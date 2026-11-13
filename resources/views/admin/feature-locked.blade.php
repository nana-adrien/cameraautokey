@extends('layouts.admin')
@section('title', $featureLabel)

@section('content')
<div class="page-head"><h1>{{ $featureLabel }}</h1></div>
<div class="card" style="text-align:center;padding:48px 20px">
    <div style="font-size:42px">🚧</div>
    <h2 style="margin:12px 0 6px">En cours de développement</h2>
    <p class="muted">Ce module sera activé à la livraison du site.</p>
</div>
@endsection
