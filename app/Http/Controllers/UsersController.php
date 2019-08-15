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

    // public function __construct()
    // {
    //     $this->middleware('guest');
    // }

    public function index()
    {

       
        // $role=Role::findById(2);
        // $role->delete();
        // $permission = Permission::findById(1);
        // $permission->delete();
    // $role = Role::create(['name' => 'admin']);
    // $permission = Permission::create(['name'=>'create-users']);
    // auth()->user()->assignRole($role);
    // auth()->user()->givePermissionTo($permission);


    //    $users = User::all();

       $admins = Admin::all();

       $admin_id = auth()->user()->id;

       $admin = User::find($admin_id);

       $users = User::where('parent_id', $admin_id)->get();
    
        // return $admin->name;

        
        if($admin->hasRole('super-admin')){
            return view('users.adminsManagment')->with(['admins'=> $users]);
        }else{
            return view('users.usersManagment')->with(['users'=> $users, 'admin'=>$admin]);
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
        $user = new user;
        $user->name =$request->input('name');
        $user->email =$request->input('email');
        $user->password = Hash::make($password);
        $user->parent_id = auth()->user()->id;
        $user->assignRole('admin');
        $user->givePermissionTo('create-users');
        $user->save();

        \Mail::to($user)->send(new Welcome($user, $password));

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
        $admin = User::find($id);



        // $users = User::get(['id', 'name', 'email', 'parent_id'=>"2"])->toArray();

        $users = User::where('parent_id', $id)->get();

       
        // $subset = $users->map->only(['id', 'name', 'email', 'parent_id']);
        // $key = array_search(2, array_column($users, 'parent_id'));
        // dd($users);

    //   return is_array($users);

        return view('users.usersManagment')->with(['users'=> $users, 'admin'=>$admin]);
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
        $user = User::find($id)->delete();

        $users = User::where('parent_id', $id)->delete();

        return redirect()->back();
    }

    public function createChild(Request $request, $id)
    {
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
        $user->parent_id = $id;
        $user->save();

        \Mail::to($user)->send(new Welcome($user, $password));

        $admin = User::find($id);

        $users = User::where('parent_id', $id)->get();


        return redirect()->back();
        return view('users.usersManagment')->with(['users'=> $users, 'admin'=>$admin]);
    }
}
