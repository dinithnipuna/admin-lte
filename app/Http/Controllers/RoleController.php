<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Datatables;
use App\Role;
use App\Permission;
use Response;
use Session;

class RoleController extends Controller {

	public function __construct()
	{
		$this->middleware('role:admin');
	}

	public function fetch()
	{
		$roles = Role::select(['id','name','display_name','description']);

    	return Datatables::of($roles)->addColumn('action', function ($role) {
                return '
                 <a href="'.route('roles.edit', $role->id).'" class="btn btn-success"><i class="glyphicon glyphicon-pencil"></i>  '.trans('button.edit').'</a>
                 <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#dialog-confirm_delete" data-roleId='.$role->id.'><i class="glyphicon glyphicon-trash"></i>  '.trans('button.delete').'
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
		return view('roles.index');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$permissions = Permission::all();
		return view('roles.create')->with('permissions',$permissions);
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

		$role = new Role();
		$role->name         = $request->name;
		$role->display_name = $request->display_name; 
		$role->description  = $request->description;
		$role->save();

		if(isset($request->permissions)){
			foreach ($request->permissions as $key => $value) {
				$role->attachPermission($value);
			}
		}	

		return redirect()->route('roles.index');
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
		$role = Role::find($id);
		$permissions = Permission::all();
		$role_permissions = $role->permissions->pluck('id','id')->toArray();
		return view('roles.edit')->with('role',$role)->with('permissions',$permissions)->with('role_permissions',$role_permissions);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request,$id)
	{
		$role = Role::find($id);
		$role->name         = $request->name;
		$role->display_name = $request->display_name; 
		$role->description  = $request->description;
		$role->save();

		$role->detachPermissions($role->permissions);

		if(isset($request->permissions)){
			foreach ($request->permissions as $key => $value) {
				$role->attachPermission($value);
			}
		}	

		Session::flash('success','The Role was successfully updated!');

		return redirect()->route('roles.index');
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
