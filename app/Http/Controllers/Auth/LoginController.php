<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\User;

class LoginController extends Controller
{

    public function __construct(){
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm(){
        
        return view('auth.login');
    }
    public function login(Request $request)
    {
        $this->validate($request, [
            'email'=>'required|email',
            'password'=>'required'
        ]);
            

        if(Auth::guard('web')->attempt(['email'=>$request->email,
         'password'=>$request->password], $request->remember)
        ){
            $user_id = auth()->user()->id;

            $user = User::find($user_id);

            if($user->hasRole('super-admin')||$user->hasRole('admin'))
            {
                return redirect('users');
            }else
            {
                Auth::logout();
                return view('auth.login');
            }
         }
         return view('auth.login');
        return redirect()->back()->withInput($request->only('email', 'remember'));
    }
    public function logout(Request $request) 
    {
        Auth::logout();
        return redirect('/login');
    }
}
