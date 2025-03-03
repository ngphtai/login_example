<?php

namespace App\Http\Middleware;

use App\constains\UserTypeConstains;
use App\Models\Role;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
    
class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */

    protected $requiredRole ;

    public function __construct(string $requiredRole = UserTypeConstains::admin_type) {
        $this->requiredRole = $requiredRole;
    }

    public function __invoke(Request $request, Closure $next ){
        
        $user = $request-> user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please log in first');
        }

        $role = Role::find($user -> role_id);
        Log::info("role name: ".$role->role_name);
        if(!$role || $role->role_name !== UserTypeConstains::admin_type){
            Auth::logout();
            return redirect()->route('login')->with('error', 'You are not an admin');
        }
        return $next($request);
    }
}