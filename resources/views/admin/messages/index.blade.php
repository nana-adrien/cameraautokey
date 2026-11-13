@extends('layouts.admin')
@section('title', 'Messages')

@section('content')
<div class="page-head"><h1>Messages reçus</h1></div>
<div class="tabs">
    <a href="{{ route('admin.messages.index') }}" @class(['active' => ! request('type')])>Tous</a>
    @foreach (\App\Models\ContactMessage::TYPES as $key => $label)
        <a href="{{ route('admin.messages.index', ['type' => $key]) }}" @class(['active' => request('type') === $key])>{{ $label }}</a>
    @endforeach
</div>
<div class="table-wrap">
    <table>
        <thead><tr><th>Type</th><th>Expéditeur</th><th>Sujet / message</th><th>Date</th><th></th></tr></thead>
        <tbody>
        @forelse ($messages as $m)
            <tr @class(['unread' => ! $m->read_at])>
                <td>{{ $m->typeLabel() }}</td>
                <td>{{ $m->name }}<div class="muted" style="font-size:.8rem">{{ $m->email }}</div></td>
                <td>{{ $m->subject ?: \Illuminate\Support\Str::limit($m->message, 70) }}</td>
                <td class="muted">{{ $m->created_at->format('d/m/Y H:i') }}</td>
                <td class="actions"><a href="{{ route('admin.messages.show', $m) }}" class="btn btn-light btn-sm">Lire</a><a href="{{ route('admin.messages.pdf', $m) }}" class="btn btn-light btn-sm">PDF</a></td>
            </tr>
        @empty
            <tr><td colspan="5" class="muted" style="text-align:center;padding:30px">Aucun message.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
{{ $messages->links() }}
@endsection
