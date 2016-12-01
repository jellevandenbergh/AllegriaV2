<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class LidAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Auth::check() == 1){
            if(auth::guest()){
                return redirect('login');
            }
            elseif(auth::user()->user_account_type >= 1) {
                return $next($request);
            }
            else{
                return redirect('/unauthorized');
            }
        }
        else{
            return redirect('login');
        }
    }
}
