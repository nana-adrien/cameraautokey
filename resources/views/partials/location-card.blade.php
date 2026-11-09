{{--
    Carte de localisation : carte Google Maps intégrée (sans clé d'API) + itinéraire.
    Variables : $name, $logo (URL) ou $logos (liste d'URL), $address, $hours, $phone, $whatsapp, $lat, $lng, $mapsUrl (facultatif)
--}}
@php
    $hasCoords = is_numeric($lat ?? null) && is_numeric($lng ?? null);
    $point = $hasCoords ? $lat.','.$lng : null;
    $directions = $hasCoords ? 'https://www.google.com/maps/dir/?api=1&destination='.$point : null;
    $openUrl = ($mapsUrl ?? '') ?: ($hasCoords ? 'https://www.google.com/maps/search/?api=1&query='.$point : null);
    $phoneHref = preg_replace('/\s+/', '', $phone ?? '');
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
    @if ($hasCoords)
        <iframe class="location-map" title="Plan d’accès — {{ $name }}" src="https://maps.google.com/maps?q={{ $point }}&amp;hl=fr&amp;z=17&amp;output=embed" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
    @else
        <div class="location-nomap"><i class="fas fa-map-marked-alt"></i> Plan bientôt disponible — appelez-nous pour être guidé.</div>
    @endif
    <div class="location-actions">
        @if ($directions)<a href="{{ $directions }}" class="btn-red" target="_blank" rel="noopener"><i class="fas fa-route"></i> Itinéraire</a>@endif
        @if ($openUrl)<a href="{{ $openUrl }}" class="btn-outline" target="_blank" rel="noopener"><i class="fab fa-google"></i> Google Maps</a>@endif
        @if ($phoneHref)<a href="tel:{{ $phoneHref }}" class="btn-outline"><i class="fas fa-phone-alt"></i> {{ $phone }}</a>@endif
    </div>
</div>
