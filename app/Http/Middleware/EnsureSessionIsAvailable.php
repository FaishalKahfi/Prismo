<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureSessionIsAvailable
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Start session if not already started
        if (!$request->hasSession() || !$request->session()->isStarted()) {
            $request->setLaravelSession(app('session.store'));
            $request->session()->start();
        }

        // Skip authentication check for public API routes (login, register, etc.)
        $publicRoutes = [
            'api/login',
            'api/register',
            'sanctum/csrf-cookie',
        ];

        // Check if current route is public
        $isPublicRoute = false;
        foreach ($publicRoutes as $publicRoute) {
            if ($request->is($publicRoute)) {
                $isPublicRoute = true;
                break;
            }
        }

        // For API requests, check if user is authenticated (except for public routes)
        if ($request->expectsJson() && !Auth::check() && !$isPublicRoute) {
            return response()->json([
                'message' => 'Unauthenticated.'
            ], 401);
        }

        return $next($request);
    }
}
