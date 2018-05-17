<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model {

		/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['name', 'address', 'tel', 'phone'];

	public function purchases(){
		return $this->hasMany('App\Purchase');
	}

}
