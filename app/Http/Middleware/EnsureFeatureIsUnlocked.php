<?php

namespace App\Http\Middleware;

use App\Services\DemoFeatures;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureFeatureIsUnlocked
{
    /**
     * Affiche la page « En cours de développement » si la fonctionnalité est verrouillée.
     */
    public function handle(Request $request, Closure $next, string $feature): Response
    {
        if (! DemoFeatures::isLocked($feature)) {
            return $next($request);
        }

        $view = $request->is('admin/*') ? 'admin.feature-locked' : 'pages.feature-locked';

        return response()->view($view, ['featureLabel' => DemoFeatures::label($feature)]);
    }
}
