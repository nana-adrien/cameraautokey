@extends('layouts.site')
@section('title', 'Contact')

@section('content')
@php $tel = preg_replace('/\s+/', '', $settings['phone']); @endphp
@include('partials.page-hero', ['image' => 'photos/facade-camer-auto-key.webp', 'position' => '50% 40%', 'titleHtml' => '<em>Contactez</em>-nous', 'title' => 'Contact', 'subtitle' => 'Une question, un devis ? Nous répondons rapidement, 7j/7.', 'cta' => false, 'trust' => false])
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
                    @if ($settings['phone_secondary'])<div class="receipt-row"><span class="receipt-lbl">Autre ligne</span><span class="receipt-val">{{ $settings['phone_secondary'] }}</span></div>@endif
                    <div class="receipt-row"><span class="receipt-lbl">E-mail</span><span class="receipt-val">{{ $settings['email'] }}</span></div>
                    <div class="receipt-row"><span class="receipt-lbl">Atelier</span><span class="receipt-val">{{ $settings['address'] }}</span></div>
                    @if ($settings['whatsapp'])<div class="receipt-row"><span class="receipt-lbl">WhatsApp</span><a class="receipt-val" href="https://wa.me/{{ $settings['whatsapp'] }}" target="_blank" rel="noopener">Écrire sur WhatsApp</a></div>@endif
                    <div class="receipt-row"><span class="receipt-lbl">Horaires</span><span class="receipt-val">{{ $settings['opening_hours'] }}</span></div>
                </div>
                <iframe title="Plan d’accès — Akwa, Douala" src="https://www.openstreetmap.org/export/embed.html?bbox=9.6880%2C4.0400%2C9.7080%2C4.0560&amp;layer=mapnik&amp;marker=4.0480%2C9.6980" style="width:100%;height:220px;border:0;border-radius:var(--r-sm);margin-top:12px"></iframe>
            </x-sc>
        </div>
    </aside>
</div>
@endsection
