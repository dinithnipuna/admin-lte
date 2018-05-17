<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Image;
use Settings;
use Storage;

class SettingController extends Controller {

	public function __construct()
	{
		$this->middleware('role:admin');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return view('settings.index');
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
	public function store(Request $request)
	{
		
        Settings::set('business_name', $request->business_name);
        Settings::set('business_address_1', $request->business_address_1);
        Settings::set('business_address_2', $request->business_address_2);
        Settings::set('land_phone', $request->land_phone);
        Settings::set('mobile', $request->mobile);
        Settings::set('card_payment_charge', $request->card_payment_charge);
        Settings::set('notice',$request->notice);
        Settings::set('message',$request->message);

        if($request->hasFile('business_logo')){
        	if(Settings::get('business_logo') != null){
				Storage::delete('business/'.Settings::get('business_logo'));
			}
			$avatar = $request->file('business_logo');
			$fileName = 'logo.'.$avatar->getClientOriginalExtension();
			$location = public_path('images/business/').$fileName;
			Image::make($avatar)
			->resize(256, null, function ($constraint) {
    			$constraint->aspectRatio();
			})
			->save($location);
			Settings::set('business_logo', $fileName);
		}

		if($request->hasFile('business_logo_mini')){
			if(Settings::get('business_logo_mini') != null){
				Storage::delete('business/'.Settings::get('business_logo_mini'));
			}
			$avatar = $request->file('business_logo_mini');
			$fileName = 'logo-mini.'.$avatar->getClientOriginalExtension();
			$location = public_path('images/business/').$fileName;
			Image::make($avatar)
			->resize(256, null, function ($constraint) {
    			$constraint->aspectRatio();
			})
			->save($location);
			Settings::set('business_logo_mini', $fileName);
		}

		if($request->hasFile('bg_image')){
			if(Settings::get('bg_image') != null){
				Storage::delete('business/'.Settings::get('bg_image'));
			}
			$avatar = $request->file('bg_image');
			$fileName = 'bg_image.'.$avatar->getClientOriginalExtension();
			$location = public_path('images/business/').$fileName;
			Image::make($avatar)->save($location);
			Settings::set('bg_image', $fileName);
		}

		

		if($request->delete_logo === 'yes'){
			if(Settings::get('business_logo') != null){
				Storage::delete('business/'.Settings::get('business_logo'));
			}
			Settings::forget('business_logo');
		}

		if($request->delete_logo_mini === 'yes'){
			if(Settings::get('business_logo_mini') != null){
				Storage::delete('business/'.Settings::get('business_logo_mini'));
			}
			Settings::forget('business_logo_mini');
		}

		if($request->delete_bg_image === 'yes'){
			if(Settings::get('bg_image') != null){
				Storage::delete('business/'.Settings::get('bg_image'));
			}
			Settings::forget('bg_image');
		}		

        return 'The Settings was successfully updated!';
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
