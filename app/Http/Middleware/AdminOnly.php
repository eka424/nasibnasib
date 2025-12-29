<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminOnly
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if ((auth()->user()->role ?? null) !== 'admin') {
            abort(403, 'Hanya admin yang boleh akses halaman ini.');
        }

        return $next($request);
    }
}
