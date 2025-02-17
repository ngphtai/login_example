<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{   
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
    
    public function register(Request $request)
    {

        try{
            $request->validate([
                'name' => 'required',
                'email' => 'required',
                'password' => 'required',
            ]);
    
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            Log::info($user);
            // $token = $user->createToken('auth_token')->plainTextToken;
            // Log::info('User created: ' .  $token);
            // return response()->json(['token' => $token, 'user' => $user]);
            toastr()->success( 'Register success!');
            return redirect()->route('login_form');
        }catch(Exception $e){
            toastr()->error( 'Have error when register!');
            return redirect()->refresh();
        }
      
    }


    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            // $user = User::find(Auth::id());
            // $token = $user->createToken('auth_token')->plainTextToken;
            // return response()->json(['token' => $token, 'user' => $user]);
            $request->session()->regenerate();
            toastr()-> success('success',"Login success!");
            return redirect()->route('home');
      
        }

       
        // return response()->json(['message' => 'Unauthorized'], 401);
        toastr()->error ( 'Email or password is incorrect!');
        return redirect()->route('login_form');
    }


    public function logout(Request $request)
    {
        // $request->user()->tokens()->delete();
        // return response()->json(['message' => 'Logged out']);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        toastr()->success('Logout success!');
        return redirect()-> route('login');
    }


    public function userInfo(Request $request)
    {
        return response()->json($request->user());
    }
}