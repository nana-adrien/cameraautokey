@extends('layouts.site')
@section('title', 'Marques couvertes')

@section('content')
@include('partials.page-hero', ['title' => 'Marques couvertes', 'subtitle' => 'Nos techniciens interviennent sur les automobiles, poids lourds et motos des principaux constructeurs.'])
<section class="section">
    <div class="container">
        @foreach (\App\Models\Brand::CATEGORIES as $cat => $label)
            @if (isset($brands[$cat]))
                <h2 style="font-size:1.3rem;margin:10px 0 16px">{{ $label }}</h2>
                <div class="brand-cloud" style="justify-content:flex-start;margin-bottom:36px">
                    @foreach ($brands[$cat] as $brand)
                        <a class="brand-chip" href="{{ route('brands.show', $brand) }}">{{ $brand->name }}</a>
                    @endforeach
                </div>
            @endif
        @endforeach
    </div>
</section>
@endsection
