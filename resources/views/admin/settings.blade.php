@extends('layouts.admin')
@section('title', 'Paramètres')

@section('content')
<div class="page-head"><h1>Paramètres du site</h1></div>
<form method="POST" action="{{ route('admin.settings.update') }}" class="card form">
    @csrf @method('PUT')
    @foreach ($labels as $key => $label)
        <div class="field">
            <label for="{{ $key }}">{{ $label }}</label>
            @if (str_starts_with($key, 'hero_subtitle'))
                <textarea id="{{ $key }}" name="{{ $key }}">{{ old($key, $values[$key] ?? '') }}</textarea>
            @else
                <input id="{{ $key }}" name="{{ $key }}" value="{{ old($key, $values[$key] ?? '') }}">
            @endif
            @error($key)<div class="err">{{ $message }}</div>@enderror
        </div>
    @endforeach
    <div><button class="btn btn-primary">Enregistrer</button></div>
</form>
@endsection
