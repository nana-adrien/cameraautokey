{{-- Tuile logo d'une marque : logo SVG si disponible, sinon nom en style « wordmark ». --}}
@props(['brand', 'size' => 'md', 'link' => true])
@php $logo = $brand->logoUrl(); $tag = $link ? 'a' : 'div'; @endphp
<{{ $tag }} @if ($link) href="{{ route('brands.show', $brand) }}" @endif {{ $attributes->merge(['class' => "brand-tile brand-tile-{$size}"]) }} title="Clé {{ $brand->name }}">
    @if ($logo)
        <img src="{{ $logo }}" alt="Logo {{ $brand->name }}" loading="lazy" width="48" height="48">
    @else
        <span class="brand-wordmark">{{ $brand->name }}</span>
    @endif
    <span class="brand-name">{{ $brand->name }}</span>
</{{ $tag }}>
