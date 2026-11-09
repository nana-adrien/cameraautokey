@extends('layouts.site')
@section('title', 'Contact')

@section('content')
@php $tel = preg_replace('/\s+/', '', $settings['phone']); @endphp
@include('partials.page-hero', ['image' => 'photos/atelier-camer-auto-key.webp', 'titleHtml' => '<em>Contactez</em>-nous', 'title' => 'Contact', 'subtitle' => 'Une question, un devis ? Nous répondons rapidement, 7j/7.', 'cta' => false, 'trust' => false])
<div class="content-wrap grid">
    <div>
        <x-sc icon="far fa-envelope" title="Envoyer un message">
            @include('partials.message-form', ['type' => 'contact'])
        </x-sc>
    </div>
    <aside>
        <div class="sidebar-sticky">
            <x-sc icon="fas fa-address-card" title="Nos coordonnées">
                <div class="success-receipt" style="margin:0;max-width:none">
                    <div class="receipt-row"><span class="receipt-lbl">Téléphone</span><a class="receipt-val" href="tel:{{ $tel }}">{{ $settings['phone'] }}</a></div>
                    @if ($settings['phone_secondary'])<div class="receipt-row"><span class="receipt-lbl">Belgique</span><span class="receipt-val">{{ $settings['phone_secondary'] }}</span></div>@endif
                    <div class="receipt-row"><span class="receipt-lbl">E-mail</span><span class="receipt-val">{{ $settings['email'] }}</span></div>
                    <div class="receipt-row"><span class="receipt-lbl">Zone</span><span class="receipt-val">{{ $settings['address'] }}</span></div>
                    <div class="receipt-row"><span class="receipt-lbl">Horaires</span><span class="receipt-val">24h/24 – 7j/7</span></div>
                </div>
            </x-sc>
        </div>
    </aside>
</div>
@endsection
