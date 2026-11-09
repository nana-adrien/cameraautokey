@extends('layouts.site')
@section('title', 'Suivre ma demande')

@section('content')
@include('partials.page-hero', ['image' => 'photos/cle-lexus.webp', 'titleHtml' => 'Suivre ma <em>demande</em>', 'title' => 'Suivre ma demande', 'subtitle' => 'Saisissez la référence reçue lors de votre demande et votre adresse e-mail.', 'cta' => false, 'trust' => false])
<div class="content-wrap" style="max-width:760px">
    <x-sc icon="fas fa-search" title="Retrouver ma demande">
        <form method="POST" action="{{ route('orders.track.search') }}">
            @csrf
            <div class="inp-row two">
                <div class="inp-wrap"><label class="inp-label" for="reference">Référence <span class="req">*</span></label><input class="dkp-input" id="reference" name="reference" value="{{ old('reference', request('reference')) }}" placeholder="CAK-261109-XXXXX" required>@error('reference')<span class="inp-err">{{ $message }}</span>@enderror</div>
                <div class="inp-wrap"><label class="inp-label" for="customer_email">E-mail <span class="req">*</span></label><input class="dkp-input" id="customer_email" type="email" name="customer_email" value="{{ old('customer_email', request('customer_email')) }}" required>@error('customer_email')<span class="inp-err">{{ $message }}</span>@enderror</div>
            </div>
            <button class="btn-cta">Rechercher</button>
        </form>
        @if (! empty($notFound))
            <div class="flash-error" style="margin-top:16px">Aucune demande ne correspond à cette référence et cette adresse e-mail.</div>
        @endif
    </x-sc>

    @isset($order)
        @php $steps = ['pending', 'assigned', 'in_progress', 'completed']; $idx = array_search($order->status, $steps); @endphp
        <x-sc icon="fas fa-clipboard-list" title="Demande {{ $order->reference }}">
            @if ($order->status !== 'cancelled')
                <div class="step-bar" style="padding:0;margin-bottom:16px;flex-wrap:wrap">
                    @foreach ($steps as $i => $s)
                        <div @class(['step-tab', 'done' => $idx !== false && $i < $idx, 'active' => $i === $idx, 'step-future' => $idx !== false && $i > $idx])><span class="sn">{{ $i + 1 }}</span><span class="sl">{{ \App\Models\Order::STATUSES[$s] }}</span></div>
                    @endforeach
                </div>
            @endif
            <div class="success-receipt" style="margin:0;max-width:none">
                <div class="receipt-row"><span class="receipt-lbl">Statut</span><span class="badge-status status-{{ $order->status }}">{{ $order->statusLabel() }}</span></div>
                <div class="receipt-row"><span class="receipt-lbl">Véhicule</span><span class="receipt-val">{{ $order->brand_model }} — {{ $order->plate }}</span></div>
                <div class="receipt-row"><span class="receipt-lbl">Besoin</span><span class="receipt-val">{{ $order->label('need') }}</span></div>
                <div class="receipt-row"><span class="receipt-lbl">Technicien</span><span class="receipt-val">{{ $order->technician?->name ?? 'En cours d’attribution' }}</span></div>
                <div class="receipt-row"><span class="receipt-lbl">Créée le</span><span class="receipt-val">{{ $order->created_at->translatedFormat('d F Y à H\hi') }}</span></div>
            </div>
        </x-sc>
    @endisset
</div>
@endsection
