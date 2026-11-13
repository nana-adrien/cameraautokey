@php
    $statusColors = [
        'pending' => '#a35a00',
        'assigned' => '#2346a8',
        'in_progress' => '#6234b8',
        'completed' => '#166c3b',
        'cancelled' => '#5b6478',
    ];
    $statusColor = $statusColors[$order->status] ?? '#5b6478';
@endphp
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Demande {{ $order->reference }}</title>
    <style>
        @page { margin: 30px 34px 58px; }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: "DejaVu Sans", sans-serif; font-size: 10.5px; color: #1a2540; line-height: 1.45; }
        .header { width: 100%; border-collapse: collapse; margin-bottom: 18px; }
        .header td { vertical-align: middle; }
        .logo { width: 58px; height: 58px; }
        .brand { font-size: 17px; font-weight: bold; color: #1a2f5e; letter-spacing: .4px; }
        .brand small { display: block; font-size: 8.5px; font-weight: normal; color: #6b7a99; letter-spacing: .2px; margin-top: 2px; }
        .company { text-align: right; font-size: 8.6px; color: #6b7a99; line-height: 1.55; }
        .rule { height: 3px; background: #d6262e; margin: 0 0 20px; }
        .title { font-size: 18px; font-weight: bold; color: #1a2f5e; margin: 0 0 2px; }
        .subtitle { color: #6b7a99; font-size: 9.5px; margin: 0 0 16px; }
        .meta { width: 100%; border-collapse: collapse; margin-bottom: 18px; }
        .meta td { background: #f5f6fa; border: 1px solid #dde3ee; padding: 8px 10px; }
        .meta .label { display: block; font-size: 7.6px; text-transform: uppercase; letter-spacing: .08em; color: #6b7a99; }
        .meta .value { display: block; font-size: 11px; font-weight: bold; color: #1a2f5e; margin-top: 2px; }
        .section { margin-bottom: 14px; page-break-inside: avoid; }
        .section-title { font-size: 8.2px; font-weight: bold; text-transform: uppercase; letter-spacing: .1em; color: #1a2f5e; border-bottom: 1px solid #dde3ee; padding-bottom: 4px; margin-bottom: 8px; }
        .grid { width: 100%; border-collapse: collapse; }
        .grid td { width: 50%; vertical-align: top; padding-right: 14px; }
        .grid td:last-child { padding-right: 0; padding-left: 14px; }
        .kv { width: 100%; border-collapse: collapse; }
        .kv td { padding: 3px 0; vertical-align: top; font-size: 9.8px; }
        .kv .k { width: 38%; color: #6b7a99; }
        .kv .v { font-weight: bold; color: #1a2540; }
        .items { width: 100%; border-collapse: collapse; margin-top: 4px; }
        .items th { background: #1a2f5e; color: #fff; text-align: left; font-size: 8px; text-transform: uppercase; letter-spacing: .07em; padding: 7px 9px; }
        .items td { border-bottom: 1px solid #dde3ee; padding: 7px 9px; font-size: 9.8px; }
        .items .right { text-align: right; }
        .total { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .total td { padding: 8px 10px; }
        .total .label { text-align: right; color: #6b7a99; font-size: 9px; text-transform: uppercase; letter-spacing: .06em; }
        .total .amount { text-align: right; font-size: 16px; font-weight: bold; color: #d6262e; white-space: nowrap; }
        .note { background: #f5f6fa; border-left: 3px solid #d6262e; padding: 8px 10px; color: #1a2540; font-size: 9.4px; white-space: pre-line; }
        .status { display: inline-block; padding: 3px 9px; border-radius: 9px; font-size: 8.6px; font-weight: bold; color: #fff; background: {{ $statusColor }}; }
        .terms { margin-top: 16px; font-size: 8.4px; color: #6b7a99; line-height: 1.55; }
        .footer { position: fixed; bottom: -38px; left: 0; right: 0; border-top: 1px solid #dde3ee; padding-top: 7px; font-size: 7.8px; color: #6b7a99; }
        .footer .right { text-align: right; }
    </style>
</head>
<body>
    <table class="header">
        <tr>
            <td style="width:70px">
                @if ($logo)<img class="logo" src="{{ $logo }}" alt="">@endif
            </td>
            <td>
                <div class="brand">{{ $settings['company_name'] }}<small>Clés auto · Programmation · Douala</small></div>
            </td>
            <td class="company">
                {{ $settings['address'] }}<br>
                {{ $settings['phone'] }} · {{ $settings['email'] }}<br>
                {{ $settings['opening_hours'] }}
            </td>
        </tr>
    </table>
    <div class="rule"></div>

    <h1 class="title">Fiche de demande d’intervention</h1>
    <p class="subtitle">Document récapitulatif de la demande enregistrée sur {{ $settings['company_name'] }}.</p>

    <table class="meta">
        <tr>
            <td><span class="label">Référence</span><span class="value">{{ $order->reference }}</span></td>
            <td><span class="label">Date de la demande</span><span class="value">{{ $order->created_at->translatedFormat('d F Y à H\hi') }}</span></td>
            <td><span class="label">Statut</span><span class="value"><span class="status">{{ $order->statusLabel() }}</span></span></td>
            <td><span class="label">Prix estimé</span><span class="value">{{ \App\Support\Money::fcfa($order->estimated_price) }}</span></td>
        </tr>
    </table>

    <div class="section">
        <div class="section-title">Client & adresse</div>
        <table class="grid">
            <tr>
                <td>
                    <table class="kv">
                        <tr><td class="k">Nom</td><td class="v">{{ $order->customer_name }}</td></tr>
                        <tr><td class="k">Téléphone</td><td class="v">{{ $order->customer_phone }}</td></tr>
                        <tr><td class="k">E-mail</td><td class="v">{{ $order->customer_email }}</td></tr>
                    </table>
                </td>
                <td>
                    <table class="kv">
                        <tr><td class="k">Adresse</td><td class="v">{{ $order->address }}</td></tr>
                        <tr><td class="k">Quartier</td><td class="v">{{ $order->district }}</td></tr>
                        <tr><td class="k">Ville</td><td class="v">{{ $order->city }}</td></tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Véhicule</div>
        <table class="grid">
            <tr>
                <td>
                    <table class="kv">
                        <tr><td class="k">Marque / modèle</td><td class="v">{{ $order->brand_model }}</td></tr>
                        <tr><td class="k">Année</td><td class="v">{{ $order->year }}</td></tr>
                    </table>
                </td>
                <td>
                    <table class="kv">
                        <tr><td class="k">Immatriculation</td><td class="v">{{ $order->plate }}</td></tr>
                        <tr><td class="k">Catégorie</td><td class="v">{{ $order->label('vehicle_type') }}</td></tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Prestation demandée</div>
        <table class="items">
            <thead>
                <tr>
                    <th>Prestation</th>
                    <th>Type de clé</th>
                    <th>Gamme</th>
                    <th class="right">Quantité</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $order->label('need') }}</td>
                    <td>{{ $order->label('key_type') }}</td>
                    <td>{{ $order->label('desired_key') }}</td>
                    <td class="right">{{ $order->quantity }}</td>
                </tr>
            </tbody>
        </table>
        <table class="total">
            <tr>
                <td class="label">Options</td>
                <td class="right" style="font-size:9.2px;text-align:right">
                    {{ $order->warranty_extension ? 'Garantie 12 mois' : 'Garantie 1 mois' }}
                    · {{ $order->emergency ? 'Passage express' : 'Délai standard' }}
                </td>
            </tr>
            <tr>
                <td class="label">Prix estimé (confirmé par téléphone)</td>
                <td class="amount">{{ \App\Support\Money::fcfa($order->estimated_price) }}</td>
            </tr>
        </table>
    </div>

    @if ($order->notes)
        <div class="section">
            <div class="section-title">Précisions du client</div>
            <div class="note">{{ $order->notes }}</div>
        </div>
    @endif

    <div class="terms">
        Rien n’est à payer avant l’intervention. Le tarif est confirmé par téléphone, puis réglé après essai de la clé.
        La carte grise du véhicule et une pièce d’identité au même nom sont demandées. Garantie : 1 mois, ou 12 mois avec l’option.
        Ce document est un récapitulatif de demande, il ne vaut pas facture.
    </div>

    <div class="footer">
        <table style="width:100%">
            <tr>
                <td>{{ $settings['company_name'] }} · {{ $settings['phone'] }} · {{ $settings['email'] }}</td>
                <td class="right">Généré le {{ $generatedAt->translatedFormat('d/m/Y à H:i') }}</td>
            </tr>
        </table>
    </div>
</body>
</html>
