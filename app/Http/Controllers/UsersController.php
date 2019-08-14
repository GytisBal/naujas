<?php

namespace App\Http\Controllers;

use App\User;
use App\Admin;
use App\Mail\Welcome;
use App\Mail\WelcomeAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {

       
        // $role=Role::findById(2);
        // $role->delete();
        // $permission = Permission::findById(1);
        // $permission->delete();
    // $role = Role::create(['name' => 'super-admin']);
    // $permission = Permission::create(['name'=>'create-admins']);
    // auth()->user()->assignRole($role);
    // auth()->user()->givePermissionTo($permission);


       $users = User::all();

       $admins = Admin::all();

       $admin_id = auth()->user()->id;

       $admin = Admin::find($admin_id);
    
        // return $admin->name;
        if($admin->hasRole('super-admin')){
            return view('users.adminsManagment')->with(['admins'=> $admins]);;
        }else{
            return view('users.usersManagment')->with(['users'=> $admin->users, 'admin'=>$admin]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { 
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required:unique:users,email',
        ]);
        
        $password = str_random(8);

        // Create admin
        $admin = new Admin;
        $admin->name =$request->input('name');
        $admin->email =$request->input('email');
        $admin->password = Hash::make($password);
        $admin->save();

        \Mail::to($admin)->send(new WelcomeAdmin($admin, $password));

        return redirect('users');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $admin = Admin::find($id);

      

        return view('users.usersManagment')->with(['users'=> $admin->users, 'admin'=>$admin]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);

        $user->delete();
        return redirect('/users');
    }

    public function createChild(Request $request, $id)
    {
        $admin = Admin::find($id);

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required:unique:users,email',
        ]);
        
        $password = str_random(8);

        // Create user
        $user = new User;
        $user->name =$request->input('name');
        $user->email =$request->input('email');
        $user->password = Hash::make($password);
        $user->admin_id = $id;
        $user->save();

        \Mail::to($user)->send(new Welcome($user, $password));


        return view('users.usersManagment')->with(['users'=> $admin->users, 'admin'=>$admin]);
    }
}
