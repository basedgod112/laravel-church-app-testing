<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EnsureAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        // If not authenticated, redirect to login
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        // Admin check
        $isAdmin = (bool) DB::table('users')
            ->where('id', Auth::id())
            ->value('is_admin');

        if (! $isAdmin) {
            abort(403);
        }

        return $next($request);
    }
}
