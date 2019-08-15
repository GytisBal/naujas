<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

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
         'password'=>$request->password], $request->remember)){
             
            return redirect('users');
         }

        return redirect()->back()->withInput($request->only('email', 'remember'));
    }
    public function logout(Request $request) {
        Auth::logout();
        return redirect('/login');
      }
}
