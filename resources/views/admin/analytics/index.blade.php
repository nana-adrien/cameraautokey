@extends('layouts.admin')
@section('title', 'Statistiques visiteurs')

@php
    $formatDuration = function ($ms) {
        $ms = (int) $ms;
        if ($ms <= 0) return '—';
        $seconds = (int) round($ms / 1000);
        if ($seconds < 60) return $seconds.' s';
        $minutes = intdiv($seconds, 60);
        $rest = $seconds % 60;
        return $minutes.' min'.($rest ? ' '.$rest.' s' : '');
    };
@endphp

@section('content')
<div class="page-head">
    <div>
        <h1>Statistiques visiteurs</h1>
        <p class="muted" style="margin:4px 0 0">Depuis le {{ $from->format('d/m/Y') }} · mesure first-party, sans cookie publicitaire.</p>
    </div>
    <div class="range">
        @foreach ([7 => '7 jours', 30 => '30 jours', 90 => '90 jours'] as $value => $label)
            <a href="{{ route('admin.analytics', ['days' => $value]) }}" @class(['btn', 'btn-sm', $days === $value ? 'btn-dark' : 'btn-light'])>{{ $label }}</a>
        @endforeach
    </div>
</div>

<div class="stats">
    <div class="card stat"><span class="ic" style="background:#e6eeff">👥</span><div><div class="val">{{ number_format($visitors, 0, ',', ' ') }}</div><div class="lbl">Visiteurs uniques</div></div></div>
    <div class="card stat"><span class="ic" style="background:#fdecec">👁️</span><div><div class="val">{{ number_format($pageViewsCount, 0, ',', ' ') }}</div><div class="lbl">Pages vues</div></div></div>
    <div class="card stat"><span class="ic" style="background:#e8f7ee">⏱️</span><div><div class="val">{{ $formatDuration($averageDuration) }}</div><div class="lbl">Temps moyen / page</div></div></div>
    <div class="card stat"><span class="ic" style="background:#fff4e0">🎯</span><div><div class="val">{{ $conversionRate }} %</div><div class="lbl">Taux de conversion</div></div></div>
</div>

<div class="grid-2">
    <div class="card">
        <div class="card-title">Visites par jour <span class="muted" style="font-weight:600;font-size:.8rem">{{ $visitors }} visiteur(s)</span></div>
        @if ($visitors > 0)
            @php $max = max(1, $chart->max('value')); @endphp
            <div class="chart {{ $days > 30 ? 'compact' : '' }}">
                @foreach ($chart as $point)
                    <div class="col">
                        <div class="bar" style="height: {{ max(2, $point['value'] / $max * 85) }}%">@if ($point['value'])<span>{{ $point['value'] }}</span>@endif</div>
                        @if ($days === 7 || $loop->index % ($days > 30 ? 7 : 3) === 0)
                            <div class="lbl">{{ $point['label'] }}</div>
                        @else
                            <div class="lbl">&nbsp;</div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <p class="muted" style="margin:0">Aucune visite enregistrée sur cette période.</p>
        @endif
    </div>

    <div class="card">
        <div class="card-title">Entonnoir du formulaire</div>
        @php $funnelMax = max(1, collect($funnel)->max('value')); @endphp
        <div class="funnel">
            @foreach ($funnel as $step)
                <div class="funnel-step">
                    <div class="funnel-head"><span>{{ $step['label'] }}</span><b>{{ $step['value'] }}</b></div>
                    <div class="funnel-track"><div class="funnel-fill" style="width: {{ max(2, $step['value'] / $funnelMax * 100) }}%"></div></div>
                </div>
            @endforeach
        </div>
        <div class="funnel-foot">
            <span>{{ $started }} commencé(s)</span>
            <span>{{ $submitted }} envoyé(s)</span>
            <span>{{ $abandoned }} abandon(s)</span>
        </div>
    </div>
</div>

<div class="grid-eq">
    <div class="card">
        <div class="card-title">Pages les plus vues</div>
        @if ($topPages->isNotEmpty())
            @php $maxViews = max(1, $topPages->max('views')); @endphp
            <div class="bar-list">
                @foreach ($topPages as $page)
                    <div class="bar-row">
                        <div class="bar-top"><span class="bar-label">{{ $page->path === '/' ? 'Accueil' : $page->path }}</span><b>{{ $page->views }}</b></div>
                        <div class="bar-track"><div class="bar-fill" style="width: {{ max(2, $page->views / $maxViews * 100) }}%"></div></div>
                        <div class="bar-sub">{{ $formatDuration($page->avg_duration) }} en moyenne</div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="muted" style="margin:0">Aucune page vue pour l’instant.</p>
        @endif
    </div>

    <div class="card">
        <div class="card-title">Pays et appareils</div>
        @if ($countries->isNotEmpty())
            <div class="bar-list">
                @foreach ($countries as $country)
                    <div class="bar-row">
                        <div class="bar-top"><span class="bar-label">{{ $country['name'] }} <span class="muted">({{ $country['code'] }})</span></span><b>{{ $country['total'] }}</b></div>
                        <div class="bar-track"><div class="bar-fill blue" style="width: {{ max(2, $country['total'] / max(1, $countries->max('total')) * 100) }}%"></div></div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="muted" style="margin:0 0 14px">Les pays apparaissent quand le site est déployé derrière Vercel (en-têtes géographiques).</p>
        @endif

        <div class="device-list">
            @foreach ($devices as $device)
                <span class="pill pill-gray">{{ ucfirst($device->device ?: 'inconnu') }} · {{ $device->total }}</span>
            @endforeach
        </div>
    </div>
</div>

<div class="card" style="margin-top:18px">
    <div class="card-title">Sessions récentes</div>
    <div class="table-wrap" style="border:0">
        <table>
            <thead><tr><th>Date</th><th>Visiteur</th><th>Pays</th><th>Pages</th><th>Temps</th><th>Formulaire</th><th>Statut</th></tr></thead>
            <tbody>
            @forelse ($sessions as $visit)
                <tr>
                    <td class="muted">{{ $visit->started_at->format('d/m/Y H:i') }}</td>
                    <td><code>{{ substr($visit->uuid, 0, 8) }}</code></td>
                    <td>{{ $visit->country_code ?: '—' }}</td>
                    <td>{{ $visit->page_views_count }}</td>
                    <td>{{ $formatDuration($visit->page_views_sum_duration_ms) }}</td>
                    <td>{{ $visit->last_step ? 'Étape '.$visit->last_step : '—' }}</td>
                    <td>
                        @if ($visit->converted)
                            <span class="pill pill-green">Envoyé</span>
                        @elseif ($visit->form_start_count > 0 && $visit->last_seen_at->lt(now()->subMinutes(30)))
                            <span class="pill pill-red">Abandon</span>
                        @elseif ($visit->form_start_count > 0)
                            <span class="pill pill-amber">En cours</span>
                        @else
                            <span class="pill pill-gray">Navigation</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="muted" style="text-align:center;padding:30px">Aucune session enregistrée sur cette période.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $sessions->links() }}
</div>
@endsection
