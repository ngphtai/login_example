<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApiLoginRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\ApiAuthService;
use Illuminate\Http\Request;

class ApiAuthController extends Controller
{

    protected $apiAuthService; 
    public function __construct(ApiAuthService $apiAuthService)
    {
        $this -> apiAuthService = $apiAuthService;
    }
    
    public function login(ApiLoginRequest $request){
        $result = $this -> apiAuthService -> login($request);
        return $result['status'] 
        ? response()->json(['message' => 'Login Success','data' => $result['message'] ], 200)
        : response()->json(["Error: "=>$result['message'],500]);
    }

    public function logout(Request $request){
        
        $result = $this -> apiAuthService -> logout( $request );
        return $result['status'] 
            ? response()->json(["Logout Success", 200]) 
            : response()->json(["Error: " => $result['message'], 500]);
    }

    
    public function registerAccount(RegisterRequest $request){
        $result = $this -> apiAuthService -> registerAccount($request);
        return $result['status'] 
            ? response()->json(["Register Success", 200]) 
            : response()->json(["Register Error "=> $result['message'],500]);
    }
}