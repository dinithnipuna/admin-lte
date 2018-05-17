<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\Role;
use Illuminate\Http\Request;
use Datatables;
use Session;
use Image;
use Storage;
use Hash;

class UserController extends Controller {

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
		return view('users.index');
	}


	public function fetch()
	{
		$users = User::select(['id','first_name', 'last_name','email']);
    	return Datatables::of($users)
    	->addColumn('role', function ($user) {
                if($user->roles()->count() > 0){
    				return $user->roles()->first()->display_name;
    			}else{
    				return "Not Assign";
    			}
            })
    	->addColumn('action', function ($user) {
                return '<a href="'.route('users.edit', $user->id).'" class="btn btn-success"><i class="glyphicon glyphicon-pencil"></i>  '.trans('button.edit').'</a>
                        <a href="'.route('users.destroy', $user->id).'" class="btn btn-danger"><i class="glyphicon glyphicon-trash"></i>  '.trans('button.delete').'</a>';
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
		$roles = Role::all();
		return view('users.create')->with('roles',$roles);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$request->merge(['password' => Hash::make($request->password)]);
        
        $user = User::create($request->all());

     	$role = Role::find($request->role);

		$user->attachRole($role);

		Session::flash('success','The User was successfully save!');

		return redirect()->route('users.index');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$member = Member::find($id);
		return view('members.show')->with('member',$member);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$user = User::find($id);
		$roles = Role::all();
		$roleOptions = array();

		foreach ($roles as $role)
		{
		    $roleOptions[$role->id] = $role->display_name;
		}
		return view('users.edit')->with('user',$user)->with('roleOptions',$roleOptions);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request, $id)
	{
		$user = User::find($id);
		$request->merge(['password' => Hash::make($request->password)]);  
        $user->update($request->all());

		$role = Role::find($request->role);

		$user->detachRoles($user->roles);

		$user->attachRole($role);

		Session::flash('success','The User was successfully updated!');

		return redirect()->route('users.index');
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

	public function setPermission($id)
	{
		$user = Sentinel::findById($id);

		$user->permissions = [
			'users.index' => true,
			'users.show' => true,
		    'users.create' => true,
		    'users.delete' => false,
		];

		$user->save();

		return redirect()->route('users.index');
	}

}
