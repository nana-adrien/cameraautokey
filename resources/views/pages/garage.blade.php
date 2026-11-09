@extends('layouts.site')
@section('title', $settings['garage_name'].' — garage automobile à Douala')
@section('description', 'Mécanique générale, entretien, vidange, diagnostic électronique, freinage, alarme, GPS et caméras : le garage '.$settings['garage_name'].', partenaire de '.$settings['company_name'].' à Douala.')

@php
    $gTel = preg_replace('/\s+/', '', $settings['garage_phone']);
    $services = [
        ['fas fa-cogs', 'Mécanique générale', 'Moteur, boîte de vitesses, embrayage, distribution et suspensions.'],
        ['fas fa-oil-can', 'Entretien & vidange', 'Vidange, filtres, niveaux et révision selon le carnet du constructeur.'],
        ['fas fa-laptop-medical', 'Diagnostic électronique', 'Lecture des codes défaut, calculateurs et voyants allumés.'],
        ['fas fa-compact-disc', 'Freinage', 'Plaquettes, disques et contrôle complet du système de freinage.'],
        ['fas fa-shield-alt', 'Alarme & GPS', 'Pose d’alarmes et de traceurs GPS pour protéger votre véhicule.'],
        ['fas fa-video', 'Caméras & écrans', 'Caméra de recul 360°, écrans multimédia et accessoires.'],
        ['fas fa-truck-pickup', 'Pick-up & 4x4', 'Hilux, Land Cruiser, F-150, RAM : nous connaissons les gros gabarits.'],
        ['fas fa-boxes', 'Vente de pièces', 'Pièces automobiles neuves pour la plupart des marques.'],
    ];
    $photos = [
        ['f150-pont-elevateur.webp', 'Ford F-150 sur pont élévateur'],
        ['mecanicien-capot-toyota.webp', 'Diagnostic sous le capot'],
        ['vidange-moteur.webp', 'Vidange moteur'],
        ['atelier-cour.webp', 'La cour de l’atelier'],
        ['pont-elevateur-toyota.webp', 'Toyota Hilux sur pont'],
        ['installation-camera-recul.webp', 'Installation d’une caméra de recul'],
        ['equipe-camer-auto-key.webp', 'Notre équipe'],
        ['diagnostic-tablette.webp', 'Diagnostic sur tablette'],
    ];
@endphp

@section('content')
@include('partials.page-hero', [
    'image' => 'photos/f150-pont-elevateur.webp',
    'position' => '50% 55%',
    'logo' => asset('images/logo-3k-auto-services.webp'),
    'title' => $settings['garage_name'],
    'titleHtml' => 'Garage <em>3K Auto Services</em><br>à Douala',
    'subtitle' => 'Le garage partenaire de <strong>'.e($settings['company_name']).'</strong> : mécanique, entretien, diagnostic et équipements pour tous vos véhicules.',
    'cta' => false,
    'trust' => false,
    'badges' => ['🔧 Mécanique générale', '🛢️ Entretien & vidange', '💻 Diagnostic électronique', '🚙 Pick-up & 4x4'],
    'crumb' => 'Garage 3K Auto Services',
])

<div class="content-wrap grid">
    <div>
        <div class="garage-intro">
            <img src="{{ asset('images/logo-3k-auto-services.webp') }}" alt="Logo {{ $settings['garage_name'] }}" width="110" height="102">
            <div>
                <h2>Deux enseignes, une seule équipe</h2>
                <p><strong>{{ $settings['company_name'] }}</strong> s’occupe de vos clés (vente, reproduction, programmation), et <strong>{{ $settings['garage_name'] }}</strong> prend soin du reste de votre véhicule. Même équipe, mêmes exigences, un seul interlocuteur.</p>
                <div class="hero-actions" style="margin:12px 0 0">
                    @if ($settings['garage_whatsapp'])
                        <a href="https://wa.me/{{ $settings['garage_whatsapp'] }}" class="btn-red" target="_blank" rel="noopener"><i class="fab fa-whatsapp"></i> Prendre rendez-vous</a>
                    @endif
                    <a href="tel:{{ $gTel }}" class="btn-outline"><i class="fas fa-phone-alt"></i> {{ $settings['garage_phone'] }}</a>
                </div>
            </div>
        </div>

        <x-sc num="1" title="Nos prestations garage" sub="Tous types de véhicules : citadines, SUV, pick-up, utilitaires">
            <div class="garage-services">
                @foreach ($services as [$icon, $name, $desc])
                    <div class="garage-service"><span><i class="{{ $icon }}"></i></span><div><b>{{ $name }}</b><p>{{ $desc }}</p></div></div>
                @endforeach
            </div>
        </x-sc>

        <x-sc num="2" title="L’atelier en images">
            <div class="gallery-grid garage-gallery">
                @foreach ($photos as [$file, $caption])
                    <button type="button" class="gallery-item" data-full="{{ asset('images/photos/'.$file) }}" data-caption="{{ $caption }}">
                        <img src="{{ asset('images/photos/thumbs/'.$file) }}" alt="{{ $caption }}" loading="lazy" width="800" height="600">
                        <span class="gallery-cap"><i class="fas fa-search-plus"></i> {{ $caption }}</span>
                    </button>
                @endforeach
            </div>
        </x-sc>

        <x-sc icon="fas fa-map-marker-alt" title="Accès au garage" sub="{{ $settings['garage_address'] }}">
            @include('partials.location-card', [
                'name' => $settings['garage_name'],
                'logo' => null,
                'address' => $settings['garage_address'],
                'hours' => $settings['garage_hours'],
                'phone' => $settings['garage_phone'],
                'lat' => $settings['garage_lat'],
                'lng' => $settings['garage_lng'],
                'mapsUrl' => $settings['garage_maps_url'],
                'streetView' => config('streetview.garage'),
            ])
        </x-sc>

        <x-sc num="3" title="Nous recrutons : mécanicien automobile" sub="Rejoignez l’équipe {{ $settings['garage_name'] }}" id="recrutement">
            <div class="recruit">
                <button type="button" class="gallery-item recruit-flyer" data-full="{{ asset('images/photos/recrutement-mecanicien-3k.webp') }}" data-caption="Recrutement — mécanicien automobile">
                    <img src="{{ asset('images/photos/recrutement-mecanicien-3k.webp') }}" alt="Affiche de recrutement d’un mécanicien automobile chez {{ $settings['garage_name'] }}" width="1600" height="1600" loading="lazy">
                </button>
                <div>
                    <p style="font-size:14.5px;line-height:1.7;margin-bottom:12px">Le garage recrute un <strong>mécanicien automobile</strong>. Conditions :</p>
                    <ul class="recruit-list">
                        <li><i class="fas fa-check-circle"></i> Permis de conduire</li>
                        <li><i class="fas fa-check-circle"></i> 4 années d’expérience</li>
                        <li><i class="fas fa-check-circle"></i> Formation reconnue en mécanique automobile</li>
                    </ul>
                    <p style="font-size:14px;margin:14px 0 10px"><b>Envoyez votre CV :</b></p>
                    <div class="hero-actions" style="margin:0">
                        @if ($settings['garage_whatsapp'])
                            <a href="https://wa.me/{{ $settings['garage_whatsapp'] }}" class="btn-red" target="_blank" rel="noopener"><i class="fab fa-whatsapp"></i> {{ $settings['garage_phone'] }}</a>
                        @endif
                        <a href="mailto:{{ $settings['email'] }}?subject={{ rawurlencode('Candidature mécanicien automobile') }}" class="btn-outline"><i class="far fa-envelope"></i> {{ $settings['email'] }}</a>
                    </div>
                </div>
            </div>
        </x-sc>
    </div>
    <aside>
        <div class="sidebar-sticky">
            <x-sc icon="fas fa-tools" title="{{ $settings['garage_name'] }}" sub="Garage partenaire" class="side-card">
                @if ($settings['garage_whatsapp'])
                    <a href="https://wa.me/{{ $settings['garage_whatsapp'] }}" class="btn-red" target="_blank" rel="noopener"><i class="fab fa-whatsapp"></i> Rendez-vous WhatsApp</a>
                @endif
                <a href="tel:{{ $gTel }}" class="btn-outline"><i class="fas fa-phone-alt"></i> {{ $settings['garage_phone'] }}</a>
                <div class="trust-list">
                    <span class="trust-item"><span class="tdot"></span>{{ $settings['garage_address'] }}</span>
                    <span class="trust-item"><span class="tdot"></span>{{ $settings['garage_hours'] }}</span>
                    <span class="trust-item"><span class="tdot"></span>Devis avant toute intervention</span>
                </div>
            </x-sc>
            <x-sc icon="fas fa-key" title="Besoin d’une clé ?" sub="{{ $settings['company_name'] }}">
                <p style="font-size:13.5px;color:var(--muted);margin-bottom:10px">Double, perte totale, programmation : nos spécialistes de la clé sont juste à côté.</p>
                <a href="{{ route('home') }}#form-area" class="btn-outline" style="justify-content:center;width:100%"><i class="fas fa-key"></i> Refaire ma clé</a>
            </x-sc>
        </div>
    </aside>
</div>

<div class="lightbox" id="lightbox" hidden>
    <button type="button" class="lightbox-close" aria-label="Fermer">&times;</button>
    <figure><img src="" alt=""><figcaption></figcaption></figure>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/lightbox.js') }}?v=1"></script>
@endpush
