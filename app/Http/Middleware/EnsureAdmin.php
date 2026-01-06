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
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        if (! ($user->is_admin ?? false) && !in_array($user->role, ['admin', 'moderator', 'priest'])) {
            abort(403);
        }

        return $next($request);
    }
}
