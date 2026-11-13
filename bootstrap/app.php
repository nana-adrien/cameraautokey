<?php

use App\Http\Middleware\EnsureFeatureIsUnlocked;
use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Middleware\SeoRobotsHeader;
use App\Http\Middleware\TrackVisit;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => EnsureUserIsAdmin::class,
            'feature' => EnsureFeatureIsUnlocked::class,
        ]);
        // Derrière le proxy HTTPS de Vercel : URLs en https et liens signés valides.
        $middleware->trustProxies(at: '*');
        // Référencement : noindex hors production (voir config/seo.php).
        $middleware->web(append: [
            SeoRobotsHeader::class,
            TrackVisit::class,
        ]);
        $middleware->redirectGuestsTo(fn () => route('admin.login'));
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
