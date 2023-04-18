<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class isAdminOrProjectManagerOrSupervisorOrAdminGudang
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
        if(auth()->user()->role=='ADMIN'||auth()->user()->role=='PROJECT_MANAGER'||auth()->user()->role=='SUPERVISOR'||auth()->user()->role=='ADMIN_GUDANG'){
            return $next($request);
        }
        return redirect('/home');
    }
}
