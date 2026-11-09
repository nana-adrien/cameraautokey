@extends('layouts.site')
@section('title', 'FAQ serrurerie automobile')

@section('content')
@include('partials.page-hero', ['image' => 'photos/cles-toyota-interieur.webp', 'titleHtml' => 'Questions <em>fréquentes</em>', 'title' => 'FAQ', 'subtitle' => 'Prix, délais, papiers à apporter, garantie : l’essentiel avant de venir.'])
<div class="content-wrap grid">
    <div>
        <x-sc icon="far fa-question-circle" title="Vos questions">
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
