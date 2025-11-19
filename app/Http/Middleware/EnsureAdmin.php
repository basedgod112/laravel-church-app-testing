<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // If not authenticated, redirect to login (auth middleware should normally run first)
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        // Accept either boolean `is_admin` column or an isAdmin() method on the User model
        $isAdmin = false;
        if (method_exists($user, 'isAdmin')) {
            $isAdmin = (bool) $user->isAdmin();
        } else {
            $isAdmin = (bool) ($user->is_admin ?? false);
        }

        if (! $isAdmin) {
            abort(403);
        }

        return $next($request);
    }
}

