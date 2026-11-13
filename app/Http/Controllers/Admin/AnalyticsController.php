<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnalyticsEvent;
use App\Models\AnalyticsPageView;
use App\Models\AnalyticsVisit;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    private const COUNTRIES = [
        'CM' => 'Cameroun', 'FR' => 'France', 'US' => 'États-Unis', 'GB' => 'Royaume-Uni',
        'CA' => 'Canada', 'BE' => 'Belgique', 'CH' => 'Suisse', 'DE' => 'Allemagne',
        'ES' => 'Espagne', 'IT' => 'Italie', 'NG' => 'Nigeria', 'GA' => 'Gabon',
        'TD' => 'Tchad', 'CF' => 'Centrafrique', 'GQ' => 'Guinée équatoriale', 'CG' => 'Congo',
        'CD' => 'RD Congo', 'CI' => 'Côte d’Ivoire', 'SN' => 'Sénégal', 'ML' => 'Mali',
        'BF' => 'Burkina Faso', 'BJ' => 'Bénin', 'TG' => 'Togo', 'GH' => 'Ghana',
        'ZA' => 'Afrique du Sud', 'MA' => 'Maroc', 'DZ' => 'Algérie', 'TN' => 'Tunisie',
        'EG' => 'Égypte', 'TR' => 'Turquie', 'CN' => 'Chine', 'IN' => 'Inde',
        'BR' => 'Brésil', 'AU' => 'Australie', 'NL' => 'Pays-Bas', 'PT' => 'Portugal',
        'LU' => 'Luxembourg', 'IE' => 'Irlande', 'SE' => 'Suède', 'NO' => 'Norvège',
        'DK' => 'Danemark', 'FI' => 'Finlande', 'PL' => 'Pologne', 'RO' => 'Roumanie',
        'RU' => 'Russie', 'UA' => 'Ukraine', 'JP' => 'Japon', 'KR' => 'Corée du Sud',
        'SG' => 'Singapour', 'AE' => 'Émirats arabes unis', 'SA' => 'Arabie saoudite',
        'QA' => 'Qatar', 'IL' => 'Israël', 'MX' => 'Mexique', 'AR' => 'Argentine',
        'CL' => 'Chili', 'CO' => 'Colombie', 'PE' => 'Pérou',
    ];

    public function __invoke(Request $request): View
    {
        $days = $request->integer('days', 30);

        if (! in_array($days, [7, 30, 90], true)) {
            $days = 30;
        }

        $from = now()->subDays($days - 1)->startOfDay();
        $visits = AnalyticsVisit::where('started_at', '>=', $from);
        $pageViews = AnalyticsPageView::where('viewed_at', '>=', $from);
        $events = AnalyticsEvent::where('occurred_at', '>=', $from);

        $visitors = (clone $visits)->distinct()->count('uuid');
        $pageViewsCount = (clone $pageViews)->count();
        $averageDuration = (clone $pageViews)->whereNotNull('duration_ms')->avg('duration_ms');

        $started = (clone $events)->where('name', 'form_start')->distinct()->count('visit_id');
        $submitted = (clone $events)->where('name', 'form_submit')->distinct()->count('visit_id');
        $startedBeforeGrace = (clone $events)->where('name', 'form_start')
            ->where('occurred_at', '<=', now()->subMinutes(30))
            ->distinct()
            ->count('visit_id');
        $abandoned = max(0, $startedBeforeGrace - $submitted);
        $conversionRate = $started > 0 ? (int) round($submitted / $started * 100) : 0;

        $perDay = (clone $visits)
            ->selectRaw('DATE(started_at) as day, COUNT(*) as total')
            ->groupBy('day')
            ->pluck('total', 'day');

        $chart = collect(range(0, $days - 1))->map(function (int $i) use ($from, $perDay, $days) {
            $day = $from->copy()->addDays($i);

            return [
                'label' => $day->translatedFormat($days > 30 ? 'd/m' : 'd M'),
                'value' => (int) ($perDay[$day->toDateString()] ?? 0),
            ];
        });

        $topPages = (clone $pageViews)
            ->selectRaw('path, COUNT(*) as views, AVG(duration_ms) as avg_duration')
            ->groupBy('path')
            ->orderByDesc('views')
            ->limit(10)
            ->get();

        $countries = (clone $visits)
            ->whereNotNull('country_code')
            ->selectRaw('country_code, COUNT(*) as total')
            ->groupBy('country_code')
            ->orderByDesc('total')
            ->limit(10)
            ->get()
            ->map(fn ($row) => [
                'code' => $row->country_code,
                'name' => $this->countryName($row->country_code),
                'total' => (int) $row->total,
            ]);

        $devices = (clone $visits)
            ->selectRaw('device, COUNT(*) as total')
            ->groupBy('device')
            ->orderByDesc('total')
            ->get();

        $funnel = [
            ['label' => 'Formulaire commencé', 'value' => $started],
            ['label' => 'Étape 2 — localisation', 'value' => (clone $events)->where('name', 'form_step')->where('step', '>=', 2)->distinct()->count('visit_id')],
            ['label' => 'Étape 3 — vérification', 'value' => (clone $events)->where('name', 'form_step')->where('step', '>=', 3)->distinct()->count('visit_id')],
            ['label' => 'Demande envoyée', 'value' => $submitted],
        ];

        $sessions = (clone $visits)
            ->withCount('pageViews')
            ->withSum('pageViews', 'duration_ms')
            ->withCount(['events as form_start_count' => fn ($q) => $q->where('name', 'form_start')])
            ->latest('started_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.analytics.index', compact(
            'days', 'from', 'visitors', 'pageViewsCount', 'averageDuration',
            'started', 'submitted', 'abandoned', 'conversionRate',
            'chart', 'topPages', 'countries', 'devices', 'funnel', 'sessions'
        ));
    }

    private function countryName(?string $code): string
    {
        if (! $code) {
            return 'Inconnu';
        }

        return self::COUNTRIES[$code] ?? $code;
    }
}
