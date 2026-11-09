@extends('layouts.admin')
@section('title', 'Demande '.$order->reference)

@section('content')
<div class="page-head">
    <div>
        <a href="{{ route('admin.orders.index') }}" class="muted">← Demandes</a>
        <h1 style="margin-top:6px">{{ $order->reference }} @if ($order->emergency)<span class="status status-pending">⚡ Urgence</span>@endif</h1>
    </div>
    <span class="status status-{{ $order->status }}" style="font-size:.9rem">{{ $order->statusLabel() }}</span>
</div>

<div class="grid-2">
    <div style="display:grid;gap:18px">
        <div class="card">
            <div class="card-title">Prestation demandée</div>
            <dl class="dl">
                <dt>Véhicule</dt><dd>{{ $order->label('vehicle_type') }}</dd>
                <dt>Besoin</dt><dd>{{ $order->label('need') }}</dd>
                <dt>Type de clé</dt><dd>{{ $order->label('key_type') }}</dd>
                <dt>Clé souhaitée</dt><dd>{{ $order->label('desired_key') }}</dd>
                <dt>Quantité</dt><dd>{{ $order->quantity }}</dd>
                <dt>Garantie 1 an</dt><dd>{{ $order->warranty_extension ? 'Oui' : 'Non' }}</dd>
                <dt>Marque / modèle</dt><dd>{{ $order->brand_model }} ({{ $order->year }})</dd>
                <dt>Plaque</dt><dd>{{ $order->plate }}</dd>
            </dl>
        </div>
        <div class="card">
            <div class="card-title">Client & adresse</div>
            <dl class="dl">
                <dt>Nom</dt><dd>{{ $order->customer_name }}</dd>
                <dt>Téléphone</dt><dd><a href="tel:{{ $order->customer_phone }}">{{ $order->customer_phone }}</a></dd>
                <dt>E-mail</dt><dd><a href="mailto:{{ $order->customer_email }}">{{ $order->customer_email }}</a></dd>
                <dt>Adresse</dt><dd>{{ $order->address }}, {{ $order->district }}, {{ $order->city }}</dd>
                <dt>Précisions</dt><dd>{{ $order->notes ?: '—' }}</dd>
                <dt>Créée le</dt><dd>{{ $order->created_at->format('d/m/Y à H:i') }}</dd>
                @if ($order->completed_at)<dt>Terminée le</dt><dd>{{ $order->completed_at->format('d/m/Y à H:i') }}</dd>@endif
            </dl>
        </div>
    </div>

    <div style="display:grid;gap:18px;align-content:start">
        <form method="POST" action="{{ route('admin.orders.update', $order) }}" class="card form">
            @csrf @method('PUT')
            <div class="card-title" style="margin:0">Traitement</div>
            <div class="field">
                <label for="status">Statut</label>
                <select id="status" name="status">
                    @foreach (\App\Models\Order::STATUSES as $key => $label)
                        <option value="{{ $key }}" @selected($order->status === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="field">
                <label for="technician_id">Technicien</label>
                <select id="technician_id" name="technician_id">
                    <option value="">— Non attribué —</option>
                    @foreach ($technicians as $tech)
                        <option value="{{ $tech->id }}" @selected($order->technician_id === $tech->id)>{{ $tech->name }} ({{ $tech->zone }})</option>
                    @endforeach
                </select>
            </div>
            <div class="field">
                <label for="estimated_price">Prix (FCFA)</label>
                <input id="estimated_price" type="number" min="0" name="estimated_price" value="{{ old('estimated_price', $order->estimated_price) }}">
                @error('estimated_price')<div class="err">{{ $message }}</div>@enderror
            </div>
            <button class="btn btn-primary" style="justify-content:center">Enregistrer</button>
        </form>
        <form method="POST" action="{{ route('admin.orders.destroy', $order) }}" onsubmit="return confirm('Supprimer définitivement cette demande ?')">
            @csrf @method('DELETE')
            <button class="btn btn-danger" style="width:100%;justify-content:center">Supprimer la demande</button>
        </form>
    </div>
</div>
@endsection
