{{--
    Corps commun des pages service et marque.
    Variables : $lead (HTML), $subject (ex. « perte totale de clés »), $priceFrom (int), $faqs, $brands, $testimonials, $links
--}}
@php
    $tel = preg_replace('/\s+/', '', $settings['phone']);
    $rows = [
        ['Voiture courante (Toyota Corolla / Yaris, Hyundai, Kia, Nissan…)', 1, 1.6],
        ['SUV et 4x4 (RAV4, Hilux, Prado, Land Cruiser, Fortuner…)', 1.3, 2.2],
        ['Véhicule premium (Lexus, Mercedes, BMW, Range Rover…)', 1.8, 2.8],
        ['Utilitaire / poids lourd', 1.4, 2.4],
        ['Moto / scooter', 0.6, 1.2],
    ];
    $round = fn ($n) => \App\Support\Money::fcfa(round($n / 5000) * 5000);
    $zones = ['Douala – Akwa', 'Bonanjo', 'Bonapriso', 'Bali', 'Deïdo', 'Bonabéri', 'Bonamoussadi', 'Makepe', 'Kotto', 'Logpom', 'Ndokoti', 'Bassa', 'PK14', 'Yaoundé', 'Kribi', 'Limbé', 'Buea', 'Edéa', 'Bafoussam', 'Bamenda'];
@endphp
<div class="content-wrap grid">
    <div>
        <p class="lead-text">{!! $lead !!}</p>

        <x-sc icon="fas fa-check-double" title="Pourquoi venir chez {{ $settings['company_name'] }}" sub="Une boutique physique, des outils pro, des prix clairs">
            <div class="stats-grid">
                <div><div class="stat-val">Akwa</div><div class="stat-lbl">Boutique et atelier à Douala</div></div>
                <div><div class="stat-val">5</div><div class="stat-lbl">Types de clés pris en charge</div></div>
                <div><div class="stat-val">FCFA</div><div class="stat-lbl">Prix confirmé avant de commencer</div></div>
                <div><div class="stat-val">1<em>mois</em></div><div class="stat-lbl">Garantie sur chaque clé</div></div>
            </div>
        </x-sc>

        <x-sc num="1" title="De l’appel à la clé en main" sub="Ce qui va se passer">
            <div class="warn-box"><b>⚠️ Clés volées ?</b><span>Pensez à faire une déclaration auprès de la police ou de la gendarmerie : elle vous sera utile, notamment pour votre assureur.</span></div>
            <div class="steps-grid">
                <div class="step-card"><span class="n">1</span><b>Vous nous contactez</b><p>Par le <a href="{{ route('home') }}#commande" style="color:var(--navy);font-weight:700">formulaire</a>, par téléphone au {{ $settings['phone'] }} ou sur WhatsApp, avec une photo de la clé si vous l’avez.</p></div>
                <div class="step-card"><span class="n">2</span><b>Prix et rendez-vous</b><p>On vous annonce le montant en FCFA et l’heure de passage — ou vous venez à l’atelier d’Akwa.</p></div>
                <div class="step-card"><span class="n">3</span><b>Fabrication</b><p>Taille de la lame sur machine, puis codage de la puce ou de la télécommande avec nos outils de diagnostic.</p></div>
                <div class="step-card"><span class="n">4</span><b>Essai et paiement</b><p>Vous ouvrez et démarrez vous-même. Ensuite seulement, vous payez et recevez la facture.</p></div>
            </div>
        </x-sc>

        <x-sc num="2" title="Prix indicatifs — {{ $subject }}" sub="Fourchettes indicatives — prix confirmé avant l’intervention">
            <div class="table-scroll">
                <table class="price-table">
                    <thead><tr><th>Véhicule / situation</th><th>Fourchette</th></tr></thead>
                    <tbody>
                        @foreach ($rows as [$labelRow, $min, $max])
                            <tr><td>{{ $labelRow }}</td><td>{{ $round($priceFrom * $min) }} – {{ $round($priceFrom * $max) }}</td></tr>
                        @endforeach
                        <tr><td>Majoration urgence (nuit, week-end, jours fériés)</td><td>+{{ \App\Support\Money::fcfa(config('pricing.emergency')) }}</td></tr>
                    </tbody>
                </table>
            </div>
            <div class="tip-box"><b>Bon à savoir :</b> à la boutique d’Akwa, la clé est souvent prête pendant que vous patientez — pensez à apporter la carte grise.</div>
        </x-sc>

        @if ($faqs->isNotEmpty())
            <x-sc num="3" title="Questions fréquentes">
                <div class="faq-list wide">
                    @foreach ($faqs as $faq)
                        <details class="faq-item" @if ($loop->first) open @endif><summary>{{ $faq->question }}</summary><div class="faq-a">{{ $faq->answer }}</div></details>
                    @endforeach
                </div>
            </x-sc>
        @endif

        <x-sc num="4" title="Marques couvertes" sub="Automobiles, poids lourds et motos">
            <div class="brand-grid compact">
                @foreach ($brands->sortByDesc(fn ($b) => (bool) $b->logoUrl()) as $b)
                    <x-brand-logo :brand="$b" size="sm" />
                @endforeach
            </div>
            <p style="font-size:13px;color:var(--muted);margin-top:14px">Votre véhicule n’apparaît pas ? <a href="tel:{{ $tel }}" style="color:var(--navy);font-weight:700">Téléphonez-nous</a> avec le modèle et l’année : nous vous disons tout de suite si c’est faisable.</p>
        </x-sc>

        <x-sc num="5" title="Où intervenons-nous ?" sub="Atelier à Akwa (Douala) — déplacement dans tout le Cameroun">
            <div class="chip-cloud">@foreach ($zones as $z)<span class="chip">📍 {{ $z }}</span>@endforeach</div>
        </x-sc>

        @if ($testimonials->isNotEmpty())
            <x-sc num="6" title="Avis clients">
                <div class="steps-grid">
                    @foreach ($testimonials as $t)
                        <div class="step-card"><span style="color:#f59e0b">★★★★★</span><p style="margin:6px 0 8px;color:var(--text)">« {{ $t->content }} »</p><b style="font-size:13px">{{ $t->name }} — {{ $t->city }}</b></div>
                    @endforeach
                </div>
            </x-sc>
        @endif

        <div class="seo-cta" style="margin-top:12px">
            <h3>On s’occupe de votre clé aujourd’hui ?</h3>
            <p>Passez à la boutique d’Akwa ou appelez-nous : un technicien peut aussi venir jusqu’à votre véhicule.</p>
            <a href="tel:{{ $tel }}" class="btn-white"><i class="fas fa-phone-alt"></i> {{ $settings['phone'] }}</a>
        </div>
    </div>
    <aside>@include('partials.side-cta')</aside>
</div>
