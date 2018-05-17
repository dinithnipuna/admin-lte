<?php namespace App;

use App\Product;
use App\Acme\Repository\CartInterface;

class Basket
{
	protected $cart;
	protected $product;

	public function __construct(CartInterface $cart, Product $product)
    { 
        $this->cart = $cart;
        $this->product = $product;
    }

    public function add(Product $product, $quantity)
    {
    	if($this->has($product)){
    		$quantity = $this->get($product)['quantity'] + $quantity;
    	}

    	$this->update($product, $quantity);
    }


    public function update(Product $product, $quantity)
    {
    	$this->cart->set($product->id,[
    		'product_id' => $product->product_id,
    		'quantity' => $quantity,
    	]);
    }

    public function has(Product $product)
    {
    	return $this->cart->exists($product->id);
    }

    public function get(Product $product)
    {
    	return $this->cart->get($product->id);
    }

    public function remove(Product $product)
    {
    	$this->cart->remove($product->id);
    }

    public function clear()
    {
    	return $this->cart->clear();
    }

    public function all()
    {
    	$ids = [];
    	$items = [];

    	foreach ($this->cart->all() as $product) {
    		$ids[] = $product['product_id'];
    	}

    	$products = $this->product->find($ids);

    	foreach ($products as $product) {
    		$product->qty = $this->get($product)['quantity'];
    		$items[] = $product;
    	}

    	return items;
    }

    public static function itemCount()
    {
    	return count($this->cart);
    }

}