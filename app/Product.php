<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model {

	public function sales(){
		return $this->belongsToMany('App\Sale');
	}

	public function uom(){
		return $this->belongsTo('App\UOM');
	}

}
