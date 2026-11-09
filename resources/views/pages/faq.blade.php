@extends('layouts.site')
@section('title', 'FAQ serrurerie automobile')

@section('content')
@include('partials.page-hero', ['image' => 'photos/cles-toyota-interieur.webp', 'titleHtml' => 'Questions <em>fréquentes</em>', 'title' => 'FAQ', 'subtitle' => 'Tarifs, délais, documents à fournir, garantie : toutes les réponses à vos questions.'])
<div class="content-wrap grid">
    <div>
        <x-sc icon="far fa-question-circle" title="Questions fréquentes — clé de voiture">
            <div class="faq-list wide">
                @foreach ($faqs as $faq)
                    <details class="faq-item" @if ($loop->first) open @endif><summary>{{ $faq->question }}</summary><div class="faq-a">{{ $faq->answer }}</div></details>
                @endforeach
            </div>
        </x-sc>
    </div>
    <aside>@include('partials.side-cta')</aside>
</div>
@endsection
