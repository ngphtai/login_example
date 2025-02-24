<?php
    namespace App\Services;

use App\Http\Requests\ApiLoginRequest;
use App\Http\Requests\LoginRequest;
use App\Models\Role;
use Illuminate\Http\Request;
    use App\Models\User;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Facades\Session;
    use Illuminate\Support\Facades\Log;
    use Exception;

use Illuminate\Support\Facades\Auth;


    class ApiAuthService{
        public function registerAccount( $data)
    {
        try{
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);
            $token = $user->createToken('auth_token')->plainTextToken;
            Log::info('User created: ' .  $token);
            return response()->json(['token' => $token, 'user' => $user]);
            
        }catch(Exception $e){
            Log::error("Error registering user: " . $e->getMessage());
            return response("Have error when register!: $e", 500);
        }
      
    }


    public function login(ApiLoginRequest $loginRequest)
    {
        if (Auth::attempt($loginRequest->only('email', 'password'))) {
            $user = User::where('email', $loginRequest->email)->first();
        
            // Nếu bạn muốn xoá tất cả các token cũ (ví dụ, để giới hạn đăng nhập 1 phiên duy nhất)
            $user->tokens()->delete();

            $token = $user->createToken('auth_token')->plainTextToken;
            $message = [
                'token' => $token,
                'user' => $user,
            ];
            return ['status' => true, 'message' => $message];

        }else{
            return ['status' => false, 'message' => 'Password or email is incorrect!!'];
        }
    }


    public function logout(Request $request)
    {       
        $request->user()->tokens()->delete();
        return ['status' => true, 'message' => "Logout success"];

    }

}