<?php

namespace App\Http\Middleware;

use App\Models\AnalyticsPageView;
use App\Models\AnalyticsVisit;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

/**
 * Mesure d'audience first-party : une visite par session (30 min d'inactivité),
 * une ligne par page HTML affichée. Les adresses IP sont hachées, jamais stockées.
 */
class TrackVisit
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        try {
            $tracking = $this->track($request, $response);

            if ($tracking !== null) {
                $this->injectAnalytics($response, $tracking);
            }
        } catch (\Throwable $e) {
            // La mesure d'audience ne doit jamais casser une page du site.
            Log::debug('Analytics: échec de collecte', ['error' => $e->getMessage()]);
        }

        return $response;
    }

    private function track(Request $request, Response $response): ?array
    {
        if (! config('analytics.enabled')) {
            return null;
        }

        if (! $request->isMethod('GET')
            || $request->is('admin', 'admin/*', 'api/*', '_track', 'up', 'storage/*', 'build/*')
            || $response->getStatusCode() !== 200
            || ! str_contains((string) $response->headers->get('Content-Type'), 'text/html')) {
            return null;
        }

        $userAgent = (string) $request->userAgent();

        if ($this->isBot($request, $userAgent)) {
            return null;
        }

        $uuid = $request->cookie('cak_vid');

        if (! is_string($uuid) || ! Str::isUuid($uuid)) {
            $uuid = (string) Str::uuid();
            Cookie::queue('cak_vid', $uuid, 60 * 24 * 365, '/', null, $request->isSecure(), false, false, 'lax');
        }

        $now = now();
        $sessionId = $request->hasSession() ? $request->session()->getId() : null;
        $country = $this->country($request);
        $city = $this->city($request);
        $device = $this->device($userAgent);
        $browser = $this->browser($userAgent);
        $os = $this->os($userAgent);
        $referrer = $this->referrerHost($request);

        $visit = AnalyticsVisit::query()
            ->when($sessionId, fn ($q) => $q->where('session_id', $sessionId), fn ($q) => $q->where('uuid', $uuid))
            ->where('last_seen_at', '>=', $now->copy()->subMinutes(30))
            ->latest('last_seen_at')
            ->first();

        if (! $visit) {
            $visit = AnalyticsVisit::create([
                'uuid' => $uuid,
                'session_id' => $sessionId,
                'ip_hash' => hash('sha256', ($request->ip() ?? '').'|'.config('app.key')),
                'country_code' => $country,
                'city' => $city,
                'device' => $device,
                'browser' => $browser,
                'os' => $os,
                'referrer_host' => $referrer,
                'landing_path' => $request->path(),
                'user_agent' => Str::limit($userAgent, 500, ''),
                'started_at' => $now,
                'last_seen_at' => $now,
            ]);
        } else {
            $visit->update(array_filter([
                'last_seen_at' => $now,
                'country_code' => $visit->country_code ?: $country,
                'city' => $visit->city ?: $city,
                'device' => $visit->device ?: $device,
                'browser' => $visit->browser ?: $browser,
                'os' => $visit->os ?: $os,
                'referrer_host' => $visit->referrer_host ?: $referrer,
            ], fn ($value) => $value !== null && $value !== ''));
        }

        $pageView = AnalyticsPageView::create([
            'visit_id' => $visit->id,
            'path' => $request->path(),
            'route_name' => ($route = $request->route()) instanceof Route ? $route->getName() : null,
            'referrer_host' => $referrer,
            'viewed_at' => $now,
        ]);

        return ['visit' => $uuid, 'pageView' => $pageView->id];
    }

    private function injectAnalytics(Response $response, array $tracking): void
    {
        if (! str_contains((string) $response->headers->get('Content-Type'), 'text/html')) {
            return;
        }

        $content = $response->getContent();

        if (! is_string($content)) {
            return;
        }

        $position = strrpos($content, '</body>');

        if ($position === false) {
            return;
        }

        $config = json_encode([
            'visit' => $tracking['visit'],
            'pageView' => $tracking['pageView'],
            'csrf' => csrf_token(),
        ], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);

        $scripts = '<script>window.__CAK_ANALYTICS = '.$config.';</script>'
            .'<script src="'.e(asset('js/analytics.js')).'?v=1"></script>';

        $response->setContent(substr_replace($content, $scripts.'</body>', $position, strlen('</body>')));
        $response->headers->remove('Content-Length');
    }

    private function isBot(Request $request, string $userAgent): bool
    {
        $purpose = strtolower((string) $request->headers->get('Sec-Purpose').' '.(string) $request->headers->get('X-Purpose'));

        if (str_contains($purpose, 'prefetch') || str_contains($purpose, 'prerender')) {
            return true;
        }

        if (strtolower((string) $request->headers->get('X-Moz')) === 'prefetch') {
            return true;
        }

        return $userAgent === ''
            || preg_match('/bot|crawl|spider|slurp|preview|headless|curl|wget|python|monitor|uptime|pingdom|pagespeed|lighthouse/i', $userAgent) === 1;
    }

    private function country(Request $request): ?string
    {
        $country = strtoupper((string) $request->headers->get('X-Vercel-IP-Country'));

        return strlen($country) === 2 ? $country : null;
    }

    private function city(Request $request): ?string
    {
        $city = urldecode(trim((string) $request->headers->get('X-Vercel-IP-City')));

        return $city !== '' ? Str::limit($city, 100, '') : null;
    }

    private function referrerHost(Request $request): ?string
    {
        $host = parse_url((string) $request->headers->get('referer'), PHP_URL_HOST);

        if (! is_string($host) || $host === '' || $host === $request->getHost()) {
            return null;
        }

        return Str::limit($host, 120, '');
    }

    private function device(string $userAgent): string
    {
        if (preg_match('/tablet|ipad/i', $userAgent)) {
            return 'tablet';
        }

        return preg_match('/mobile|android|iphone|ipod/i', $userAgent) ? 'mobile' : 'desktop';
    }

    private function browser(string $userAgent): string
    {
        return match (true) {
            str_contains($userAgent, 'Edg/') => 'Edge',
            str_contains($userAgent, 'OPR/') => 'Opera',
            str_contains($userAgent, 'Chrome/') => 'Chrome',
            str_contains($userAgent, 'Firefox/') => 'Firefox',
            str_contains($userAgent, 'Safari/') => 'Safari',
            default => 'Autre',
        };
    }

    private function os(string $userAgent): string
    {
        return match (true) {
            str_contains($userAgent, 'Windows') => 'Windows',
            str_contains($userAgent, 'Android') => 'Android',
            str_contains($userAgent, 'iPhone'), str_contains($userAgent, 'iPad') => 'iOS',
            str_contains($userAgent, 'Mac OS X') => 'macOS',
            str_contains($userAgent, 'Linux') => 'Linux',
            default => 'Autre',
        };
    }
}
