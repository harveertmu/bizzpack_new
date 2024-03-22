<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::user()->roles->pluck('name')[0] == 'super-admin') {
            return redirect('/dashboard');
        }else if(Auth::user()->roles->pluck('name')[0] == 'vendor'){

            return redirect('/vendor-dashboard');

        }else if(Auth::user()->roles->pluck('name')[0] == 'customer'){

            return redirect('/customer-dashboard');

        }else{
            return redirect('/');
        }
    }
}
