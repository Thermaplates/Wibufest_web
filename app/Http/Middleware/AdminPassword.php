<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminPassword
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->session()->get('admin_authenticated')) {
            return redirect()->route('admin.login')->with('error', 'Silakan login admin.');
        }

        return $next($request);
    }
}


