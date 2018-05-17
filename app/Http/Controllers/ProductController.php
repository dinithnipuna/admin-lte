<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Product;
use Illuminate\Http\Request;
use Session;
use Yajra\Datatables\Datatables;
use App\UOM;
use Response;

class ProductController extends Controller {


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
		$user = env('DB_USERNAME');
		$password = env('DB_PASSWORD');
		$database = env('DB_DATABASE');

		$categories = UOM::all();
		return view('products.index')->with('categories',$categories);
	}

	public function anyData()
	{
		$products = Product::select(['id','product_id','product_name','qty','uom_id','cost','sale']);
    	return Datatables::of($products)
    	->addColumn('action', function ($product) {
                return '
                       <a href="" class="btn btn-success" data-toggle="modal" data-target="#editProduct" data-productId="'.$product->id.'"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
                       <a href="'.route('cart.add', $product->id).'" class="btn btn-warning"><i class="glyphicon glyphicon-new-window"></i> Add To Cart</a>
                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteProduct" data-productId='.$product->id.'><i class="glyphicon glyphicon-trash"></i> Delete
          </button>';
            })
    	->editColumn('qty', function ($product) {
                return number_format($product->qty,3);
            })
    	->editColumn('uom_id', function ($product) {
                return $product->uom->name;
            })
    	->editColumn('cost', function ($product) {
                return number_format($product->cost,2);
            })
    	->editColumn('sale', function ($product) {
                return number_format($product->sale,2);
            })
    	->removeColumn('id')
        ->make();
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$categories = UOM::all();
		return view('products.create')->with('categories',$categories);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$this->validate($request, [
			'product_id' => 'required',
    		'product_name' => 'required|max:255',
    		'uom_id' => 'required',
    		'qty' => 'required',
    		'cost' => 'required',
    		'sale' => 'required'
		]);

		$product = new Product;

		$product->product_id = $request->product_id;
		$product->product_name = $request->product_name;
		$product->barcode = $request->barcode;
		$product->uom_id = $request->uom_id;
		$product->qty = $request->qty;
		$product->cost = $request->cost;
		$product->sale = $request->sale;
		$product->warranty = $request->warranty;
		$product->rop = $request->rop;

		$product->save();


        $results = array(
	      	'success' => 'The Product was successfully save!',
	        'item' => $product
      	);

        return Response::json($results);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$product = Product::find($id);
		return $id;
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit(Request $request)
	{
		$id = $request->id;
		$product = Product::find($id);
		$uoms = UOM::all();
		$uomOptions = array();

		foreach ($uoms as $uom)
		{
		    $uomOptions[$uom->id] = $uom->name;
		}
		return $product;
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request)
	{
		$this->validate($request, [
    		'product_name' => 'required|max:255',
    		'qty' => 'required',
    		'cost' => 'required',
    		'sale' => 'required',
		]);

		$id = $request->id;

		$product = Product::find($id);

		$product->product_id = $request->product_id;
		$product->product_name = $request->input('product_name');
		$product->barcode = $request->barcode;
		$product->uom_id = $request->uom_id;
		$product->qty = $request->input('qty');
		$product->cost = $request->input('cost');
		$product->sale = $request->input('sale');
		$product->warranty = $request->warranty;
		$product->rop = $request->rop;

		$product->save();

		return 'The Product was successfully updated!';
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Request $request)
	{
		$id = $request->id;

		$product = Product::find($id);

		$product->delete();

		return 'The product was successfully deleted!';
	}

}
