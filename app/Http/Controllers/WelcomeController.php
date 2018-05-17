<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Response;
use App\Product;
use App\Customer;
use App\Supplier;
use App\Purchase;
use App\Sale;
use Charts;
use Escpos;
use Carbon\Carbon;

class WelcomeController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Welcome Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders the "marketing page" for the application and
	| is configured to only allow guests. Like most of the other sample
	| controllers, you are free to modify or remove it as you desire.
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
		
	$productCount = Product::all()->count();
	$customerCount = Customer::all()->count();
	$supplierCount = Supplier::all()->count();
	$billCount = Sale::where('status', 0)->count();

	$sales =  Sale::groupBy('created_at')
                    ->selectRaw('*, sum(total) as sale')
                    ->get();

     $saleChart = Charts::database($sales,'bar', 'highcharts')
                    ->setTitle('Monthly Sales Report')
                    ->setElementLabel("Sales")
                    ->setResponsive(true)
                    ->setSumOfField('sale')
                    ->groupByMonth();

      $purchases =  Purchase::groupBy('created_at')
                    ->selectRaw('*, sum(total) as purchases')
                    ->get();

      $purchaseChart = Charts::database($purchases, 'bar', 'highcharts')
                        ->setTitle('Monthly Purchases Report')
                        ->setElementLabel("Purchases")
                        ->setResponsive(true)
                        ->setSumOfField('purchases')
                        ->groupByMonth();


		return view('welcome',[
			'productCount'=> $productCount,
			'customerCount' => $customerCount, 
			'supplierCount' => $supplierCount,
			'billCount' => $billCount
			])
		->with('saleChart',$saleChart)
		->with('purchaseChart',$purchaseChart);
	}

	public function getProduct(Request $request){

//$term = Input::get('term');
	$term =$request->term;


	$results = array();
	
/*	$queries = DB::table('products')
		->where('product_name', 'LIKE', '%'.$term.'%')
		->orWhere('product_id', 'LIKE', '%'.$term.'%')
		->take(5)->get();
*/
	$product = Product::where('product_id', $term)
			->orWhere('barcode', $term)
			->first();

	return Response::json($product);
}

	public function autocomplete(Request $request){

//$term = Input::get('term');
	$term =$request->term;


	$results = array();
	
/*	$queries = DB::table('products')
		->where('product_name', 'LIKE', '%'.$term.'%')
		->orWhere('product_id', 'LIKE', '%'.$term.'%')
		->take(5)->get();
*/
	$queries = Product::where('product_name', 'LIKE', '%'.$term.'%')
			->orWhere('product_id', 'LIKE', '%'.$term.'%')
			->orWhere('barcode', 'LIKE', '%'.$term.'%')
			->get();

	foreach ($queries as $query)
	{
	    $results[] = [ 'id' => $query->id, 'value' => $query->product_name, 'product_name' => $query->product_name,  'product_id' => $query->product_id,'qty' => $query->qty ,'sale' => $query->sale,'cost' => $query->cost ];
	}
	return Response::json($results);
}

public function customerAutocomplete(Request $request){

//$term = Input::get('term');
	$term =$request->term;


	$results = array();
	
/*	$queries = DB::table('products')
		->where('product_name', 'LIKE', '%'.$term.'%')
		->orWhere('product_id', 'LIKE', '%'.$term.'%')
		->take(5)->get();
*/
	$queries = Customer::where('name', 'LIKE', '%'.$term.'%')
			->get();

	foreach ($queries as $query)
	{
	    $results[] = [ 'id' => $query->id, 'value' => $query->name];
	}
	return Response::json($results);
}

public function supplierAutocomplete(Request $request){

//$term = Input::get('term');
	$term =$request->term;


	$results = array();
	
/*	$queries = DB::table('products')
		->where('product_name', 'LIKE', '%'.$term.'%')
		->orWhere('product_id', 'LIKE', '%'.$term.'%')
		->take(5)->get();
*/
	$queries = Supplier::where('name', 'LIKE', '%'.$term.'%')
			->get();

	foreach ($queries as $query)
	{
	    $results[] = [ 'id' => $query->id, 'value' => $query->name];
	}
	return Response::json($results);
}

}
