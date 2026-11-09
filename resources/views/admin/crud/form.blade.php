@extends('layouts.admin')
@section('title', ($record->exists ? 'Modifier' : 'Ajouter').' · '.$title)

@section('content')
<div class="page-head">
    <div><a href="{{ route("admin.$routeName.index") }}" class="muted">← {{ $title }}</a><h1 style="margin-top:6px">{{ $record->exists ? 'Modifier' : 'Ajouter' }} : {{ $singular }}</h1></div>
</div>
<form method="POST" action="{{ $record->exists ? route("admin.$routeName.update", $record->id) : route("admin.$routeName.store") }}" class="card form">
    @csrf
    @if ($record->exists) @method('PUT') @endif

    @foreach ($fields as $name => $field)
        @php $value = old($name, $record->{$name}); @endphp
        <div class="field">
            @if ($field['type'] === 'checkbox')
                <label class="switch"><input type="checkbox" name="{{ $name }}" value="1" @checked($record->exists ? $value : old($name, true))> {{ $field['label'] }}</label>
            @else
                <label for="{{ $name }}">{{ $field['label'] }}</label>
                @if ($field['type'] === 'textarea')
                    <textarea id="{{ $name }}" name="{{ $name }}">{{ $value }}</textarea>
                @elseif ($field['type'] === 'select')
                    <select id="{{ $name }}" name="{{ $name }}">
                        @foreach ($field['options'] as $key => $label)
                            <option value="{{ $key }}" @selected($value === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                @else
                    <input id="{{ $name }}" type="{{ $field['type'] }}" name="{{ $name }}" value="{{ $value }}">
                @endif
            @endif
            @isset($field['help'])<div class="help">{{ $field['help'] }}</div>@endisset
            @error($name)<div class="err">{{ $message }}</div>@enderror
        </div>
    @endforeach

    <div class="actions" style="justify-content:flex-start">
        <button class="btn btn-primary">Enregistrer</button>
        <a href="{{ route("admin.$routeName.index") }}" class="btn btn-light">Annuler</a>
    </div>
</form>
@endsection
