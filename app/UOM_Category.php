<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class UOM_Category extends Model {

	public $table = 'uom_categories';

	public function uoms(){
		return $this->hasMany('App\UOM');
	}

}
