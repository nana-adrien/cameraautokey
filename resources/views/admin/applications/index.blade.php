@extends('layouts.admin')
@section('title', 'Candidatures')

@section('content')
<div class="page-head">
    <h1>Candidatures reçues</h1>
    <a href="{{ route('admin.job-offers.index') }}" class="btn btn-dark">Gérer les offres</a>
</div>
<form method="GET" class="card form" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end;margin-bottom:18px">
    <div class="field" style="flex:1;min-width:220px;margin:0">
        <label for="offre">Offre</label>
        <select id="offre" name="offre">
            <option value="">Toutes les offres</option>
            @foreach ($offers as $offer)
                <option value="{{ $offer->id }}" @selected((string) request('offre') === (string) $offer->id)>{{ $offer->title }} — {{ $offer->companyLabel() }}</option>
            @endforeach
        </select>
    </div>
    <div class="field" style="min-width:180px;margin:0">
        <label for="status">Statut</label>
        <select id="status" name="status">
            <option value="">Tous</option>
            @foreach (\App\Models\JobApplication::STATUSES as $key => $label)
                <option value="{{ $key }}" @selected(request('status') === $key)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <button class="btn btn-primary">Filtrer</button>
</form>
<div class="table-wrap">
    <table>
        <thead><tr><th>Candidat</th><th>Offre</th><th>Statut</th><th>Date</th><th></th></tr></thead>
        <tbody>
        @forelse ($applications as $a)
            <tr @class(['unread' => ! $a->read_at])>
                <td>{{ $a->name }}<div class="muted" style="font-size:.8rem">{{ $a->phone }} · {{ $a->email }}</div></td>
                <td>{{ $a->jobOffer->title }}<div class="muted" style="font-size:.8rem">{{ $a->jobOffer->companyLabel() }}</div></td>
                <td>{{ $a->statusLabel() }}</td>
                <td class="muted">{{ $a->created_at->format('d/m/Y H:i') }}</td>
                <td class="actions"><a href="{{ route('admin.applications.show', $a) }}" class="btn btn-light btn-sm">Ouvrir</a><a href="{{ route('admin.applications.cv', $a) }}" class="btn btn-light btn-sm">CV</a></td>
            </tr>
        @empty
            <tr><td colspan="5" class="muted" style="text-align:center;padding:30px">Aucune candidature.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
{{ $applications->links() }}
@endsection
