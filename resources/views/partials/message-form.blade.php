{{-- Formulaire générique : $type = contact | intervenant | professionnel --}}
@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
<form method="POST" action="{{ route('contact.send') }}" class="card">
    @csrf
    <input type="hidden" name="type" value="{{ $type }}">
    <div class="fields two">
        <div class="field"><label for="name">Nom complet *</label><input id="name" name="name" value="{{ old('name') }}" required>@error('name')<div class="err">{{ $message }}</div>@enderror</div>
        <div class="field"><label for="email">E-mail *</label><input id="email" type="email" name="email" value="{{ old('email') }}" required>@error('email')<div class="err">{{ $message }}</div>@enderror</div>
        <div class="field"><label for="phone">Téléphone</label><input id="phone" name="phone" value="{{ old('phone') }}"></div>
        @if ($type === 'contact')
            <div class="field"><label for="subject">Sujet</label><input id="subject" name="subject" value="{{ old('subject') }}"></div>
        @else
            <div class="field"><label for="company">{{ $type === 'intervenant' ? 'Société / statut' : 'Entreprise' }}</label><input id="company" name="company" value="{{ old('company') }}"></div>
        @endif
        <div class="field full"><label for="message">{{ $messageLabel ?? 'Message' }} *</label><textarea id="message" name="message" rows="5" required>{{ old('message') }}</textarea>@error('message')<div class="err">{{ $message }}</div>@enderror</div>
    </div>
    <button class="btn btn-red" style="margin-top:16px">{{ $button ?? 'Envoyer' }}</button>
</form>
