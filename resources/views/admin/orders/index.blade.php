@extends('layouts.admin')
@section('title', 'Demandes d’intervention')

@section('content')
<div class="page-head"><h1>Demandes d’intervention</h1></div>
<form class="filters" method="GET">
    <input type="search" name="q" value="{{ request('q') }}" placeholder="Référence, client, plaque, ville…">
    <select name="status" onchange="this.form.submit()">
        <option value="">Tous les statuts</option>
        @foreach (\App\Models\Order::STATUSES as $key => $label)
            <option value="{{ $key }}" @selected(request('status') === $key)>{{ $label }}</option>
        @endforeach
    </select>
    <button class="btn btn-dark">Filtrer</button>
    @if (request()->hasAny(['q', 'status']))<a href="{{ route('admin.orders.index') }}" class="btn btn-light">Réinitialiser</a>@endif
</form>
<div class="table-wrap">@include('admin.orders._table')</div>
{{ $orders->links() }}
@endsection
