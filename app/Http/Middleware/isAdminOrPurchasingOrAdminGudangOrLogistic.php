<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class isAdminOrPurchasingOrAdminGudangOrLogistic
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
        return $next($request);
        if(auth()->user()->role=='ADMIN'||auth()->user()->role=='PURCHASING'||auth()->user()->role=='ADMIN_GUDANG'||auth()->user()->role=='LOGISTIC'){
            return $next($request);
        }
        return redirect('/home');
    }
}
