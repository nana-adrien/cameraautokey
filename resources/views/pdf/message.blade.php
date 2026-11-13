@php
    $messageType = $message->typeLabel();
@endphp
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Message {{ $message->id }}</title>
    <style>
        @page { margin: 30px 34px 58px; }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: "DejaVu Sans", sans-serif; font-size: 10.5px; color: #1a2540; line-height: 1.5; }
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
        .section { margin-bottom: 16px; page-break-inside: avoid; }
        .section-title { font-size: 8.2px; font-weight: bold; text-transform: uppercase; letter-spacing: .1em; color: #1a2f5e; border-bottom: 1px solid #dde3ee; padding-bottom: 4px; margin-bottom: 8px; }
        .kv { width: 100%; border-collapse: collapse; }
        .kv td { padding: 4px 0; vertical-align: top; font-size: 9.8px; }
        .kv .k { width: 26%; color: #6b7a99; }
        .kv .v { font-weight: bold; color: #1a2540; }
        .body { background: #f5f6fa; border-left: 3px solid #d6262e; padding: 10px 12px; font-size: 9.8px; white-space: pre-line; }
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

    <h1 class="title">{{ $messageType }}</h1>
    <p class="subtitle">Message reçu sur le site {{ $settings['company_name'] }}.</p>

    <table class="meta">
        <tr>
            <td><span class="label">Reçu le</span><span class="value">{{ $message->created_at->translatedFormat('d F Y à H\hi') }}</span></td>
            <td><span class="label">Nom</span><span class="value">{{ $message->name }}</span></td>
            <td><span class="label">E-mail</span><span class="value">{{ $message->email }}</span></td>
            <td><span class="label">Téléphone</span><span class="value">{{ $message->phone ?: '—' }}</span></td>
        </tr>
    </table>

    <div class="section">
        <div class="section-title">Coordonnées</div>
        <table class="kv">
            @if ($message->company)<tr><td class="k">Société</td><td class="v">{{ $message->company }}</td></tr>@endif
            @if ($message->subject)<tr><td class="k">Sujet</td><td class="v">{{ $message->subject }}</td></tr>@endif
            <tr><td class="k">Type</td><td class="v">{{ $messageType }}</td></tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Message</div>
        <div class="body">{{ $message->message }}</div>
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
