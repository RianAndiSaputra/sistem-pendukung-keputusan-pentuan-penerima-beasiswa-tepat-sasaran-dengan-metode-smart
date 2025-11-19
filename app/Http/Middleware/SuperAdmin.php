<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuperAdmin
{
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated as admin and is super_admin
        if (Auth::guard('admin')->check() && Auth::guard('admin')->user()->role === 'super_admin') {
            return $next($request);
        }

        // If not super admin, redirect back with error
        return redirect()->route('dashboard')->with('error', 'Akses ditolak. Hanya Super Admin yang dapat mengakses halaman ini.');
    }
}