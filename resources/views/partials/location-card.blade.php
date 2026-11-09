{{--
    Carte de localisation : carte Google Maps intégrée (sans clé d'API) + itinéraire.
    Variables : $name, $logo (URL) ou $logos (liste d'URL), $address, $hours, $phone, $whatsapp, $lat, $lng, $mapsUrl (facultatif),
    $streetView (facultatif) : une entrée de config/streetview.php
--}}
@php
    $hasCoords = is_numeric($lat ?? null) && is_numeric($lng ?? null);
    $point = $hasCoords ? $lat.','.$lng : null;
    $directions = $hasCoords ? 'https://www.google.com/maps/dir/?api=1&destination='.$point : null;
    $openUrl = ($mapsUrl ?? '') ?: ($hasCoords ? 'https://www.google.com/maps/search/?api=1&query='.$point : null);
    $phoneHref = preg_replace('/\s+/', '', $phone ?? '');

    // Street View : URL d'intégration Google si fournie, sinon recherche du panorama le plus proche.
    $sv = $streetView ?? null;
    $svUrl = null;
    if (! empty($sv['enabled'])) {
        if (! empty($sv['embed_url'])) {
            $svUrl = $sv['embed_url'];
        } elseif (is_numeric($sv['lat'] ?? null) && is_numeric($sv['lng'] ?? null)) {
            $svUrl = 'https://maps.google.com/maps?layer=c&cbll='.$sv['lat'].','.$sv['lng']
                .'&cbp=12,'.(float) ($sv['heading'] ?? 0).',0,0,'.(float) ($sv['pitch'] ?? 0).'&hl=fr&output=svembed';
        }
    }
    $viewId = 'loc-'.\Illuminate\Support\Str::slug($name);
@endphp
<div class="location-card">
    <div class="location-head">
        @foreach (array_filter($logos ?? [$logo ?? null]) as $logoUrl)
            <img src="{{ $logoUrl }}" alt="" width="52" height="52" loading="lazy">
        @endforeach
        <div>
            <b>{{ $name }}</b>
            <span><i class="fas fa-map-marker-alt"></i> {{ $address }}</span>
            @if (! empty($hours))<span><i class="far fa-clock"></i> {{ $hours }}</span>@endif
        </div>
    </div>
    @if ($svUrl)
        <div class="location-switch" role="group" aria-label="Type de vue">
            <button type="button" class="active" aria-pressed="true" data-view-target="{{ $viewId }}" data-view="map"><i class="fas fa-map"></i> Carte</button>
            <button type="button" aria-pressed="false" data-view-target="{{ $viewId }}" data-view="street"><i class="fas fa-street-view"></i> Street View</button>
            @if (! empty($sv['test']))<span class="location-test" title="Vue provisoire à remplacer dans config/streetview.php">Vue de test</span>@endif
        </div>
    @endif
    @if ($hasCoords)
        <div class="location-views" id="{{ $viewId }}">
            <iframe class="location-map" data-view-pane="map" title="Plan d’accès — {{ $name }}" src="https://maps.google.com/maps?q={{ $point }}&amp;hl=fr&amp;z=17&amp;output=embed" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
            @if ($svUrl)
                <iframe class="location-map" data-view-pane="street" data-src="{{ $svUrl }}" title="Street View — {{ $name }}" referrerpolicy="no-referrer-when-downgrade" allowfullscreen hidden></iframe>
            @endif
        </div>
    @else
        <div class="location-nomap"><i class="fas fa-map-marked-alt"></i> Plan bientôt disponible — appelez-nous pour être guidé.</div>
    @endif
    <div class="location-actions">
        @if ($directions)<a href="{{ $directions }}" class="btn-red" target="_blank" rel="noopener"><i class="fas fa-route"></i> Itinéraire</a>@endif
        @if ($openUrl)<a href="{{ $openUrl }}" class="btn-outline" target="_blank" rel="noopener"><i class="fab fa-google"></i> Google Maps</a>@endif
        @if ($phoneHref)<a href="tel:{{ $phoneHref }}" class="btn-outline"><i class="fas fa-phone-alt"></i> {{ $phone }}</a>@endif
    </div>
</div>

@once
    @push('scripts')
        <script src="{{ asset('js/location-views.js') }}?v=1"></script>
    @endpush
@endonce
