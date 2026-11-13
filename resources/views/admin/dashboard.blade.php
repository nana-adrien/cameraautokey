@extends('layouts.admin')
@section('title', 'Tableau de bord')

@section('content')
<div class="page-head">
    <div><h1>Bonjour {{ auth()->user()->name }} 👋</h1><p class="muted" style="margin:4px 0 0">Voici l’activité de CAMERAUTOKEY.</p></div>
    <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="btn btn-primary">Traiter les demandes en attente</a>
</div>

<div class="stats">
    <div class="card stat"><span class="ic" style="background:#fdecec">🧾</span><div><div class="val">{{ $stats['orders_total'] }}</div><div class="lbl">Demandes au total</div></div></div>
    <div class="card stat"><span class="ic" style="background:#fff4e0">⏳</span><div><div class="val">{{ $stats['orders_pending'] }}</div><div class="lbl">En attente</div></div></div>
    <div class="card stat"><span class="ic" style="background:#e8f7ee">💶</span><div><div class="val">{{ \App\Support\Money::fcfa($stats['revenue']) }}</div><div class="lbl">CA réalisé · {{ \App\Support\Money::fcfa($stats['revenue_month']) }} ce mois</div></div></div>
    <div class="card stat"><span class="ic" style="background:#e6eeff">👷</span><div><div class="val">{{ $stats['technicians'] }}</div><div class="lbl">Techniciens actifs · {{ $stats['unread_messages'] }} message(s) non lu(s)</div></div></div>
</div>

<div class="card analytics-cta">
    <div>
        <div class="card-title" style="margin:0">Statistiques visiteurs</div>
        <p class="muted" style="margin:4px 0 0">Pays, pages vues, temps passé et abandons du formulaire de demande.</p>
    </div>
    <a href="{{ route('admin.analytics') }}" class="btn btn-dark">Voir les statistiques</a>
</div>

<div class="card telegram-card">
    <div class="tg-head">
        <span class="tg-ic">✈️</span>
        <div>
            <div class="card-title" style="margin:0">Notifications Telegram</div>
            <p class="muted" style="margin:4px 0 0">Les demandes, messages de contact et candidatures sont envoyés à ce chat, avec leur PDF.</p>
        </div>
    </div>
    <div class="tg-dest">
        @if ($telegram['locked'])
            <span class="pill pill-amber">Version démo : envois suspendus</span>
        @elseif ($telegram['configured'])
            <span class="pill pill-green">Actives</span>
        @else
            <span class="pill pill-red">Non configurées</span>
        @endif
        @if ($telegram['chat_id'] !== '')
            <span class="tg-lbl">Chat de destination</span>
            <code>{{ $telegram['chat_id'] }}</code>
        @endif
    </div>
    @unless ($telegram['configured'])
        <p class="tg-help">Renseignez <code>TELEGRAM_BOT_TOKEN</code> et <code>TELEGRAM_CHAT_ID</code> dans l’environnement du serveur, puis redéployez.</p>
    @endunless
</div>

<div class="grid-2">
    <div class="card">
        <div class="card-title">Demandes reçues (14 derniers jours)</div>
        @php $max = max(1, $chart->max('value')); @endphp
        <div class="chart">
            @foreach ($chart as $point)
                <div class="col">
                    <div class="bar" style="height: {{ max(2, $point['value'] / $max * 85) }}%">@if ($point['value'])<span>{{ $point['value'] }}</span>@endif</div>
                    <div class="lbl">{{ $point['label'] }}</div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="card">
        <div class="card-title">Répartition par statut</div>
        @php
            $colors = ['pending' => 'var(--amber)', 'assigned' => 'var(--blue)', 'in_progress' => '#8b5cf6', 'completed' => 'var(--green)', 'cancelled' => '#a0a8ba'];
            $total = max(1, $stats['orders_total']);
        @endphp
        <div class="status-list">
            @foreach (\App\Models\Order::STATUSES as $key => $label)
                @php $n = $statusCounts[$key] ?? 0; @endphp
                <div class="row">
                    <span>{{ $label }}</span><b>{{ $n }}</b>
                    <div class="track"><div class="fill" style="width: {{ $n / $total * 100 }}%; background: {{ $colors[$key] }}"></div></div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<div class="card" style="padding:0">
    <div class="card-title" style="padding:20px 20px 0">Dernières demandes <a href="{{ route('admin.orders.index') }}" class="btn btn-light btn-sm">Tout voir</a></div>
    @include('admin.orders._table', ['orders' => $latestOrders])
</div>
@endsection
