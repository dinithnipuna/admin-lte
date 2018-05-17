<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Supplier;
use Datatables;
use Session;
use Image;
use Storage;
use Response;

class SupplierController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return view('suppliers.index');
	}

	public function fetch()
	{
		$suppliers = Supplier::select(['id','name','created_at','updated_at']);
    	return Datatables::of($suppliers)->addColumn('action', function ($supplier) {
                return '<a href="'.route('suppliers.show', $supplier->id).'" class="btn btn-primary"><i class="glyphicon glyphicon-new-window"></i> View</a>
                        <a href="'.route('suppliers.edit', $supplier->id).'" class="btn btn-success"><i class="glyphicon glyphicon-pencil"></i> Edit</a>';
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
		return view('suppliers.create');
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
    		'address' => 'required',
    		'tel' => 'required',
    		'mobile' => 'required',
		]);

		if($request->hasFile('avatar')){
			$avatar = $request->file('avatar');
			$fileName = time().'.'.$avatar->getClientOriginalExtension();
			$location = public_path('images/suppliers/').$fileName;
			Image::make($avatar)->resize(128, 128)->save($location);
			$supplier->avatar = $fileName;
		}

		$supplier = Supplier::create(array_merge($request->all(), ['avatar' => $fileName]));

		if($request->ajax_post){
			return Response::json($supplier);
		}else{
			Session::flash('success','The Supplier was successfully save!');
			return redirect()->route('suppliers.index');
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
		$supplier = Supplier::find($id);
		return view('suppliers.show')->with('supplier',$supplier);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$supplier = Supplier::find($id);
		return view('suppliers.edit')->with('supplier',$supplier);
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
    		'address' => 'required',
    		'tel' => 'required',
    		'mobile' => 'required',
		]);

		$supplier = Supplier::find($id);

		$supplier->name = $request->input('name');
		$supplier->address = $request->input('address');
		$supplier->tel = $request->input('tel');
		$supplier->mobile = $request->input('mobile');

		if($request->hasFile('avatar')){
			$avatar = $request->file('avatar');
			$fileName = time().'.'.$avatar->getClientOriginalExtension();
			$location = public_path('images/suppliers/').$fileName;
			Image::make($avatar)->resize(128, 128)->save($location);
			$oldAvatar = $supplier->avatar;	
			$supplier->avatar = $fileName;
			if($oldAvatar != null){
				Storage::delete('suppliers/'.$oldAvatar);
			}
			
		}

		$supplier->save();

		Session::flash('success','The Supplier was successfully updated!');

		return redirect()->route('suppliers.index');
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
