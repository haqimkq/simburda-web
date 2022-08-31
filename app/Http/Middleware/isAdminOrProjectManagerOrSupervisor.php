<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class isAdminOrProjectManagerOrSupervisor
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
        if(auth()->user()->role=='admin'||auth()->user()->role=='project manager'||auth()->user()->role=='supervisor'){
            return $next($request);
        }
        return redirect('/home');
    }
}
