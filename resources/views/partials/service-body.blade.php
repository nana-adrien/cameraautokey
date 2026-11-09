{{--
    Corps commun des pages service et marque.
    Variables : $lead (HTML), $subject (ex. « perte totale de clés »), $priceFrom (int), $faqs, $brands, $testimonials, $links
--}}
@php
    $tel = preg_replace('/\s+/', '', $settings['phone']);
    $rows = [
        ['Voiture standard (Renault, Peugeot, Citroën, Volkswagen…)', 1, 1.6],
        ['Véhicule premium (BMW, Mercedes, Audi…)', 1.4, 2.2],
        ['Véhicule haut de gamme (Porsche, Range Rover, Tesla…)', 1.8, 2.8],
        ['Utilitaire / poids lourd', 1.3, 2.4],
        ['Moto / scooter', 0.8, 1.5],
    ];
    $round = fn ($n) => (int) (round($n / 5) * 5);
    $zones = ['Paris', 'Hauts-de-Seine', 'Seine-Saint-Denis', 'Val-de-Marne', 'Yvelines', 'Essonne', 'Val-d’Oise', 'Seine-et-Marne', 'Lyon', 'Marseille', 'Lille', 'Toulouse', 'Bordeaux', 'Nantes', 'Nice', 'Strasbourg', 'Bruxelles', 'Liège', 'Charleroi', 'Namur'];
@endphp
<div class="content-wrap grid">
    <div>
        <p class="lead-text">{!! $lead !!}</p>

        <x-sc icon="far fa-chart-bar" title="{{ $settings['company_name'] }} en chiffres" sub="Le service de référence en serrurerie automobile mobile">
            <div class="stats-grid">
                <div><div class="stat-val">45<em>min</em></div><div class="stat-lbl">Temps d’arrivée moyen</div></div>
                <div><div class="stat-val">24/7</div><div class="stat-lbl">Disponibilité urgence</div></div>
                <div><div class="stat-val">100%</div><div class="stat-lbl">Ouverture sans dégât</div></div>
                <div><div class="stat-val">4,9<em>★</em></div><div class="stat-lbl">Note clients</div></div>
            </div>
        </x-sc>

        <x-sc num="1" title="Comment se passe l’intervention ?" sub="Le déroulé étape par étape">
            <div class="warn-box"><b>⚠️ En cas de vol :</b><span>déposez plainte auprès des autorités avant l’intervention. Votre assurance pourra vous la demander pour un remboursement.</span></div>
            <div class="steps-grid">
                <div class="step-card"><span class="n">1</span><b>Contactez-nous</b><p>Faites votre <a href="{{ route('home') }}#commande" style="color:var(--navy);font-weight:700">demande en ligne</a> ou appelez le {{ $settings['phone'] }}. Un conseiller vous répond 24h/24.</p></div>
                <div class="step-card"><span class="n">2</span><b>Restez près du véhicule</b><p>Indiquez l’adresse exacte ; le technicien vous tient informé de son heure d’arrivée.</p></div>
                <div class="step-card"><span class="n">3</span><b>Intervention sur place</b><p>Le technicien utilise un équipement professionnel pour fabriquer et programmer la clé, sans clé d’origine si besoin.</p></div>
                <div class="step-card"><span class="n">4</span><b>Validation &amp; règlement</b><p>Vous validez la prestation, puis vous réglez. La facture vous est envoyée par e-mail.</p></div>
            </div>
        </x-sc>

        <x-sc num="2" title="Tarifs {{ $subject }}" sub="Fourchettes indicatives TTC — prix confirmé avant le départ du technicien">
            <div class="table-scroll">
                <table class="price-table">
                    <thead><tr><th>Véhicule / situation</th><th>Fourchette TTC</th></tr></thead>
                    <tbody>
                        @foreach ($rows as [$labelRow, $min, $max])
                            <tr><td>{{ $labelRow }}</td><td>{{ $round($priceFrom * $min) }} – {{ $round($priceFrom * $max) }} €</td></tr>
                        @endforeach
                        <tr><td>Majoration urgence (nuit, week-end, jours fériés)</td><td>+{{ config('pricing.emergency') }} €</td></tr>
                    </tbody>
                </table>
            </div>
            <div class="tip-box"><b>Astuce :</b> votre assurance auto peut couvrir tout ou partie de cette prestation. Demandez-nous une facture détaillée.</div>
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
            <div class="chip-cloud">
                @foreach ($brands as $b)
                    <a class="chip" href="{{ route('brands.show', $b) }}">{{ $b->name }}</a>
                @endforeach
            </div>
            <p style="font-size:13px;color:var(--muted);margin-top:14px">Vous ne trouvez pas votre marque ? <a href="tel:{{ $tel }}" style="color:var(--navy);font-weight:700">Appelez-nous</a> : nous vérifions la compatibilité avant de nous déplacer.</p>
        </x-sc>

        <x-sc num="5" title="Zones d’intervention" sub="France et Belgique">
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
            <h3>Besoin d’une intervention maintenant ?</h3>
            <p>Un technicien {{ $settings['company_name'] }} peut être chez vous dans l’heure, 24h/24 et 7j/7.</p>
            <a href="tel:{{ $tel }}" class="btn-white"><i class="fas fa-phone-alt"></i> {{ $settings['phone'] }}</a>
        </div>
    </div>
    <aside>@include('partials.side-cta')</aside>
</div>
