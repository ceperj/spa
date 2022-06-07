<?php

namespace App\Http\Controllers;

class LoginController extends Controller
{
    public function index(){
        return view('login');
    }

    public function confirmWeb(){
        if ($this->validateLogin()){
            return redirect()->route('index');
        } else {
            return redirect()->route('login')->withErrors(['invalidCredentials'=>true]);
        }
    }

    private function validateLogin(){
        $username = request()->input("username", "");
        $password = request()->input("password", "");
        $valid = auth()->attempt(["username"=>$username, 'password'=>$password]);
        return $valid;
    }

    public function invalidateWeb(){
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('login');
    }
}
