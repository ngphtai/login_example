<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
class EnsureSingleSession
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
        if(Auth::check()){
            $user = Auth::user();
            $currentSessionId = session()->getId();

            if($user->session_id != $currentSessionId){
                Auth::logout();
                Session::invalidate();
                Session::regenerateToken();    

                return redirect()->route('login_form')->with('error', 'Bạn đã đăng nhập ở nơi khác.');
            }
        }
        return $next($request);
    }
}