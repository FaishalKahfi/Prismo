<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$guards): Response
    {
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }

            // Determine redirect URL based on requested path
            $intendedUrl = $request->url();
            
            $redirectResponse = null;
            
            if (str_contains($intendedUrl, '/admin')) {
                $redirectResponse = redirect()->route('login')->with('error', 'Silakan login sebagai Admin untuk mengakses halaman ini.');
            } elseif (str_contains($intendedUrl, '/mitra')) {
                $redirectResponse = redirect()->route('login')->with('error', 'Silakan login sebagai Mitra untuk mengakses halaman ini.');
            } elseif (str_contains($intendedUrl, '/customer')) {
                $redirectResponse = redirect()->route('login')->with('error', 'Silakan login sebagai Customer untuk mengakses halaman ini.');
            } else {
                $redirectResponse = redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
            }

            // Add cache control headers to prevent back button access
            return $redirectResponse
                ->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate')
                ->header('Pragma', 'no-cache')
                ->header('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
        }

        return $next($request);
    }
}
