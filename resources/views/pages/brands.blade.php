@extends('layouts.site')
@section('title', 'Clés de voiture toutes marques à Douala')
@section('description', 'Toyota, Hyundai, Kia, Nissan, Mercedes, BMW, Ford… mais aussi camions et motos : les marques dont nous copions et codons les clés à Douala.')

@section('content')
@include('partials.page-hero', ['image' => 'photos/cles-acura-rdx.webp', 'position' => '50% 60%', 'titleHtml' => 'Toutes les <em>marques</em>', 'title' => 'Marques couvertes', 'subtitle' => 'Nos techniciens interviennent sur les automobiles, poids lourds et motos des principaux constructeurs.'])
<div class="content-wrap">
    @php $i = 0; @endphp
    @foreach (\App\Models\Brand::CATEGORIES as $cat => $label)
        @if (isset($brands[$cat]))
            <x-sc :num="++$i" :title="$label" :sub="$brands[$cat]->count().' marques'">
                <div class="brand-grid compact">
                    @foreach ($brands[$cat] as $brand)
                        <x-brand-logo :brand="$brand" />
                    @endforeach
                </div>
            </x-sc>
        @endif
    @endforeach
</div>
@endsection
