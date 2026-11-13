@extends('layouts.admin')
@section('title', 'Message de '.$message->name)

@section('content')
<div class="page-head">
    <div><a href="{{ route('admin.messages.index') }}" class="muted">← Messages</a><h1 style="margin-top:6px">{{ $message->subject ?: $message->typeLabel() }}</h1></div>
    <div class="actions">
        <a href="mailto:{{ $message->email }}" class="btn btn-dark">Répondre par e-mail</a>
        <a href="{{ route('admin.messages.pdf', $message) }}" class="btn btn-light">Télécharger le PDF</a>
        <form method="POST" action="{{ route('admin.messages.destroy', $message) }}" onsubmit="return confirm('Supprimer ce message ?')">@csrf @method('DELETE')<button class="btn btn-danger">Supprimer</button></form>
    </div>
</div>
<div class="card">
    <dl class="dl" style="margin-bottom:18px">
        <dt>Type</dt><dd>{{ $message->typeLabel() }}</dd>
        <dt>Nom</dt><dd>{{ $message->name }}</dd>
        <dt>E-mail</dt><dd>{{ $message->email }}</dd>
        <dt>Téléphone</dt><dd>{{ $message->phone ?: '—' }}</dd>
        @if ($message->company)<dt>Société</dt><dd>{{ $message->company }}</dd>@endif
        <dt>Reçu le</dt><dd>{{ $message->created_at->format('d/m/Y à H:i') }}</dd>
    </dl>
    <div style="white-space:pre-line;background:var(--bg);padding:16px;border-radius:10px">{{ $message->message }}</div>
</div>
@endsection
