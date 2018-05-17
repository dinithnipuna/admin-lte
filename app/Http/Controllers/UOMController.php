<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\UOM;
use App\UOM_Category;
use Datatables;
use Session;


class UOMController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{		
		$uoms = UOM::all();
		return view('uom.index')->withUoms($uoms);
	}

	public function fetch()
	{
		$uomList = UOM::select(['id','name','category_id']);

    	return Datatables::of($uomList)->addColumn('action', function ($uom) {
                return '
                <a href="'.route('uom.edit', $uom->id).'" class="btn btn-success"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
                 <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#dialog-confirm_delete" data-whatever='.$uom->id.'><i class="glyphicon glyphicon-trash"></i> Delete
          </button>';
            })
    	->editColumn('category_id', function ($uom) {
                return $uom->category->name;
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
		$categories = UOM_Category::all();
		return view('uom.create')->with('categories',$categories);
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
    		'category_id' => 'required|integer',
    		'uom_type' => 'required',
    		'factor' => 'required',
		]);
		
		$uom = new UOM;
		
		$uom->name = $request->name;
		$uom->category_id = $request->category_id;
		$uom->uom_type = $request->uom_type;
		$uom->factor = $request->factor;

		
		$uom->save();
	
		Session::flash('success','The UOM was successfully save!');
		
		return redirect()->route('uom.index');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$uom = UOM::find($id);
		return view('uom.show')->with('uom',$uom);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$uom = UOM::find($id);
		$categories = UOM_Category::all();
		$cats = array();
		$types = array();

		foreach ($categories as $category)
		{
		    $cats[$category->id] = $category->name;
		}

		$types['biger'] = 'Bigger than the reference Unit of Measure';
		$types['reference'] = 'Reference Unit of Measure for this category';
		$types['smaller'] = 'Smaller than the reference Unit of Measure';
		
		return view('uom.edit')->with('uom',$uom)->with('categories',$cats)->with('types',$types);
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
    		'category_id' => 'required|integer',
    		'uom_type' => 'required',
    		'factor' => 'required',
		]);
		
		$uom = UOM::find($id);
		
		$uom->name = $request->name;
		$uom->category_id = $request->category_id;
		$uom->uom_type = $request->uom_type;
		$uom->factor = $request->factor;

		
		$uom->save();
	
		Session::flash('success','The UOM was successfully updated!');
		
		return redirect()->route('uom.index');
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
