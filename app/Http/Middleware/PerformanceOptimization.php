<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PerformanceOptimization
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Enable GZIP compression
        if (!$response->headers->has('Content-Encoding')) {
            if (function_exists('gzencode') &&
                strpos($request->header('Accept-Encoding', ''), 'gzip') !== false &&
                $response->getStatusCode() === 200) {

                $content = $response->getContent();
                if ($content && strlen($content) > 1024) { // Only compress if > 1KB
                    $compressed = gzencode($content, 6);
                    if ($compressed !== false) {
                        $response->setContent($compressed);
                        $response->headers->set('Content-Encoding', 'gzip');
                        $response->headers->set('Vary', 'Accept-Encoding');
                    }
                }
            }
        }

        // Cache control headers for static assets
        if ($request->is('css/*') || $request->is('js/*') || $request->is('images/*') || $request->is('storage/*')) {
            $response->headers->set('Cache-Control', 'public, max-age=31536000, immutable');
        } else {
            $response->headers->set('Cache-Control', 'no-cache, must-revalidate');
        }

        return $response;
    }
}
