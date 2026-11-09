@extends('layouts.site')
@section('title', 'Suivre ma demande')

@section('content')
@include('partials.page-hero', ['title' => 'Suivre ma demande', 'subtitle' => 'Saisissez la référence reçue lors de votre demande et votre adresse e-mail.'])
<section class="section">
    <div class="container" style="max-width:760px">
        <form method="POST" action="{{ route('orders.track.search') }}" class="card">
            @csrf
            <div class="fields two">
                <div class="field"><label for="reference">Référence *</label><input id="reference" name="reference" value="{{ old('reference', request('reference')) }}" placeholder="CAK-251109-XXXXX" required>@error('reference')<div class="err">{{ $message }}</div>@enderror</div>
                <div class="field"><label for="customer_email">E-mail *</label><input id="customer_email" type="email" name="customer_email" value="{{ old('customer_email', request('customer_email')) }}" required>@error('customer_email')<div class="err">{{ $message }}</div>@enderror</div>
            </div>
            <button class="btn btn-red" style="margin-top:16px">Rechercher</button>
        </form>

        @if (! empty($notFound))
            <div class="alert alert-error" style="margin-top:20px">Aucune demande ne correspond à cette référence et cette adresse e-mail.</div>
        @endif

        @isset($order)
            <div class="card" style="margin-top:20px">
                <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap">
                    <h2 style="margin:0">{{ $order->reference }}</h2>
                    <span class="status status-{{ $order->status }}">{{ $order->statusLabel() }}</span>
                </div>
                @php $steps = ['pending', 'assigned', 'in_progress', 'completed']; $idx = array_search($order->status, $steps); @endphp
                @if ($order->status !== 'cancelled')
                    <div class="progress" style="box-shadow:none;padding:16px 0;margin:10px 0">
                        @foreach ($steps as $i => $s)
                            <div @class(['p-step', 'done' => $idx !== false && $i < $idx, 'active' => $i === $idx])><span class="dot">{{ $i + 1 }}</span>{{ \App\Models\Order::STATUSES[$s] }}</div>
                            @if (! $loop->last)<div class="bar"></div>@endif
                        @endforeach
                    </div>
                @endif
                <div class="summary-row"><span>Véhicule</span><span>{{ $order->brand_model }} — {{ $order->plate }}</span></div>
                <div class="summary-row"><span>Besoin</span><span>{{ $order->label('need') }}</span></div>
                <div class="summary-row"><span>Technicien</span><span>{{ $order->technician?->name ?? 'En cours d’attribution' }}</span></div>
                <div class="summary-row"><span>Créée le</span><span>{{ $order->created_at->translatedFormat('d F Y à H\hi') }}</span></div>
                <div class="summary-total"><span class="muted">Prix estimé TTC</span><strong>{{ $order->estimated_price }} €</strong></div>
            </div>
        @endisset
    </div>
</section>
@endsection
