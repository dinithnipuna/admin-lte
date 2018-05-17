<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Cart;
use Session;
use App\Sale;
use App\Product;
use Response;

class CartController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return view('sales.cart');
	}

	public function getCart()
	{
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
		return Response::json($oldCart);
	}

	public function getAddToCart(Request $request, $id)
	{
		$product = Product::find($id);
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);
        $cart->add($product, $id);
        $request->session()->put('cart', $cart);

		return redirect()->route('products.index');
	}

	public function remove($id)
    {
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);
        $cart->remove($id);
        if (count($cart->items) > 0) {
            Session::put('cart', $cart);
        } else {
            Session::forget('cart');
        }
    }


	public function clear(Request $request)
    {
       $request->session()->flush();
    }

    public function setQuantity(Request $request)
    {
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $oldCart->setQuantity($request->id, $request->value);
      	return $request->value;
    }

    public function setDiscount(Request $request)
    {
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $oldCart->setDiscount($request->id, $request->value);
      	return $request->value;
    }


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
