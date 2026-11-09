@extends('layouts.site')
@section('title', $service['seo_title'])
@section('title_suffix', '3K Auto Services')
@section('og_image', config('seo.og_image_garage'))
@section('geo', $settings['garage_lat'].';'.$settings['garage_lng'])
@section('description', \Illuminate\Support\Str::limit($service['excerpt'].' Garage 3K Auto Services à Ndogsimbi, Douala. Devis avant travaux.', 152))

@php $gWa = $settings['garage_whatsapp']; @endphp

@push('jsonld')
<script type="application/ld+json">{!! \App\Support\Seo::json(\App\Support\Seo::garageService($service)) !!}</script>
@endpush

@section('content')
@include('partials.page-hero', [
    'image' => 'photos/'.(in_array($service['photo'], ['diagnostic-tablette.webp', 'pont-elevateur-toyota.webp']) ? 'f150-pont-elevateur.webp' : $service['photo']),
    'title' => $service['title'],
    'titleHtml' => e($service['title']).'<br>à <em>Douala</em>',
    'subtitle' => e($service['excerpt']).' <strong>Devis annoncé avant les travaux.</strong>',
    'cta' => false,
    'trust' => false,
    'logo' => asset('images/logo-3k-auto-services.webp'),
    'badges' => ['🔧 '.$settings['garage_name'], '📍 Ndogsimbi, Douala 5e', '🕗 '.$settings['garage_hours']],
    'crumb' => $service['title'],
    'breadcrumbRoot' => ['Garage 3K', route('garage')],
    'breadcrumbItems' => [[$service['title'], null]],
])

<div class="content-wrap grid">
    <div>
        <x-sc icon="{{ $service['icon'] }}" title="{{ $service['title'] }} au garage 3K Auto Services">
            <div class="g-service-detail">
                <div class="text-block">
                    <p>{{ $service['intro'] }}</p>
                    <h3>Ce que nous faisons</h3>
                    <ul class="g-points">
                        @foreach ($service['points'] as $point)
                            <li><i class="fas fa-check"></i> {{ $point }}</li>
                        @endforeach
                    </ul>
                </div>
                <img src="{{ asset('images/photos/thumbs/'.$service['photo']) }}" alt="{{ $service['title'] }} au garage 3K Auto Services, Douala" width="800" height="600" loading="lazy">
            </div>
        </x-sc>

        <x-sc num="1" title="Comment ça se passe ?">
            <div class="steps-grid">
                <div class="step-card"><span class="n">1</span><b>Prise de rendez-vous</b><p>Par WhatsApp ou téléphone, avec le modèle du véhicule et le symptôme.</p></div>
                <div class="step-card"><span class="n">2</span><b>Diagnostic</b><p>Contrôle visuel et, si besoin, valise électronique pour trouver la cause.</p></div>
                <div class="step-card"><span class="n">3</span><b>Devis</b><p>Prix en FCFA annoncé et validé avec vous avant de commencer.</p></div>
                <div class="step-card"><span class="n">4</span><b>Réparation et essai</b><p>Travaux réalisés, essai du véhicule, puis restitution et paiement.</p></div>
            </div>
        </x-sc>

        <div class="seo-cta g-cta-band">
            <h3>Votre véhicule a besoin d’un passage au garage ?</h3>
            <p>Envoyez-nous le modèle et le problème : nous vous répondons avec un créneau et une première estimation.</p>
            @if ($gWa)<a href="https://wa.me/{{ $gWa }}" class="btn-white" target="_blank" rel="noopener"><i class="fab fa-whatsapp"></i> Rendez-vous WhatsApp</a>@endif
        </div>
    </div>
    <aside>
        <div class="sidebar-sticky">
            <x-sc icon="fas fa-tools" title="Autres prestations" sub="{{ $settings['garage_name'] }}">
                <div class="link-list">
                    @foreach ($others as $oslug => $o)
                        <a href="{{ route('garage.service', $oslug) }}"><i class="{{ $o['icon'] }}" style="color:var(--red);width:18px"></i> {{ $o['title'] }}</a>
                    @endforeach
                </div>
            </x-sc>
            <x-sc icon="fas fa-key" title="Un souci de clé ?" sub="{{ $settings['company_name'] }}">
                <p style="font-size:13.5px;color:var(--muted);margin-bottom:10px">Clé perdue ou télécommande en panne : notre partenaire à Akwa la refait et la code.</p>
                <a href="{{ route('home') }}" class="btn-outline" style="justify-content:center;width:100%"><i class="fas fa-key"></i> Services clés auto</a>
            </x-sc>
        </div>
    </aside>
</div>
@endsection
