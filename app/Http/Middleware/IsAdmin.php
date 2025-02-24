<?php

namespace App\Http\Middleware;

use App\constains\UserTypeConstains;
use App\Models\Role;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
       if(!Auth::check()){
            return redirect()->route("login");
       }
       $user = User::find(Auth::id());
       Log::info("User cần kiểm tra là : ".$user);
       $role = Role::find($user -> role_id);
       if(!$role || $role->role_name !== UserTypeConstains::admin_type){
            Auth::logout();
            return redirect()->route('login')->with('error', 'You are not an admin');
       }
       return $next($request);

    }
}