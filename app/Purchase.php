<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model {

	public function products(){
		return $this->belongsToMany('App\Product')->withPivot('qty', 'amount','sub_total');
	}

	public function supplier(){
		return $this->belongsTo('App\Supplier');
	}

	public function user(){
		return $this->belongsTo('App\User');
	}

}
