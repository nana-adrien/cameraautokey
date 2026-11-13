@extends('layouts.site')
@section('title', $offer->title.' — '.$offer->companyLabel())
@section('description', \Illuminate\Support\Str::limit($offer->summary, 155))

@section('content')
@include('partials.page-hero', ['image' => 'photos/installation-camera-recul.webp', 'titleHtml' => e($offer->title), 'title' => $offer->title, 'subtitle' => e($offer->companyLabel()).' · '.e($offer->contract_type).' · '.e($offer->location), 'cta' => false, 'trust' => false])
<div class="content-wrap grid">
    <div>
        <x-sc num="1" title="Le poste">
            <p>{{ $offer->summary }}</p>
            <h3 class="job-h3">Missions</h3>
            <ul class="job-list">
                @foreach (preg_split('/\R/', trim($offer->description)) as $line)
                    @if (trim($line) !== '')<li><i class="fas fa-check-circle"></i> {{ trim($line, " \t-•") }}</li>@endif
                @endforeach
            </ul>
            @if ($offer->requirements)
                <h3 class="job-h3">Profil recherché</h3>
                <ul class="job-list">
                    @foreach (preg_split('/\R/', trim($offer->requirements)) as $line)
                        @if (trim($line) !== '')<li><i class="fas fa-user-check"></i> {{ trim($line, " \t-•") }}</li>@endif
                    @endforeach
                </ul>
            @endif
            <div class="job-meta" style="margin-top:14px">
                <span><i class="fas fa-building"></i> {{ $offer->companyLabel() }}</span>
                <span><i class="fas fa-file-signature"></i> {{ $offer->contract_type }}</span>
                <span><i class="fas fa-map-marker-alt"></i> {{ $offer->location }}</span>
                @if ($offer->closes_at)<span><i class="far fa-calendar"></i> Candidatures jusqu’au {{ $offer->closes_at->format('d/m/Y') }}</span>@endif
            </div>
        </x-sc>

        <x-sc num="2" title="Postuler à cette offre">
            @if (session('success'))
                <div class="flash-success">✓ {{ session('success') }}</div>
            @endif
            <form method="POST" action="{{ route('careers.apply', $offer) }}" enctype="multipart/form-data" data-track-form="recrutement">
                @csrf
                <div class="inp-row two">
                    <div class="inp-wrap"><label class="inp-label" for="name">Nom complet <span class="req">*</span></label><input class="dkp-input" id="name" name="name" value="{{ old('name') }}" required autocomplete="name">@error('name')<span class="inp-err">{{ $message }}</span>@enderror</div>
                    <div class="inp-wrap"><label class="inp-label" for="phone">Téléphone <span class="req">*</span></label><input class="dkp-input" id="phone" type="tel" name="phone" value="{{ old('phone') }}" required autocomplete="tel">@error('phone')<span class="inp-err">{{ $message }}</span>@enderror</div>
                    <div class="inp-wrap full"><label class="inp-label" for="email">E-mail <span class="req">*</span></label><input class="dkp-input" id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email">@error('email')<span class="inp-err">{{ $message }}</span>@enderror</div>
                    <div class="inp-wrap full"><label class="inp-label" for="cv">Votre CV (PDF ou Word, 5 Mo maximum) <span class="req">*</span></label><input class="dkp-input job-file" id="cv" type="file" name="cv" accept=".pdf,.doc,.docx" required>@error('cv')<span class="inp-err">{{ $message }}</span>@enderror</div>
                    <div class="inp-wrap full"><label class="inp-label" for="message">Quelques mots sur vous</label><textarea class="dkp-textarea" id="message" name="message" rows="4">{{ old('message') }}</textarea>@error('message')<span class="inp-err">{{ $message }}</span>@enderror</div>
                    <div class="inp-wrap full"><label class="job-consent"><input type="checkbox" name="accept_privacy" value="1" required @checked(old('accept_privacy'))> J’accepte que mes informations et mon CV soient conservés pour le traitement de ma candidature.</label>@error('accept_privacy')<span class="inp-err">{{ $message }}</span>@enderror</div>
                </div>
                <button class="btn-cta"><i class="fas fa-paper-plane"></i> Envoyer ma candidature</button>
            </form>
        </x-sc>
    </div>
    <aside>
        <x-sc icon="fas fa-briefcase" title="Autres offres">
            <p><a href="{{ route('careers.index') }}" style="text-decoration:underline">← Toutes les offres</a></p>
        </x-sc>
        @include('partials.side-cta', ['sideTitle' => 'Une question ?'])
    </aside>
</div>
@endsection
