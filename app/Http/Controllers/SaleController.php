<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Sale;
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
use Laratrust;

class SaleController extends Controller {


	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return view('sales.index');
	}

	public function printInvoice($id)
	{
		$sale = Sale::find($id);
		return view('sales.print')->with('sale',$sale);
	}

	public function fetch()
	{
		$sales = Sale::select(['id','customer_id','user_id','total','status','payment_type','active','active_user','created_at']);
    	return Datatables::of($sales)->addColumn('action', function ($sale) {

		    	if($sale->active == '1' && $sale->active_user != 0){
		    		return '<p><i class="glyphicon glyphicon-lock"></i> '.$sale->activeUser->first_name.' is editing this record ...</p>';
		    	} else if($sale->active == '1' && $sale->active_user == 0){
		    		return '<a href="'.route('sales.show', $sale->id).'" class="btn btn-primary"><i class="glyphicon glyphicon-new-window"></i> View</a><p><i class="glyphicon glyphicon-lock"></i> User(s) are viewing this record ...</p>';
		    	} else {
		    		if(Laratrust::hasRole(['owner', 'admin'])){
		    			return '<a href="'.route('sales.show', $sale->id).'" class="btn btn-primary"><i class="glyphicon glyphicon-new-window"></i> View</a>
		                <a href="'.route('sales.edit', $sale->id).'" class="btn btn-success"><i class="glyphicon glyphicon-pencil"></i>  '.trans('button.edit').'</a>
		                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#dialog-confirm_delete" data-whatever='.$sale->id.'><i class="glyphicon glyphicon-trash"></i> Delete
		          		</button>';
		    		}else{
		                return '<a href="'.route('sales.show', $sale->id).'" class="btn btn-primary"><i class="glyphicon glyphicon-new-window"></i> View</a>';
		    		}
		    	}
            })
    	->editColumn('id', function ($sale) {
                return 'SN'. str_pad($sale->id , 7, '0', STR_PAD_LEFT);
            })
    	->editColumn('customer_id', function ($sale) {
                return $sale->customer->name;
            })
    	->editColumn('user_id', function ($sale) {
                return $sale->user->first_name;
            })
    	->editColumn('total', function ($sale) {
                return number_format($sale->total,2);
            })
    	->editColumn('status', function ($sale) {
    			if($sale->status == 0){
    				return '<span class="label label-danger">Unpaid</span>';
			    }else{
			    	return '<span class="label label-success">Paid</span>';
			    }     
            })
    	->editColumn('payment_type', function ($sale) {
                return ucfirst($sale->payment_type);
            })
    	->removeColumn('active')
    	->removeColumn('active_user')
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
            return view('sales.create')->with('categories',$categories);
        }
        $oldCart = Session::get('cart');
        $cart = new Cart($oldCart);
        return view('sales.create', ['products' => $cart->items, 'categories'=>$categories]);

		
		
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$items = json_decode($request->items);

		$sale = Sale::create(array_merge($request->all(), ['user_id' => Auth::user()->id]));

		$products = array();

    	foreach($items as $item)
    	{
        	 $products[$item->product_id] = ['qty' => $item->qty, 'amount' => $item->sale, 'discount' =>$item->discount, 'sub_total' => $item->amount];
        	 $product = Product::find($item->product_id);
        	 $product->qty = $product->qty - $item->qty;
        	 $product->save();
    	}

    	$sale->products()->sync($products, false);

    	return $sale->id;

	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$sale = Sale::find($id);
		$sale->active = 1;
		$sale->save();
		return view('sales.show')->with('sale',$sale);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$sale = Sale::find($id);

		if($sale->active == 1){

			Session::flash('warning','User editing this record...');
			return redirect()->route('sales.index');

		}else{
			$sale->active = 1;
			$sale->active_user = Auth::user()->id;
			$sale->save();

			$categories = UOM::all();

			$invoice = [];

			foreach ($sale->products as $product) {
				$invoice []= array('product_id' => $product->id, 'qty' => $product->pivot->qty, 'sale' => $product->pivot->amount, 'amount' => $product->pivot->sub_total, 'discount' => $product->pivot->discount);
			}

			return view('sales.edit')->with('categories',$categories)->with('sale',$sale)->with('invoice',$invoice);
		}
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request)
	{
		$total_amount = $request->total;
		$total_discount = $request->total_discount;
		$card_payment_charge = $request->charge;
		$payment_type = $request->payment_type;
		$items = json_decode(stripslashes($request->items));

		$sale = Sale::find($request->id);
		
		$sale->customer_id = $request->customer_id;
		$sale->total_discount = $total_discount;
		$sale->charges = $card_payment_charge;
		$sale->payment_type = $payment_type;
		$sale->total = $total_amount;
		$sale->active = 0;
		$sale->active_user = 0;
		$sale->save();

		$products = array();

    	foreach($items as $item)
    	{	
    		
    		$bo = 0;

    		if($a = $sale->products()->wherePivot('product_id', $item->product_id)->first()){
	             		$bo = $a->pivot->qty;
	        }

	        if($item->qty > 0){
	        	$products[$item->product_id] = ['qty' => $item->qty, 'amount' => $item->sale, 'sub_total' => $item->amount];
	        } 
	               	 
        	 $product = Product::find($item->product_id);
        	 $product->qty = $product->qty - $item->qty + $bo;
        	 $product->save();
    	}

    	$sale->products()->sync($products, true);

    	return route('sales.show', $sale->id);
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

		$sale = Sale::find($id);

		foreach($sale->products as $item)
    	{
        	 $product = Product::find($item->product_id);
        	 $product->qty = $product->qty + $item->pivot->qty;
        	 $product->save();
    	}

		$sale->products()->detach(); // Remove records from product_sale table reference to this sale

		$sale->delete();

		return 'The sale was successfully deleted!';
	}

	public function active($id)
	{
		$sale = Sale::find($id);
		$sale->active = 0;
		$sale->active_user = 0;
		$sale->save();
	}

	public function pay(Request $request)
	{
		$id = $request->sale_id;

		$sale = Sale::find($id);

		$sale->status = 1;

		$sale->save();

	}

	public function printReciept($id)
	{	

	$sale = Sale::find($id);
	//return view('sales.reciept')->with('sale',$sale);

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
	  $printer -> text("Date : ". date('j - M - Y h:iA',strtotime($sale->created_at)));
	  $printer -> feed();
	  $printer -> text("Invoice No : SN" .str_pad($sale->id , 7, '0', STR_PAD_LEFT));
	  $printer -> feed();
	  $printer -> text("Cus. Name : " . $sale->customer->name);
	  $printer -> feed(2);
	  // print Item Table Header
      // ===========
	  $header = str_pad("Item",8," ").str_pad("Unit Price",12," ", STR_PAD_LEFT).str_pad("Qty.",6," ", STR_PAD_LEFT).str_pad("Amount",12," ", STR_PAD_LEFT);
	  $printer -> text($header."\n");
      $printer -> text("----------------------------------------");
      $printer -> feed();

      foreach($sale->products as $product){
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
	 $total_discount = str_pad("Total Discount",26," ", STR_PAD_LEFT).str_pad(number_format($sale->total_discount,2),12," ", STR_PAD_LEFT);
     $printer -> text($total_discount);
	 $printer -> feed(); 

	 if($sale->payment_type == 'card'){
	 $card_payment = str_pad("Card Payment Charges",26," ", STR_PAD_LEFT).str_pad(number_format($sale->charges,2),12," ", STR_PAD_LEFT);
     $printer -> text($card_payment);
	 $printer -> feed();
	 }	 
	  
	 $total_amount = str_pad("Total Amount",26," ", STR_PAD_LEFT).str_pad(number_format($sale->total,2),12," ", STR_PAD_LEFT);
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

		return redirect()->route('sales.create');
	}

}
