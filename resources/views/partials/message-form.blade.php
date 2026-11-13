{{-- Formulaire générique : $type = contact | intervenant | professionnel --}}
@if (session('success'))
    <div class="flash-success">✓ {{ session('success') }}</div>
@endif
<form method="POST" action="{{ route('contact.send') }}" data-track-form="{{ $type }}">
    @csrf
    <input type="hidden" name="type" value="{{ $type }}">
    <div class="inp-row two">
        <div class="inp-wrap"><label class="inp-label" for="name">Nom complet <span class="req">*</span></label><input class="dkp-input" id="name" name="name" value="{{ old('name') }}" required autocomplete="name">@error('name')<span class="inp-err">{{ $message }}</span>@enderror</div>
        <div class="inp-wrap"><label class="inp-label" for="email">E-mail <span class="req">*</span></label><input class="dkp-input" id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email">@error('email')<span class="inp-err">{{ $message }}</span>@enderror</div>
        <div class="inp-wrap"><label class="inp-label" for="phone">Téléphone</label><input class="dkp-input" id="phone" type="tel" name="phone" value="{{ old('phone') }}" autocomplete="tel"></div>
        @if (in_array($type, ['contact', 'garage']))
            <div class="inp-wrap"><label class="inp-label" for="subject">Sujet</label><input class="dkp-input" id="subject" name="subject" value="{{ old('subject') }}"></div>
        @else
            <div class="inp-wrap"><label class="inp-label" for="company">{{ $type === 'intervenant' ? 'Société / statut' : 'Entreprise' }}</label><input class="dkp-input" id="company" name="company" value="{{ old('company') }}" autocomplete="organization"></div>
        @endif
        <div class="inp-wrap full"><label class="inp-label" for="message">{{ $messageLabel ?? 'Message' }} <span class="req">*</span></label><textarea class="dkp-textarea" id="message" name="message" rows="5" required>{{ old('message') }}</textarea>@error('message')<span class="inp-err">{{ $message }}</span>@enderror</div>
    </div>
    <button class="btn-cta">{{ $button ?? 'Envoyer' }}</button>
</form>
