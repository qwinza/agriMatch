<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PetaniMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role === 'petani') {
            return $next($request);
        }

        abort(403, 'Akses ditolak. Halaman ini hanya untuk Petani.');
    }
}
