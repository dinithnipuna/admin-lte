<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model {

	public function products(){
		return $this->belongsToMany('App\Product')->withPivot('qty', 'amount','discount','sub_total');
	}

	public function customer(){
		return $this->belongsTo('App\Customer');
	}

	public function user(){
		return $this->belongsTo('App\User');
	}

}
