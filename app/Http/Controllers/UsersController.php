<?php

namespace App\Http\Controllers;

use App\User;
use App\Mail\Welcome;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware(['auth', 'section.owner']);
    }

    public function index()
    {
        $admin_id = auth()->user()->id;
        $admin = User::find($admin_id);
        $users = User::where('parent_id', $admin_id)->get();

        if ($admin->hasRole('super-admin')) {
            return view('users.adminsManagment')->with(['admins' => $users]);
        } else if ($admin->hasRole('admin')) {
            return view('users.usersManagment')->with(['users' => $users, 'admin' => $admin]);
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $deletedUser = User::onlyTrashed()
            ->where('email', $request->input('email'));

        if (count($deletedUser->get()) > 0) {
            $getId = $deletedUser->get()->toArray()[0]['id'];
            $deletedUser->restore();

            $user = User::find($getId);

        } else {
            $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users,email'
            ]);
            // Create admin
            $user = new user;
            $user->email = $request->input('email');
        }
        if (!$user->hasRole('admin')) {
            $user->assignRole('admin');
            $user->givePermissionTo('create-users');
        }

        $password = Str::random(8);
        $user->auth_key = 'YzJkNHVpZAE6836A3F4E4C39A362E0EAB377E52C166F221554926191FEFBB8869DB6D4CF552C6EBC94151E17EF';
        $user->password = Hash::make($password);
        $user->parent_id = auth()->user()->id;
        $user->name = $request->input('name');
        $user->save();

        $devices = \App\Device::all();
        if ($user->devices->count() <= 0) {

            $user->devices()->attach($devices);
        }

        \Mail::to($user)->send(new Welcome($user, $password));

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $admin = User::find($id);
        $users = User::where('parent_id', $id)->get();

        return view('users.usersManagment')->with(['users' => $users, 'admin' => $admin]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = $request->userId;
        $user = User::find($id);
        $userChild = User::where('parent_id', $id);
        $devices = \App\Device::all();

        if ($user->hasRole('admin')) {
            $user->removeRole('admin');
            $user->revokePermissionTo('create-users');
        }

        if ($user->devices->count() > 0) {
            $user->devices()->detach($devices);
        }
        $userChild->get()->map(function ($user) {
            if ($user->devices->count() > 0) {
                return $user->devices()->detach();
            }
        });
        $user->delete();
        $userChild->delete();
        return redirect()->back();
    }

    public function createChild(Request $request, $id)
    {
        $deletedUser = User::onlyTrashed()
            ->where('email', $request->input('email'));

        if (count($deletedUser->get()) > 0) {
            $getId = $deletedUser->get()->toArray()[0]['id'];
            $deletedUser->restore();

            $user = User::find($getId);
        } else {
            $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users,email'
            ]);
            // Create user
            $user = new User;
            $user->email = $request->input('email');
        }

        $password = Str::random(8);
        $user->name = $request->input('name');
        $user->password = Hash::make($password);
        $user->parent_id = $id;
        $user->auth_key = '0';
        $user->save();

        \Mail::to($user)->send(new Welcome($user, $password));

        return redirect()->back();
    }
}
