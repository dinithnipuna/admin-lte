<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class UOM extends Model {

	public $table = 'product_uom';


	public function products(){
		return $this->hasMany('App\Product');
	}

	public function category(){
		return $this->belongsTo('App\UOM_Category');
	}

}
