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
        if(auth()->user()->role=='admin'||auth()->user()->role=='purchasing'||auth()->user()->role=='admin gudang'||auth()->user()->role=='logistic'){
            return $next($request);
        }
        return redirect('/home');
    }
}