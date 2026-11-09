<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Bloque l'indexation par les moteurs de recherche quand le site n'est pas en
 * production (config seo.indexing), et toujours pour l'administration.
 */
class SeoRobotsHeader
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! config('seo.indexing') || $request->is('admin', 'admin/*')) {
            $response->headers->set('X-Robots-Tag', 'noindex, nofollow');
        }

        return $response;
    }
}
