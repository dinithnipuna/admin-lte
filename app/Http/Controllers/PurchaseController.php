<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Purchase;
use App\Product;
use Datatables;
use App\UOM;
use Session;
use Auth;

class PurchaseController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return view('purchases.index');
	}

	public function fetch()
	{
		$purchases = Purchase::select(['id','supplier_id','user_id','total','status','created_at']);
    	return Datatables::of($purchases)->addColumn('action', function ($purchase) {
                return '<a href="'.route('purchases.show', $purchase->id).'" class="btn btn-primary"><i class="glyphicon glyphicon-new-window"></i> View</a>
                <a href="'.route('purchases.edit', $purchase->id).'" class="btn btn-success"><i class="glyphicon glyphicon-pencil"></i>  '.trans('button.edit').'</a>
                 <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#dialog-confirm_delete" data-whatever='.$purchase->id.'><i class="glyphicon glyphicon-trash"></i> Delete
          </button>';
            })
    	->editColumn('id', function ($purchase) {
                return 'PO'. str_pad($purchase->id , 7, '0', STR_PAD_LEFT);
            })
    	->editColumn('supplier_id', function ($purchase) {
                return $purchase->supplier->name;
            })
    	->editColumn('user_id', function ($purchase) {
                return $purchase->user->first_name;
            })
    	->editColumn('total', function ($purchase) {
                return number_format($purchase->total,2);
            })
    	->editColumn('status', function ($purchase) {
    			if($purchase->status == 0){
    				return '<span class="label label-danger">Unpaid</span>';
			    }else{
			    	return '<span class="label label-success">Paid</span>';
			    }
                
            })
        ->make();	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$categories = UOM::all();
		return view('purchases.create')->with('categories',$categories);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{		
		
		$purchase = new Purchase;
		
		$purchase->supplier_id = $request->supplier_id;
		$purchase->user_id = Auth::user()->id;
		$purchase->sup_ref = $request->sup_ref;
		$purchase->total = $request->total_amount;
		$purchase->status = 0;
		$purchase->po_at = $request->po_date;
		
		$purchase->save();

		$items = json_decode($request->items);

		$products = array();

    	foreach($items as $item)
    	{
        	 $products[$item->product_id] = ['qty' => $item->qty, 'amount' => $item->cost, 'sub_total' => $item->amount];
        	 $product = Product::find($item->product_id);
        	 $product->qty = $product->qty + $item->qty;
        	 $product->cost = $item->cost;
        	 $product->save();
    	}

    	$purchase->products()->sync($products, false);

    	return $purchase->id;
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$purchase = Purchase::find($id);
		return view('purchases.show')->with('purchase',$purchase);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$purchase = Purchase::find($id);
		$categories = UOM::all();

		$invoice = [];

		foreach ($purchase->products as $product) {
			$invoice []= array('product_id' => $product->id, 'qty' => $product->pivot->qty, 'cost' => $product->cost, 'amount' => $product->pivot->sub_total);
		}

		return view('purchases.edit')->with('categories',$categories)->with('purchase',$purchase)->with('invoice',$invoice);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request)
	{
		$purchase = Purchase::find($request->id);
		
		$purchase->supplier_id = $request->supplier_id;
		$purchase->sup_ref = $request->sup_ref;
		$purchase->total = $request->total_amount;
		$purchase->po_at = $request->po_date;
		
		$purchase->save();

		$items = json_decode(stripslashes($request->items));

		$products = array();

    	foreach($items as $item)
    	{	
    		
    		$bo = 0;

    		if($a = $purchase->products()->wherePivot('product_id', $item->product_id)->first()){
	             		$bo = $a->pivot->qty;
	        }

	        if($item->qty > 0){
	        	$products[$item->product_id] = ['qty' => $item->qty, 'amount' => $item->cost, 'sub_total' => $item->amount];
	        } 

        	 $product = Product::find($item->product_id);
        	 $product->qty = $product->qty + $item->qty - $bo;
        	 $product->cost = $item->cost;
        	 $product->save();
    	}

    	$purchase->products()->sync($products, true);

    	return route('purchases.show', $purchase->id);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Request $request)
	{
		$id = $request->purchase_id;

		$purchase = Purchase::find($id);

		foreach($purchase->products as $item)
    	{
        	 $product = Product::find($item->product_id);
        	 $product->qty = $product->qty - $item->pivot->qty;
        	 $product->save();
    	}

		$purchase->products()->detach(); // Remove records from product_purchase table reference to this purchase

		$purchase->delete();

		return 'The purchase was successfully deleted!';

	}

	public function pay(Request $request)
	{
		$id = $request->purchase_id;

		$purchase = Purchase::find($id);

		$purchase->status = 1;

		$purchase->save();

	}

}
