<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model {

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['customer_id', 'user_id', 'total_discount', 'charges', 'payment_type', 'total'];

	public function products(){
		return $this->belongsToMany('App\Product')->withPivot('qty', 'amount','discount','sub_total');
	}

	public function customer(){
		return $this->belongsTo('App\Customer');
	}

	public function user(){
		return $this->belongsTo('App\User');
	}

	public function activeUser()
	{
	    return $this->belongsTo('App\User', 'active_user');
	}

}
