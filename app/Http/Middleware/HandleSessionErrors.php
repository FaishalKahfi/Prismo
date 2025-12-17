<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class HandleSessionErrors
{
    /**
     * Handle an incoming request.
     * Catches session/authentication errors and redirects appropriately.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // Check if session is valid
            if ($request->hasSession()) {
                $request->session()->get('_token');
            }

            // Check if user is authenticated but session is corrupted
            if (Auth::check()) {
                $user = Auth::user();
                if (!$user || !$user->id) {
                    throw new \Exception('Session corrupted');
                }
            }

            return $next($request);
        } catch (\Exception $e) {
            // Log the error
            Log::warning('Session error detected', [
                'error' => $e->getMessage(),
                'url' => $request->fullUrl(),
                'ip' => $request->ip()
            ]);

            // Clear the corrupted session
            if ($request->hasSession()) {
                $request->session()->flush();
                $request->session()->regenerate();
            }

            // Logout the user
            Auth::logout();

            // Redirect to login with error message
            return redirect('/login')
                ->withErrors(['session' => 'Session telah berakhir. Silakan login kembali.'])
                ->with('error', 'Session error. Silakan login kembali.');
        }
    }
}
