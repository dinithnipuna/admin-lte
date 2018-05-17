<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Bill;
use App\Product;
use Datatables;
use PDF;
use Escpos;
use App\UOM;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\CapabilityProfile;
use Mike42\Escpos\Printer;
use Settings;
use App\Cart;
use Session;
use Auth;
use Response;

class BillController extends Controller {


	public function __construct()
	{
		$this->middleware('role:admin|owner');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return view('bills.index');
	}

	public function printInvoice($id)
	{
		$bill = Bill::find($id);
		return view('bills.print')->with('sale',$bill);
	}

	public function fetch()
	{
		$bills = Bill::select(['id','customer_id','user_id','total','status','payment_type','created_at']);
    	return Datatables::of($bills)->addColumn('action', function ($bill) {
                return '<a href="'.route('bills.show', $bill->id).'" class="btn btn-primary"><i class="glyphicon glyphicon-new-window"></i> View</a>
                 <a href="'.route('bills.edit', $bill->id).'" class="btn btn-success"><i class="glyphicon glyphicon-pencil"></i>  '.trans('button.edit').'</a>
                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#dialog-confirm_delete" data-whatever='.$bill->id.'><i class="glyphicon glyphicon-trash"></i> Delete
          </button>';
            })
    	->editColumn('id', function ($bill) {
                return 'BN'. str_pad($bill->id , 7, '0', STR_PAD_LEFT);
            })
    	->editColumn('customer_id', function ($bill) {
                return $bill->customer->name;
            })
    	->editColumn('user_id', function ($bill) {
                return $bill->user->first_name;
            })
    	->editColumn('total', function ($bill) {
                return number_format($bill->total,2);
            })
    	->editColumn('status', function ($bill) {
    			if($bill->status == 0){
    				return '<span class="label label-danger">Unpaid</span>';
			    }else{
			    	return '<span class="label label-success">Paid</span>';
			    }     
            })
    	->editColumn('payment_type', function ($bill) {
                return ucfirst($bill->payment_type);
            })
        ->make();	
    }

    public function getProduct(Request $request)
	{
		$this->validate($request, [
			'term' => 'required|numeric',
		]);

		$term =$request->term;

		$product = Product::where('product_id', $term)
				->orWhere('barcode', $term)
				->firstOrFail();

		return $product;
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$categories = UOM::all();

		if (!Session::has('cart')) {
            return view('bills.create')->with('categories',$categories);
        }
        $oldCart = Session::get('cart');
        $cart = new Cart($oldCart);
        return view('bills.create', ['products' => $cart->items, 'categories'=>$categories]);

		
		
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$total_amount = $request->total_amount;
		$total_discount = $request->total_discount;
		$card_payment_charge = $request->card_payment_charge;
		$payment_type = $request->payment_type;
		$items = json_decode($request->items);

		$bill = new Bill;
		
		$bill->customer_id = $request->customer_id;
		$bill->user_id = Auth::user()->id;
		$bill->total_discount = $total_discount;
		$bill->charges = $card_payment_charge;
		$bill->payment_type = $payment_type;
		$bill->total = $total_amount;
		
		$bill->save();

		$products = array();



    	foreach($items as $item)
    	{
        	 $products[$item->product_id] = ['qty' => $item->qty, 'amount' => $item->sale, 'discount' =>$item->discount, 'sub_total' => $item->amount];
        	 $product = Product::find($item->product_id);
        	 $product->qty = $product->qty - $item->qty;
        	 $product->save();
    	}

    	$bill->products()->sync($products, false);

    	return $bill->id;

	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$bill = Bill::find($id);
		return view('bills.show')->with('sale',$bill);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$bill = Bill::find($id);

		$categories = UOM::all();

		$invoice = [];

		foreach ($bill->products as $product) {
			$invoice []= array('product_id' => $product->id, 'qty' => $product->pivot->qty, 'sale' => $product->pivot->amount, 'amount' => $product->pivot->sub_total, 'discount' => $product->pivot->discount);
		}

		return view('bills.edit')->with('categories',$categories)->with('bill',$bill)->with('invoice',$invoice);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request)
	{
		$total_amount = $request->total_amount;
		$total_discount = $request->total_discount;
		$card_payment_charge = $request->card_payment_charge;
		$payment_type = $request->payment_type;
		$items = json_decode(stripslashes($request->items));

		$bill = Bill::find($request->id);
		
		$bill->customer_id = $request->customer_id;
		$bill->total_discount = $total_discount;
		$bill->charges = $card_payment_charge;
		$bill->payment_type = $payment_type;
		$bill->total = $total_amount;
		
		$bill->save();

		$products = array();

    	foreach($items as $item)
    	{	
    		
    		$bo = 0;

    		if($a = $bill->products()->wherePivot('product_id', $item->product_id)->first()){
	             		$bo = $a->pivot->qty;
	        }

	        if($item->qty > 0){
	        	$products[$item->product_id] = ['qty' => $item->qty, 'amount' => $item->sale, 'sub_total' => $item->amount];
	        } 
	               	 
        	 $product = Product::find($item->product_id);
        	 $product->qty = $product->qty - $item->qty + $bo;
        	 $product->save();
    	}

    	$bill->products()->sync($products, true);

    	return route('bills.show', $bill->id);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Request $request)
	{
		$id = $request->sale_id;

		$bill = Bill::find($id);

		foreach($bill->products as $item)
    	{
        	 $product = Product::find($item->product_id);
        	 $product->qty = $product->qty + $item->pivot->qty;
        	 $product->save();
    	}

		$bill->products()->detach(); // Remove records from product_sale table reference to this sale

		$bill->delete();

		return 'The sale was successfully deleted!';
	}

	public function pay(Request $request)
	{
		$id = $request->sale_id;

		$bill = Bill::find($id);

		$bill->status = 1;

		$bill->save();

	}

	public function printReciept($id)
	{	

	$bill = Bill::find($id);
	//return view('bills.reciept')->with('sale',$bill);

	try {
	// Enter the share name for your USB printer here
	$connector = new WindowsPrintConnector("BIXOLON SRP-275");
	$printer = new Printer($connector);

	  // print Company Name, Address,Contacts
      // ===========

	  $business_name = Settings::get('business_name');
	  $business_address_1 = Settings::get('business_address_1');
	  $business_address_2 = Settings::get('business_address_2');
	  $contact_details = "Tel : ".Settings::get('land_phone')." Mobile : ".Settings::get('mobile');
	  $notice = Settings::get('notice');
	  $message = Settings::get('message');
      
      $printer -> setJustification(Printer::JUSTIFY_CENTER);
	  $printer -> setFont(Printer::FONT_A);
	  $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
      $printer -> text($business_name."\n"); // Comoany Name
      $printer -> selectPrintMode();
	  $printer -> setFont(Printer::FONT_B);
	  $printer -> text($business_address_1."\n"); // Address Line 1
	  $printer -> text($business_address_2."\n"); // Address Line 2
	  $printer -> text($contact_details); // Contacts
	  $printer -> feed(2);
	  // print Date, Invoice, Customer Name
      // ===========
	  $printer -> setJustification(Printer::JUSTIFY_LEFT);
	  $printer -> text("Date : ". date('j - M - Y h:iA',strtotime($bill->created_at)));
	  $printer -> feed();
	  $printer -> text("Invoice No : BN" .str_pad($bill->id , 7, '0', STR_PAD_LEFT));
	  $printer -> feed();
	  $printer -> text("Cus. Name : " . $bill->customer->name);
	  $printer -> feed(2);
	  // print Item Table Header
      // ===========
	  $header = str_pad("Item",8," ").str_pad("Unit Price",12," ", STR_PAD_LEFT).str_pad("Qty.",6," ", STR_PAD_LEFT).str_pad("Amount",12," ", STR_PAD_LEFT);
	  $printer -> text($header."\n");
      $printer -> text("----------------------------------------");
      $printer -> feed();

      foreach($bill->products as $product){
      	$product_name = $product->product_name;
      	if($product->warranty > 0){
      		$product_name.="( ".$product->warranty." Months Warranty )";
      	}
      	  $printer -> text($product_name."\n");
			$item= str_pad($product->product_id,8," ").str_pad(number_format($product->pivot->amount,2),12," ", STR_PAD_LEFT).str_pad($product->pivot->qty,6," ", STR_PAD_LEFT).str_pad(number_format($product->pivot->sub_total,2),12," ", STR_PAD_LEFT);
	        $printer -> text($item);
			$printer -> feed(2);
 	}


     $printer -> setJustification(Printer::JUSTIFY_LEFT);
	 $printer -> text("----------------------------------------");
	 $printer -> feed();
	  
	   // print Total Amount, Paid Amount, Balance
      // ===========
	 $printer -> setJustification(Printer::JUSTIFY_LEFT);
	 $total_discount = str_pad("Total Discount",26," ", STR_PAD_LEFT).str_pad(number_format($bill->total_discount,2),12," ", STR_PAD_LEFT);
     $printer -> text($total_discount);
	 $printer -> feed(); 

	 if($bill->payment_type == 'card'){
	 $card_payment = str_pad("Card Payment Charges",26," ", STR_PAD_LEFT).str_pad(number_format($bill->charges,2),12," ", STR_PAD_LEFT);
     $printer -> text($card_payment);
	 $printer -> feed();
	 }	 
	  
	 $total_amount = str_pad("Total Amount",26," ", STR_PAD_LEFT).str_pad(number_format($bill->total,2),12," ", STR_PAD_LEFT);
     $printer -> text($total_amount);
	 $printer -> feed(2);
	   // print Notice
      // ===========
     $printer -> setJustification(Printer::JUSTIFY_CENTER);
	 $printer -> text($notice."\n");
	 $printer -> text($message);
	 $printer -> feed(2);
	 $printer -> text("ATLA Software.");
	 $printer -> feed();
	 $printer -> cut(); 
		
	/* Close printer */
	$printer -> close();
		
		} catch(Exception $e) {
			echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";
		}

		return redirect()->route('bills.create');
	}

}
