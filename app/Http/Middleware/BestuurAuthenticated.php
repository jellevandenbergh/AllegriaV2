<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class BestuurAuthenticated
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
        if(auth::guest()){
            return redirect('http://localhost/AllegriaV2/public/login');
        }
        elseif(auth::user()->user_account_type >= 2) {
            return $next($request);
        }
        else{
            return redirect('/unauthorized');
        }
    }
}
