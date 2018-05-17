<?php namespace App;


class Cart
{
	public $items = null;
	public $totalQty = 0;
	public $totalDiscount = 0;
	public $totalAmount = 0;

	public function __construct($oldCart)
    {
        if($oldCart)
        {
        	$this->items = $oldCart->items;
        	$this->totalQty = $oldCart->totalQty;
        	$this->totalAmount = $oldCart->totalAmount;
        }
    }

	public function add($item, $id)
	{
		$storedItem = ['qty' => 0, 'unit_price' => $item->sale, 'discount' => 0, 'amount' => $item->sale, 'item' => $item];

		if($this->items){
			if(array_key_exists($id, $this->items)){
				$storedItem = $this->items[$id];
			}
		}
		$storedItem['qty']++;
		$storedItem['amount'] = ($item->sale - $storedItem['discount']) * $storedItem['qty'];
		$this->items[$id] = $storedItem;
		$this->totalQty++;
		$this->totalDiscount += $storedItem['discount']; 
		$this->totalAmount += $storedItem['amount']; 
	}

	public function setQuantity($id, $quantity) {
		$oldQty = $this->items[$id]['qty'];
		$oldAmount = $this->items[$id]['amount'];
        $this->items[$id]['qty'] = $quantity;
        $this->items[$id]['amount'] = (floatval($this->items[$id]['unit_price']) - floatval($this->items[$id]['discount'])) * floatval($quantity);
        $this->totalQty += $quantity - $oldQty;
        $this->totalDiscount += ($quantity - $oldQty) * $this->items[$id]['discount'];
        $this->totalAmount += floatval($this->items[$id]['amount']) - floatval($oldAmount);
        if ($this->items[$id]['qty'] <= 0) {
            unset($this->items[$id]);
        }
    }

    public function setDiscount($id, $discount) {
		$oldDiscount = $this->items[$id]['discount'];
		$oldAmount = $this->items[$id]['amount'];
		$this->items[$id]['discount'] = $discount;
        $this->items[$id]['amount'] = ($this->items[$id]['unit_price'] - $this->items[$id]['discount']) *  $this->items[$id]['qty'];
        $this->totalDiscount += ($this->items[$id]['discount'] - $oldDiscount) * $this->items[$id]['qty'];
        $this->totalAmount += $this->items[$id]['amount'] - $oldAmount;
    }

	public function set($index, $value)
	{
		$_SESSION[$this->bucket][$index] = $value;
	}

	public function all($index)
	{
		return $_SESSION[$this->bucket];
	}

	public function exists($index)
	{
		return isset($_SESSION[$this->bucket][$index]);
	}

	public function remove($id)
	{
		$this->totalQty -= $this->items[$id]['qty'];
        $this->totalAmount -= $this->items[$id]['amount'];
        unset($this->items[$id]);
	}

	public function clear()
	{
		unset($_SESSION[$this->bucket]);
	}

	public function count()
	{
		return count($this->all());
	}
}
