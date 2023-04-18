<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class isAdminOrAdminGudang
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
        if(auth()->user()->role=='ADMIN'||auth()->user()->role=='ADMIN_GUDANG'){
            // abort(403);
            return $next($request);
        }
        return redirect('/home');
    }
}
