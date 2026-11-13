@extends('layouts.admin')
@section('title', 'Candidature de '.$application->name)

@section('content')
<div class="page-head">
    <div><a href="{{ route('admin.applications.index') }}" class="muted">← Candidatures</a><h1 style="margin-top:6px">{{ $application->name }}</h1></div>
    <div class="actions">
        <a href="{{ route('admin.applications.cv', $application) }}" class="btn btn-dark">Télécharger le CV</a>
        <a href="mailto:{{ $application->email }}" class="btn btn-light">Répondre par e-mail</a>
        <form method="POST" action="{{ route('admin.applications.destroy', $application) }}" onsubmit="return confirm('Supprimer cette candidature et son CV ?')">@csrf @method('DELETE')<button class="btn btn-danger">Supprimer</button></form>
    </div>
</div>
<div class="card">
    <dl class="dl" style="margin-bottom:18px">
        <dt>Offre</dt><dd>{{ $application->jobOffer->title }} — {{ $application->jobOffer->companyLabel() }}</dd>
        <dt>Téléphone</dt><dd>{{ $application->phone }}</dd>
        <dt>E-mail</dt><dd>{{ $application->email }}</dd>
        <dt>CV</dt><dd>{{ $application->cv_original_name }}</dd>
        <dt>Reçue le</dt><dd>{{ $application->created_at->format('d/m/Y à H:i') }}</dd>
    </dl>
    @if ($application->message)
        <div style="white-space:pre-line;background:var(--bg);padding:16px;border-radius:10px;margin-bottom:18px">{{ $application->message }}</div>
    @endif
    <form method="POST" action="{{ route('admin.applications.update', $application) }}" class="form" style="display:flex;gap:12px;align-items:flex-end;flex-wrap:wrap">
        @csrf @method('PUT')
        <div class="field" style="margin:0;min-width:220px">
            <label for="status">Statut de la candidature</label>
            <select id="status" name="status">
                @foreach (\App\Models\JobApplication::STATUSES as $key => $label)
                    <option value="{{ $key }}" @selected($application->status === $key)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <button class="btn btn-primary">Enregistrer</button>
    </form>
</div>
@endsection
