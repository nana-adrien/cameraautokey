/*
 * Configurateur de demande CAMERAUTOKEY.
 * Gère les 3 étapes du formulaire et le calcul du prix estimé en direct.
 * Le prix réel est toujours recalculé côté serveur (App\Services\PriceCalculator).
 */
(function () {
    const form = document.getElementById('order-form');
    if (!form) return;

    const P = window.PRICING;
    const panels = form.querySelectorAll('[data-panel]');
    const progressSteps = document.querySelectorAll('#progress .p-step');
    const errorBox = document.getElementById('step-error');
    let current = parseInt(form.dataset.startStep || '1', 10);

    const value = (name) => {
        const el = form.querySelector(`[name="${name}"]:checked`) || form.querySelector(`[name="${name}"]:not([type=radio]):not([type=checkbox])`);
        return el ? el.value.trim() : '';
    };
    const checked = (name) => !!form.querySelector(`[name="${name}"]:checked`);
    const labelOf = (group, key) => (P[group] && P[group][key] ? P[group][key].label : '—');
    const euro = (n) => `${Math.round(n).toLocaleString('fr-FR')} €`;

    function estimate() {
        const v = value('vehicle_type'), n = value('need'), k = value('key_type'), d = value('desired_key');
        if (!v || !n || !k || !d) return null;
        const unit = (P.needs[n].base + P.key_types[k].extra + P.desired_keys[d].extra) * P.vehicle_types[v].multiplier;
        const qty = value('quantity') === '2' ? 2 : 1;
        let total = unit + (qty === 2 ? unit * (1 - P.second_key_discount) : 0);
        if (checked('warranty_extension')) total += P.warranty_extension * qty;
        if (checked('emergency')) total += P.emergency;
        return Math.round(total);
    }

    function refreshSummary() {
        const set = (key, text) => { const el = document.querySelector(`[data-sum="${key}"]`); if (el) el.textContent = text; };
        set('vehicle_type', labelOf('vehicle_types', value('vehicle_type')));
        set('need', labelOf('needs', value('need')));
        set('key_type', labelOf('key_types', value('key_type')));
        set('desired_key', labelOf('desired_keys', value('desired_key')));
        set('quantity', value('quantity') === '2' ? '2 clés' : '1 clé');
        set('warranty_extension', checked('warranty_extension') ? 'Oui' : 'Non');
        set('emergency', checked('emergency') ? 'Oui' : 'Non');
        const total = estimate();
        document.getElementById('total').textContent = total === null ? '— €' : euro(total);
    }

    function refreshRecap() {
        const rows = [
            ['Véhicule', `${value('brand_model')} (${value('year')}) — ${value('plate').toUpperCase()}`],
            ['Besoin', labelOf('needs', value('need'))],
            ['Clé', `${labelOf('key_types', value('key_type'))} · ${labelOf('desired_keys', value('desired_key'))}`],
            ['Quantité', value('quantity') === '2' ? '2 clés' : '1 clé'],
            ['Client', `${value('customer_name')} · ${value('customer_phone')}`],
            ['E-mail', value('customer_email')],
            ['Adresse', `${value('address')}, ${value('postal_code')} ${value('city')}`],
            ['Prix estimé', euro(estimate() || 0)],
        ];
        const recap = document.getElementById('recap');
        recap.innerHTML = '';
        rows.forEach(([k, val]) => {
            const div = document.createElement('div');
            div.className = 'summary-row';
            const a = document.createElement('span'); a.textContent = k;
            const b = document.createElement('span'); b.textContent = val;
            div.append(a, b);
            recap.appendChild(div);
        });
    }

    const required = {
        1: [['vehicle_type', 'le type de véhicule'], ['need', 'votre besoin'], ['key_type', 'le type de clé'], ['desired_key', 'la clé souhaitée'], ['plate', 'la plaque'], ['brand_model', 'la marque / le modèle'], ['year', 'l’année']],
        2: [['customer_name', 'votre nom'], ['customer_phone', 'votre téléphone'], ['customer_email', 'votre e-mail'], ['address', 'l’adresse'], ['postal_code', 'le code postal'], ['city', 'la ville']],
    };

    function validate(step) {
        const missing = (required[step] || []).filter(([name]) => !value(name));
        if (missing.length) return `Merci de renseigner : ${missing.map(([, l]) => l).join(', ')}.`;
        if (step === 2 && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value('customer_email'))) return 'Merci de saisir une adresse e-mail valide.';
        return '';
    }

    function show(step) {
        current = step;
        panels.forEach((p) => p.classList.toggle('active', +p.dataset.panel === step));
        progressSteps.forEach((s) => {
            const n = +s.dataset.step;
            s.classList.toggle('active', n === step);
            s.classList.toggle('done', n < step);
        });
        errorBox.hidden = true;
        if (step === 3) refreshRecap();
    }

    form.addEventListener('click', (e) => {
        if (e.target.closest('[data-next]')) {
            const err = validate(current);
            if (err) { errorBox.textContent = err; errorBox.hidden = false; return; }
            show(current + 1);
            document.getElementById('commande').scrollIntoView({ behavior: 'smooth' });
        }
        if (e.target.closest('[data-prev]')) {
            show(current - 1);
            document.getElementById('commande').scrollIntoView({ behavior: 'smooth' });
        }
    });

    form.addEventListener('submit', (e) => {
        if (!checked('accept_terms')) {
            e.preventDefault();
            errorBox.textContent = 'Vous devez accepter les conditions générales d’intervention.';
            errorBox.hidden = false;
        }
    });

    form.addEventListener('change', refreshSummary);
    show(current);
    refreshSummary();
})();
