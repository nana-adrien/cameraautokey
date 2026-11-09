<section class="page-hero">
    <div class="container">
        <div class="breadcrumb"><a href="{{ route('home') }}">Accueil</a> › {{ $crumb ?? $title }}</div>
        <h1>{{ $title }}</h1>
        @isset($subtitle)<p>{{ $subtitle }}</p>@endisset
    </div>
</section>
