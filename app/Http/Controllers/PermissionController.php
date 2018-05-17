<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Datatables;
use App\Permission;
use Response;

class PermissionController extends Controller {

	public function __construct()
	{
		$this->middleware('role:admin');
	}

	public function fetch()
	{
		$permissions = Permission::select(['id','name','display_name','description']);

    	return Datatables::of($permissions)->addColumn('action', function ($permission) {
                return '
                <a href="" data-toggle="modal" data-target="#editPermissionModal" data-permissionId="'.$permission->id.'" class="btn btn-success"><i class="glyphicon glyphicon-pencil"></i>  '.trans('button.edit').'</a>
                 <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#dialog-confirm_delete" data-roleId='.$permission->id.'><i class="glyphicon glyphicon-trash"></i>  '.trans('button.delete').'
          </button>';
            })
        ->make();	
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return view('permissions.index');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$this->validate($request, [
    		'name' => 'required|max:255'
		]);

		$permission = new Permission();
		$permission->name         = $request->name;
		$permission->display_name = $request->display_name; 
		$permission->description  = $request->description;
		$permission->save();
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
	public function edit(Request $request)
	{
		$id = $request->id;
		$permission = Permission::find($id);
		return Response::json($permission);
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
