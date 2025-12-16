<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $roles): Response
    {
        if (Auth::check()) {
            $userRole = Auth::user()->role;
            $allowedRoles = explode(',', $roles); // Pisahkan peran dengan koma

            if (in_array($userRole, $allowedRoles)) {
                return $next($request);
            }
        }

        abort(403, 'Unauthorized action.');
    }
}
