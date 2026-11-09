@extends('layouts.site')

@section('content')
<section class="hero">
    <div class="container">
        <div>
            <h1>{{ $settings['hero_title'] }} <em>On intervient chez vous.</em></h1>
            <p class="lead">{{ $settings['hero_subtitle'] }}</p>
            <p class="lead" style="font-size:.98rem">Voitures · Utilitaires · Camions · Motos — partout en France et en Belgique.</p>
            <div class="actions">
                <a href="#commande" class="btn btn-green btn-lg">🚀 Démarrer ma demande</a>
                <a href="tel:{{ preg_replace('/\s+/', '', $settings['phone']) }}" class="btn btn-ghost btn-lg">📞 {{ $settings['phone'] }}</a>
            </div>
            <div class="badges">
                <span class="badge-pill">🛡️ Techniciens certifiés</span>
                <span class="badge-pill">⚡ Intervention d’urgence</span>
                <span class="badge-pill">🕒 Service 24h/24 – 7j/7</span>
                <span class="badge-pill">💶 Prix annoncé à l’avance</span>
                <span class="badge-pill">⭐ 4,8/5 avis clients</span>
            </div>
        </div>
        <div class="hero-card">
            <div class="big-key">🔑</div>
            <ul>
                <li><span class="ic">📝</span><div><b>Demande en 2 minutes</b><small>Configurez votre besoin, le prix s’affiche en direct.</small></div></li>
                <li><span class="ic">🚐</span><div><b>Atelier mobile</b><small>Taille et programmation de la clé sur place.</small></div></li>
                <li><span class="ic">✅</span><div><b>Paiement après intervention</b><small>Vous validez, puis vous réglez. Facture par e-mail.</small></div></li>
            </ul>
        </div>
    </div>
</section>

<section class="order-wrap" id="commande">
    <div class="container">
        <div class="progress" id="progress">
            <div class="p-step active" data-step="1"><span class="dot">1</span>Configuration</div>
            <div class="bar"></div>
            <div class="p-step" data-step="2"><span class="dot">2</span>Localisation</div>
            <div class="bar"></div>
            <div class="p-step" data-step="3"><span class="dot">3</span>Confirmation</div>
        </div>

        @if ($errors->any())
            <div class="alert alert-error">
                Merci de corriger les champs suivants :
                <ul style="margin:6px 0 0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @php
            $step2Fields = ['customer_name', 'customer_phone', 'customer_email', 'address', 'postal_code', 'city', 'notes', 'accept_terms'];
            $startStep = $errors->hasAny($step2Fields) ? 2 : 1;
            $v = fn ($name, $default = null) => old($name, $default);
        @endphp

        <form method="POST" action="{{ route('orders.store') }}" id="order-form" data-start-step="{{ $startStep }}" novalidate>
            @csrf
            <div class="order-layout">
                <div>
                    {{-- ============ ÉTAPE 1 : CONFIGURATION ============ --}}
                    <div class="panel" data-panel="1">
                        <div class="info-box">
                            <h3>ℹ️ Comment se déroule votre intervention ?</h3>
                            <span class="muted">De la demande à la facture : simple, rapide et transparent.</span>
                            <ol>
                                <li><b>Demande</b> — vous configurez votre besoin ; aucun paiement à la commande.</li>
                                <li><b>Attribution</b> — notre équipe vous attribue un technicien proche de vous.</li>
                                <li><b>Intervention</b> — fabrication, programmation ou ouverture sur place.</li>
                                <li><b>Validation</b> — vous validez la prestation, puis vous réglez. Facture envoyée par e-mail.</li>
                            </ol>
                        </div>

                        <div class="q-block">
                            <div class="q-title"><span class="n">1</span>Type de véhicule *</div>
                            <p class="q-help">Choisissez le type de véhicule concerné.</p>
                            <div class="choices cols-3">
                                @foreach ($pricing['vehicle_types'] as $key => $opt)
                                    <div class="choice">
                                        <input type="radio" name="vehicle_type" id="vt-{{ $key }}" value="{{ $key }}" @checked($v('vehicle_type', 'auto') === $key)>
                                        <label for="vt-{{ $key }}"><span class="emoji">{{ $opt['icon'] }}</span><span><b>{{ $opt['label'] }}</b><small>{{ $opt['hint'] }}</small></span></label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="q-block">
                            <div class="q-title"><span class="n">2</span>Quel est votre besoin ? *</div>
                            <div class="choices" style="margin-top:12px">
                                @foreach ($pricing['needs'] as $key => $opt)
                                    <div class="choice">
                                        <input type="radio" name="need" id="need-{{ $key }}" value="{{ $key }}" @checked($v('need') === $key)>
                                        <label for="need-{{ $key }}"><span><b>{{ $opt['label'] }}</b><small>{{ $opt['hint'] }}</small></span><span class="tag">dès {{ $opt['base'] }} €</span></label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="q-block">
                            <div class="q-title"><span class="n">3</span>Type de clé du véhicule *</div>
                            <p class="q-help">Choisissez ce qui ressemble le plus à votre clé actuelle.</p>
                            <div class="choices cols-2">
                                @foreach ($pricing['key_types'] as $key => $opt)
                                    <div class="choice">
                                        <input type="radio" name="key_type" id="kt-{{ $key }}" value="{{ $key }}" @checked($v('key_type') === $key)>
                                        <label for="kt-{{ $key }}"><span><b>{{ $opt['label'] }}</b><small>{{ $opt['hint'] }}</small></span></label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="q-block">
                            <div class="q-title"><span class="n">4</span>Type de clé souhaitée *</div>
                            <p class="q-help">Les clés génériques sont des modèles compatibles de qualité professionnelle ; les clés d’origine portent le logo du constructeur.</p>
                            <div class="choices cols-2">
                                @foreach ($pricing['desired_keys'] as $key => $opt)
                                    <div class="choice">
                                        <input type="radio" name="desired_key" id="dk-{{ $key }}" value="{{ $key }}" @checked($v('desired_key', 'generic') === $key)>
                                        <label for="dk-{{ $key }}"><span><b>{{ $opt['label'] }}</b><small>{{ $opt['hint'] }}</small></span></label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="q-block">
                            <div class="q-title"><span class="n">5</span>Nombre de clés *</div>
                            <p class="q-help">Nous recommandons d’avoir au moins deux clés. La 2ᵉ clé bénéficie de −{{ $pricing['second_key_discount'] * 100 }} %.</p>
                            <div class="choices cols-2">
                                <div class="choice">
                                    <input type="radio" name="quantity" id="qty-1" value="1" @checked((string) $v('quantity', '1') === '1')>
                                    <label for="qty-1"><span class="emoji">🔑</span><b>1 clé</b></label>
                                </div>
                                <div class="choice">
                                    <input type="radio" name="quantity" id="qty-2" value="2" @checked((string) $v('quantity') === '2')>
                                    <label for="qty-2"><span class="emoji">🔑🔑</span><b>2 clés</b><span class="tag">2ᵉ clé −{{ $pricing['second_key_discount'] * 100 }} %</span></label>
                                </div>
                            </div>
                        </div>

                        <div class="q-block">
                            <div class="q-title"><span class="n">6</span>Protection <span class="opt">Optionnel</span></div>
                            <p class="q-help">Un mois de garantie est inclus pour chaque clé fabriquée.</p>
                            <div class="choice">
                                <input type="checkbox" name="warranty_extension" id="warranty" value="1" @checked($v('warranty_extension'))>
                                <label for="warranty"><span class="emoji">🛡️</span><span><b>Extension de garantie 1 an</b><small>Couvre les dysfonctionnements électroniques de chaque clé pendant un an.</small></span><span class="tag">+{{ $pricing['warranty_extension'] }} € / clé</span></label>
                            </div>
                        </div>

                        <div class="q-block">
                            <div class="q-title"><span class="n">7</span>Options d’intervention <span class="opt">Optionnel</span></div>
                            <div class="choice" style="margin-top:12px">
                                <input type="checkbox" name="emergency" id="emergency" value="1" @checked($v('emergency'))>
                                <label for="emergency"><span class="emoji">⚡</span><span><b>Intervention d’urgence</b><small>Week-end, soirée de 20h à 8h ou besoin d’un passage rapide.</small></span><span class="tag">+{{ $pricing['emergency'] }} €</span></label>
                            </div>
                        </div>

                        <div class="q-block">
                            <div class="q-title"><span class="n">8</span>Informations du véhicule</div>
                            <div class="fields" style="margin-top:14px">
                                <div class="field"><label for="plate">Plaque *</label><input id="plate" name="plate" value="{{ $v('plate') }}" placeholder="AB-123-CD" required></div>
                                <div class="field"><label for="brand_model">Marque / modèle *</label><input id="brand_model" name="brand_model" value="{{ $v('brand_model') }}" placeholder="Peugeot 308" required></div>
                                <div class="field"><label for="year">Année *</label><input id="year" name="year" type="number" min="1970" max="{{ now()->year + 1 }}" value="{{ $v('year') }}" placeholder="2018" required></div>
                            </div>
                        </div>

                        <div class="panel-actions">
                            <span></span>
                            <button type="button" class="btn btn-red btn-lg" data-next>Continuer vers la localisation →</button>
                        </div>
                    </div>

                    {{-- ============ ÉTAPE 2 : LOCALISATION ============ --}}
                    <div class="panel" data-panel="2">
                        <div class="q-block">
                            <div class="q-title"><span class="n">📍</span>Vos coordonnées</div>
                            <div class="fields two" style="margin-top:14px">
                                <div class="field"><label for="customer_name">Nom complet *</label><input id="customer_name" name="customer_name" value="{{ $v('customer_name') }}" autocomplete="name" required></div>
                                <div class="field"><label for="customer_phone">Téléphone *</label><input id="customer_phone" name="customer_phone" type="tel" value="{{ $v('customer_phone') }}" autocomplete="tel" required></div>
                                <div class="field full"><label for="customer_email">E-mail *</label><input id="customer_email" name="customer_email" type="email" value="{{ $v('customer_email') }}" autocomplete="email" required></div>
                            </div>
                        </div>
                        <div class="q-block">
                            <div class="q-title"><span class="n">🏠</span>Adresse d’intervention</div>
                            <div class="fields" style="margin-top:14px">
                                <div class="field full"><label for="address">Adresse *</label><input id="address" name="address" value="{{ $v('address') }}" autocomplete="street-address" required></div>
                                <div class="field"><label for="postal_code">Code postal *</label><input id="postal_code" name="postal_code" value="{{ $v('postal_code') }}" autocomplete="postal-code" required></div>
                                <div class="field" style="grid-column: span 2"><label for="city">Ville *</label><input id="city" name="city" value="{{ $v('city') }}" autocomplete="address-level2" required></div>
                                <div class="field full"><label for="notes">Précisions (facultatif)</label><textarea id="notes" name="notes" rows="3" placeholder="Parking, étage, disponibilités…">{{ $v('notes') }}</textarea></div>
                            </div>
                        </div>
                        <div class="panel-actions">
                            <button type="button" class="btn btn-outline" data-prev>← Retour</button>
                            <button type="button" class="btn btn-red btn-lg" data-next>Vérifier ma demande →</button>
                        </div>
                    </div>

                    {{-- ============ ÉTAPE 3 : CONFIRMATION ============ --}}
                    <div class="panel" data-panel="3">
                        <div class="q-block">
                            <div class="q-title"><span class="n">✓</span>Vérifiez votre demande</div>
                            <p class="q-help">Aucun paiement n’est demandé maintenant. Le règlement s’effectue après l’intervention, une fois la prestation validée.</p>
                            <div id="recap" class="grid grid-2" style="gap:8px 24px"></div>
                        </div>
                        <div class="q-block">
                            <label class="check-line">
                                <input type="checkbox" name="accept_terms" value="1" id="accept_terms" @checked($v('accept_terms'))>
                                <span>J’ai lu et j’accepte les <a href="{{ route('terms') }}" target="_blank" style="color:var(--red);font-weight:700">Conditions générales d’intervention</a>. *</span>
                            </label>
                        </div>
                        <div class="panel-actions">
                            <button type="button" class="btn btn-outline" data-prev>← Retour</button>
                            <button type="submit" class="btn btn-green btn-lg">Envoyer ma demande ✓</button>
                        </div>
                    </div>
                    <p class="error-text" id="step-error" role="alert" hidden></p>
                </div>

                <aside class="summary" aria-live="polite">
                    <div class="summary-head">🧾 Vos sélections</div>
                    <div class="summary-body">
                        <div class="summary-row"><span>Véhicule</span><span data-sum="vehicle_type">—</span></div>
                        <div class="summary-row"><span>Besoin</span><span data-sum="need">—</span></div>
                        <div class="summary-row"><span>Type de clé</span><span data-sum="key_type">—</span></div>
                        <div class="summary-row"><span>Clé souhaitée</span><span data-sum="desired_key">—</span></div>
                        <div class="summary-row"><span>Quantité</span><span data-sum="quantity">—</span></div>
                        <div class="summary-row"><span>Garantie 1 an</span><span data-sum="warranty_extension">Non</span></div>
                        <div class="summary-row"><span>Urgence</span><span data-sum="emergency">Non</span></div>
                        <div class="summary-total"><span class="muted">Prix estimé TTC</span><strong id="total">— €</strong></div>
                    </div>
                    <div class="summary-foot">
                        <span>✔ Aucun débit avant la fin de la prestation</span>
                        <span>✔ Garantie 1 mois incluse sur chaque clé</span>
                        <span>✔ Annulation gratuite avant le départ du technicien</span>
                        <span>✔ Techniciens certifiés, assurés RC Pro</span>
                    </div>
                </aside>
            </div>
        </form>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-head">
            <div class="eyebrow">Nos prestations</div>
            <h2>Toutes les solutions pour vos clés, sans passer par le garage</h2>
            <p class="muted">Un technicien équipé d’un atelier mobile complet se déplace là où se trouve votre véhicule.</p>
        </div>
        <div class="grid grid-4">
            @foreach ($services as $service)
                <a class="card" href="{{ route('services.show', $service) }}">
                    <div class="icon">{{ $service->icon }}</div>
                    <h3>{{ $service->title }}</h3>
                    <p class="muted" style="font-size:.92rem">{{ $service->excerpt }}</p>
                    <span class="price">À partir de {{ $service->price_from }} € TTC</span>
                </a>
            @endforeach
        </div>
    </div>
</section>

<section class="section white">
    <div class="container">
        <div class="section-head">
            <div class="eyebrow">Avis clients</div>
            <h2>Ils nous ont confié leurs clés</h2>
        </div>
        <div class="reviews">
            @foreach ($testimonials as $t)
                <div class="card review">
                    <div class="head">
                        <div class="avatar">{{ $t->initials() }}</div>
                        <div><b>{{ $t->name }}</b><div class="muted" style="font-size:.85rem">Client · {{ $t->city }}</div></div>
                        <div class="stars" style="margin-left:auto" aria-label="{{ $t->rating }} sur 5">{{ str_repeat('★', $t->rating) }}</div>
                    </div>
                    <p style="margin:0">{{ $t->content }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-head">
            <div class="eyebrow">Toutes marques</div>
            <h2>Plus de {{ $brands->flatten()->count() }} marques couvertes</h2>
        </div>
        @foreach (\App\Models\Brand::CATEGORIES as $cat => $label)
            @if (isset($brands[$cat]))
                <h3 class="center" style="font-size:1rem;margin:24px 0 12px">{{ $label }}</h3>
                <div class="brand-cloud">
                    @foreach ($brands[$cat] as $brand)
                        <a class="brand-chip" href="{{ route('brands.show', $brand) }}">{{ $brand->name }}</a>
                    @endforeach
                </div>
            @endif
        @endforeach
    </div>
</section>

<section class="section white">
    <div class="container prose" style="max-width:900px">
        <h2>{{ $settings['company_name'] }} — votre serrurier automobile mobile</h2>
        <p>{{ $settings['company_name'] }} réunit un réseau de techniciens spécialisés dans la reproduction de clés de voiture, le double de clé auto et la programmation de clés électroniques, directement à votre domicile ou sur votre lieu de travail.</p>
        <h3>Clé de voiture perdue ? Nous intervenons en urgence</h3>
        <p>En cas de perte totale, nous fabriquons une nouvelle clé sans l’originale, puis nous la programmons sur le calculateur du véhicule. Les anciennes clés peuvent être désactivées pour votre sécurité.</p>
        <h3>Double de clé et programmation</h3>
        <p>Clé simple, clé à transpondeur, télécommande ou carte mains libres : nos techniciens utilisent un matériel de diagnostic professionnel pour coder votre nouvelle clé en quelques minutes.</p>
        <h3>Des prix clairs</h3>
        <p>Le prix estimé s’affiche pendant votre demande et vous est confirmé avant le départ du technicien. Vous ne réglez qu’une fois la prestation terminée.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="cta-band">
            <div>
                <h2>Besoin d’une clé maintenant ?</h2>
                <p style="margin:6px 0 0;opacity:.9">Un conseiller vous répond 24h/24 et 7j/7.</p>
            </div>
            <a class="btn btn-lg" href="tel:{{ preg_replace('/\s+/', '', $settings['phone']) }}">📞 {{ $settings['phone'] }}</a>
        </div>
    </div>
</section>
@endsection

@push('scripts')
    <script>window.PRICING = @json($pricing);</script>
    <script src="{{ asset('js/configurator.js') }}?v=1"></script>
@endpush
