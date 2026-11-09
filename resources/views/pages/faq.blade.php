@extends('layouts.site')
@section('title', 'FAQ serrurerie automobile')

@section('content')
@include('partials.page-hero', ['title' => 'Questions fréquentes', 'crumb' => 'FAQ'])
<section class="section">
    <div class="container" style="max-width:860px">
        @foreach ($faqs as $faq)
            <details class="faq-item" @if ($loop->first) open @endif>
                <summary>{{ $faq->question }}</summary>
                <div>{{ $faq->answer }}</div>
            </details>
        @endforeach
        <p class="center muted" style="margin-top:30px">Vous ne trouvez pas votre réponse ? <a href="{{ route('contact') }}" style="color:var(--red);font-weight:700">Contactez-nous</a>.</p>
    </div>
</section>
@endsection
