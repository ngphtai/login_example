<?php
    namespace App\Services;
    
    use App\Http\Requests\LoginRequest;
    use Illuminate\Http\Request;
    use App\Models\User;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Facades\Session;
    use Illuminate\Support\Facades\Log;
    use Exception;

use Illuminate\Support\Facades\Auth;


    class AuthService{
        public function registerAccount(array $data)
    {

        try{
    
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);
            Log::info("User created: ", ['user' => $user]);
            return ['status' => true, 'message' => 'Register success!'];
        }catch(Exception $e){
            Log::error("Error registering user: " . $e->getMessage());
            return ['status' => false, 'message' => 'Have error when register!'];
        }
      
    }


    public function login(LoginRequest $loginRequest)
    {
        if (Auth::attempt($loginRequest->only('email','password'))) {
            $loginRequest->session()->regenerate();
            
            $user = Auth::user();
            $currentSessionId = Session::getId(); 
            if ($user->session_id && $user->session_id !== $currentSessionId) {
                Session::getHandler()->destroy($user->session_id);
            }
         
            $user1 = User::find($user->id);
            $user1 -> session_id = $currentSessionId;
            $user1 -> save();
            
            return ['status' => true, 'message' => 'Login success!'];
        }
        return ['status' => false, 'message' => 'Email or password is incorrect!'];
    }


    public function logout(Request $request)
    {       
        $user = Auth::user();
        $user1 = User::find($user->id);
        $user1 -> session_id = null;
        $user1 -> save();
        Log::info("check session_id: ", ['user' => $user->session_id]);
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
       
        
        return ['status' => true, 'message' => 'Logout !'];

    }

}