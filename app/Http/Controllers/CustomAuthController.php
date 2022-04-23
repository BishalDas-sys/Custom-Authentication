<?php

namespace App\Http\Controllers;

use App\Http\Controllers\UserController;
use App\Http\Controllers\EmployeeController;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Employee;

use Hash;
use Session;


class CustomAuthController extends Controller
{
    public function login(){
        if(Session()->has('loginId')){
            return redirect ('employees');
        }
        return view("auth.Login");
    }

    public function registration(){
        if(Session()->has('loginId')){
            return redirect ('employees');
        }
        return view("auth.registration");
    }

    public function registerUser(Request $request){
        $request->validate([
            'name'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:8|max:10'
        ]);
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $res = $user->save();

        if($res){
            return back()->with('success', 'You have registered successfully');
        }else{
            return back()->with('fail', 'Something is Wrong');
        }
        
    }
    public function loginUser(Request $request){
        $request->validate([
            'email'=>'required|email|exists:users',
            'password'=>'required|min:8|max:10'
        ]);
        $user = User::where('email', '=', $request->email)->first();
        if($user){
            if(Hash::check($request->password, $user->password)){
                $request->session()->put('loginId', $user->id);
                return redirect('employees');

            }
            else{
                return back()->with('fail', 'This Password is Wrong');
            }
        }
        else{
            return back()->with('fail', 'This Email is not registered');
        }
    }

    public function logout(){
        if(Session::has('loginId')){
            Session::pull('loginId');
            return redirect('login');
            }
    }
    
    

    
}
