<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!session()->has('user')) {
            return redirect('/login');
        }

        if (!in_array(session('user')->role, $roles)) {
            abort(403);
        }

        return $next($request);
    }
}
