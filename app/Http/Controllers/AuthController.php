<?php

namespace App\Http\Controllers;
use App\Http\Requests\LoginRequest;
use App\Services\AuthService;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Exception;


class AuthController extends Controller
{   
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }
    
    public function homepage()
    {
        if (!Auth::check()) {
            return redirect()-> route('login_form');
        }
        return view('homepage');
    }
    
    public function gotoLogin(){
        return view('loginpage');
    }
    public function gotoRegister(){
        return view('registerpage');
    }
    
    public function registerAccount(LoginRequest $request)
    {

        try{
           $result = $this -> authService ->registerAccount($request->all());
           if($result){
                toastr()->success( 'Register success!');
                return redirect()->route('login_form');
           }else{
                toastr()->error('Have error when register!');
                return redirect()->refresh();
           }
          
        }catch(Exception $e){
            toastr()->error( 'Have error when register!');
            return redirect()->refresh();
        }
      
    }

    public function login(LoginRequest $request)
    {
        $result = $this -> authService -> Login($request);
        if($result){         
            toastr()-> success('success',"Login success!");
            return redirect()->route('home');
        }else{
            // return response()->json(['message' => 'Unauthorized'], 401);
            toastr()->error ( 'Email or password is incorrect!');
            return redirect()->route('login_form');
        }
       

    }


    public function logout(Request $request)
    {
    
        $result=  $this -> authService -> Logout($request);
        if($result){
           
            toastr()->success('Logout success!');
            return redirect()-> route('login');
        }
        else{
            toastr()->error('Logout error!');
            return redirect()->refresh();
        }
       
    }

}