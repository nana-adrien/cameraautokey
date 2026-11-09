@extends('layouts.site')
@section('title', 'Marques couvertes')

@section('content')
@include('partials.page-hero', ['image' => 'photos/cles-toyota-tacoma.webp', 'titleHtml' => 'Toutes les <em>marques</em>', 'title' => 'Marques couvertes', 'subtitle' => 'Nos techniciens interviennent sur les automobiles, poids lourds et motos des principaux constructeurs.'])
<div class="content-wrap">
    @php $i = 0; @endphp
    @foreach (\App\Models\Brand::CATEGORIES as $cat => $label)
        @if (isset($brands[$cat]))
            <x-sc :num="++$i" :title="$label" :sub="$brands[$cat]->count().' marques'">
                <div class="chip-cloud">
                    @foreach ($brands[$cat] as $brand)
                        <a class="chip" href="{{ route('brands.show', $brand) }}">{{ $brand->name }}</a>
                    @endforeach
                </div>
            </x-sc>
        @endif
    @endforeach
</div>
@endsection
