@extends('layouts.admin')
@section('title', $title)

@section('content')
<div class="page-head">
    <h1>{{ $title }}</h1>
    <a href="{{ route("admin.$routeName.create") }}" class="btn btn-primary">+ Ajouter</a>
</div>
<form class="filters" method="GET">
    <input type="search" name="q" value="{{ request('q') }}" placeholder="Rechercher…">
    <button class="btn btn-dark">Rechercher</button>
</form>
<div class="table-wrap">
    <table>
        <thead><tr>@foreach ($columns as $label)<th>{{ $label }}</th>@endforeach<th></th></tr></thead>
        <tbody>
        @forelse ($records as $record)
            <tr>
                @foreach ($columns as $col => $label)
                    @php $val = $record->{$col}; $field = $fields[$col] ?? null; @endphp
                    <td>
                        @if (is_bool($val))
                            <span class="{{ $val ? 'yes' : 'no' }}">{{ $val ? '● Oui' : '○ Non' }}</span>
                        @elseif ($field && $field['type'] === 'select')
                            {{ $field['options'][$val] ?? $val }}
                        @else
                            {{ \Illuminate\Support\Str::limit((string) $val, 60) }}
                        @endif
                    </td>
                @endforeach
                <td class="actions">
                    <a href="{{ route("admin.$routeName.edit", $record->id) }}" class="btn btn-light btn-sm">Modifier</a>
                    <form method="POST" action="{{ route("admin.$routeName.destroy", $record->id) }}" onsubmit="return confirm('Supprimer cet élément ?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm">Supprimer</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="{{ count($columns) + 1 }}" class="muted" style="text-align:center;padding:30px">Aucun élément.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
{{ $records->links() }}
@endsection
