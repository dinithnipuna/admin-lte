<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Customer;
use Illuminate\Http\Request;
use Datatables;
use Session;
use Image;
use Storage;
use Response;

class CustomerController extends Controller {

	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return view('customers.index');
	}


	public function fetch()
	{
		$customers = Customer::select(['id','name','created_at','updated_at']);
    	return Datatables::of($customers)->addColumn('action', function ($customer) {
                return '<a href="'.route('customers.show', $customer->id).'" class="btn btn-primary"><i class="glyphicon glyphicon-new-window"></i> View</a>
                        <a href="'.route('customers.edit', $customer->id).'" class="btn btn-success"><i class="glyphicon glyphicon-pencil"></i> Edit</a>';
            })
        ->make();	
    }
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('customers.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$this->validate($request, [
    		'name' => 'required|max:255',
    		'job' => 'max:255',
    		'address' => 'required',
    		'tel' => 'required',
    		'mobile' => 'required',
		]);

		$customer = new Customer;

		$customer->name = $request->name;
		$customer->job = $request->job;
		$customer->address = $request->address;
		$customer->tel = $request->tel;
		$customer->mobile = $request->mobile;

		if($request->hasFile('avatar')){
			$avatar = $request->file('avatar');
			$fileName = time().'.'.$avatar->getClientOriginalExtension();
			$location = public_path('images/customers/').$fileName;
			Image::make($avatar)->resize(128, 128)->save($location);
			$customer->avatar = $fileName;
		}

		$customer->save();

		if($request->ajax_post){
			return Response::json($customer);
		}else{
			Session::flash('success','The Customer was successfully save!');
			return redirect()->route('customers.index');
		}	
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$customer = Customer::find($id);
		return view('customers.show')->with('customer',$customer);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$customer = Customer::find($id);
		return view('customers.edit')->with('customer',$customer);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request, $id)
	{
		$this->validate($request, [
    		'name' => 'required|max:255',
    		'job' => 'max:255',
    		'address' => 'required',
    		'tel' => 'required',
    		'mobile' => 'required',
		]);

		$customer = Customer::find($id);

		$customer->name = $request->input('name');
		$customer->job = $request->input('job');
		$customer->address = $request->input('address');
		$customer->tel = $request->input('tel');
		$customer->mobile = $request->input('mobile');

		if($request->hasFile('avatar')){
			$avatar = $request->file('avatar');
			$fileName = time().'.'.$avatar->getClientOriginalExtension();
			$location = public_path('images/customers/').$fileName;
			Image::make($avatar)->resize(128, 128)->save($location);
			$oldAvatar = $customer->avatar;	
			$customer->avatar = $fileName;
			if($oldAvatar != null){
				Storage::delete('customers/'.$oldAvatar);
			}
			
		}

		$customer->save();

		Session::flash('success','The Customer was successfully updated!');

		return redirect()->route('customers.index');
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
