<?php

namespace App\Http\Controllers;

use App\User;
use App\Mail\Welcome;
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
        $this->middleware('auth');
    }

    public function index()
    {
    // $role = Role::create(['name' => 'admin']);
    // $permission = Permission::create(['name'=>'create-users']);
    // $role = Role::create(['name' => 'super-admin']);
    // $permission = Permission::create(['name'=>'create-admins']);
    // auth()->user()->assignRole($role);
    // auth()->user()->givePermissionTo($permission);

       $admin_id = auth()->user()->id;

       $admin = User::find($admin_id);

       $users = User::where('parent_id', $admin_id)->get();
         
        if($admin->hasRole('super-admin')){

            return view('users.adminsManagment')->with(['admins'=> $users]);

        }else if($admin->hasRole('admin')) {

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
        $deletedUser = User::onlyTrashed()
        ->where('email', $request->input('email'));

        if(count($deletedUser->get())>0)
        {
            $getId = $deletedUser->get()->toArray()[0]['id'];
            $deletedUser->restore();
            $user = User::find($getId);
            $user->parent_id = auth()->user()->id;
            $user->save();

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email'
        ]);
        }else{

        
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
        }

        return redirect()->back();
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

        $users = User::where('parent_id', $id)->get();

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
        $deletedUser = User::onlyTrashed()
                ->where('email', $request->input('email'));

        if(count($deletedUser->get())>0)
        {
            $getId = $deletedUser->get()->toArray()[0]['id'];
            $deletedUser->restore();
            $user = User::find($getId);
            $user->parent_id = $id;
            $user->save();
        
        }else{
            $this->validate($request, [
                'name' => 'required',
                'email' => 'required|email|unique:users,email'
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
        }

        return redirect()->back();
    }
}
