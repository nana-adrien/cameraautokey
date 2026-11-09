/*
 * Formulaire de demande CAMERAUTOKEY (accueil).
 * - états visuels des cartes (sélection, interrupteurs)
 * - 3 étapes avec validation par section
 * - récapitulatif « Vos sélections »
 * - brouillon conservé dans le navigateur (bouton « Effacer mes informations »)
 * - géolocalisation + autocomplétion d'adresse limitée au Cameroun (Photon / OpenStreetMap)
 * Le prix est calculé côté serveur uniquement (App\Services\PriceCalculator).
 */
(function () {
    const form = document.getElementById('order-form');
    if (!form) return;

    const P = window.PRICING || {};
    const DRAFT_KEY = 'cak-order-draft';
    const panels = form.querySelectorAll('.step-panel');
    const tabs = document.querySelectorAll('.step-bar .step-tab');
    const btnNext = document.getElementById('btn-next');
    const btnBack = document.getElementById('btn-back');
    const labels = { 1: 'Continuer vers la localisation →', 2: 'Vérifier ma demande →', 3: 'Envoyer ma demande ✓' };
    let current = parseInt(form.dataset.startStep || '1', 10);

    const $ = (sel, root = form) => root.querySelector(sel);
    const val = (name) => {
        const el = $(`[name="${name}"]:checked`) || $(`[name="${name}"]:not([type=radio]):not([type=checkbox])`);
        return el ? el.value.trim() : '';
    };
    const checked = (name) => !!$(`[name="${name}"]:checked`);
    const label = (group, key) => (P[group] && P[group][key] ? P[group][key].label : '');

    /* ---------- États visuels ---------- */
    function syncSelection() {
        form.querySelectorAll('[data-group]').forEach((card) => {
            card.classList.toggle('sel', card.querySelector('input').checked);
        });
        form.querySelectorAll('[data-toggle]').forEach((row) => {
            row.classList.toggle('on', row.querySelector('input').checked);
        });
    }

    /* ---------- Récapitulatif ---------- */
    function recapItems() {
        const items = [];
        const add = (l, v) => v && items.push([l, v]);
        add('Véhicule', label('vehicle_types', val('vehicle_type')));
        add('Besoin', label('needs', val('need')));
        add('Type de clé', label('key_types', val('key_type')));
        add('Clé souhaitée', label('desired_keys', val('desired_key')));
        add('Quantité', val('quantity') === '2' ? '2 clés (2ᵉ clé −30 %)' : (val('quantity') ? '1 clé' : ''));
        if (checked('warranty_extension')) add('Protection', 'Extension de garantie 1 an');
        if (checked('emergency')) add('Option', 'Intervention d’urgence');
        const veh = [val('brand_model'), val('year'), val('plate').toUpperCase()].filter(Boolean).join(' · ');
        add('Véhicule renseigné', veh);
        const addr = [val('address'), val('district'), val('city')].filter(Boolean).join(', ');
        add('Adresse', addr);
        return items;
    }

    function renderRecap() {
        const items = recapItems();
        const box = document.getElementById('recap');
        document.getElementById('rc-count').textContent = items.length;
        box.innerHTML = '';
        if (!items.length) {
            box.innerHTML = '<div class="recap-empty"><span class="recap-empty-icon">🔑</span>Sélectionnez vos options<br>pour voir votre récapitulatif</div>';
            return;
        }
        items.forEach(([l, v]) => {
            const row = document.createElement('div');
            row.className = 'ri';
            const a = document.createElement('div'); a.className = 'ri-l'; a.textContent = l;
            const b = document.createElement('div'); b.className = 'ri-v'; b.textContent = v;
            row.append(a, b);
            box.appendChild(row);
        });
    }

    function renderValidation() {
        const fill = (id, rows) => {
            const grid = document.getElementById(id);
            grid.innerHTML = '';
            rows.forEach(([l, v, full, plate]) => {
                const cell = document.createElement('div');
                if (full) cell.className = 'vinfo-full';
                const a = document.createElement('div'); a.className = 'vinfo-lbl'; a.textContent = l;
                const b = document.createElement('div'); b.className = 'vinfo-val';
                if (plate) { const s = document.createElement('span'); s.className = 'vinfo-plate'; s.textContent = v; b.appendChild(s); } else { b.textContent = v || '—'; }
                cell.append(a, b);
                grid.appendChild(cell);
            });
        };
        fill('vinfo-vehicle', [
            ['Plaque', val('plate').toUpperCase(), false, true],
            ['Marque / modèle', `${val('brand_model')} (${val('year')})`],
            ['Type de véhicule', label('vehicle_types', val('vehicle_type'))],
            ['Besoin', label('needs', val('need'))],
            ['Type de clé', label('key_types', val('key_type'))],
            ['Clé souhaitée', `${label('desired_keys', val('desired_key'))} × ${val('quantity') || 1}`],
            ['Options', [checked('warranty_extension') && 'Garantie 1 an', checked('emergency') && 'Urgence'].filter(Boolean).join(' · ') || 'Aucune', true],
        ]);
        fill('vinfo-contact', [
            ['Adresse', `${val('address')}, ${val('district')}, ${val('city')}`, true],
            ['Nom', val('customer_name')],
            ['Téléphone', val('customer_phone')],
            ['E-mail', val('customer_email'), true],
            ['Précisions', val('notes'), true],
        ]);
    }

    /* ---------- Validation par section ---------- */
    const rules = {
        1: [
            ['vehicle_type', () => !!val('vehicle_type'), 'Choisissez le type de véhicule.'],
            ['need', () => !!val('need'), 'Indiquez votre besoin.'],
            ['key_type', () => !!val('key_type'), 'Choisissez le type de clé de votre véhicule.'],
            ['vehicle_info', () => val('plate') && val('brand_model') && /^\d{4}$/.test(val('year')), 'Renseignez la plaque, la marque / le modèle et l’année (4 chiffres).'],
        ],
        2: [
            ['address', () => val('address') && val('district') && val('city'), 'Renseignez l’adresse ou un repère, le quartier et la ville.'],
            ['contact', () => val('customer_name') && val('customer_phone') && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val('customer_email')), 'Renseignez votre nom, votre téléphone et un e-mail valide.'],
        ],
    };

    function validate(step) {
        let firstError = null;
        (rules[step] || []).forEach(([section, ok, msg]) => {
            const sc = form.querySelector(`[data-section="${section}"]`);
            const err = sc.querySelector('.section-error');
            const valid = !!ok();
            sc.classList.toggle('has-error', !valid);
            err.textContent = valid ? '' : msg;
            err.classList.toggle('show', !valid);
            if (!valid && !firstError) firstError = sc;
        });
        const terms = document.getElementById('terms-box');
        const termsOk = checked('accept_terms');
        terms.classList.toggle('err', !termsOk);
        if (!termsOk && !firstError) firstError = terms;
        if (firstError) firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        return !firstError;
    }

    /* ---------- Navigation entre étapes ---------- */
    function show(step, scroll = true) {
        current = step;
        panels.forEach((p) => p.classList.toggle('vis', +p.dataset.step === step));
        tabs.forEach((t) => {
            const n = +t.dataset.tab;
            t.classList.toggle('active', n === step);
            t.classList.toggle('done', n < step);
            t.classList.toggle('step-future', n > step);
        });
        btnNext.textContent = labels[step];
        btnBack.hidden = step === 1;
        if (step === 3) renderValidation();
        if (scroll) document.getElementById('commande').scrollIntoView({ behavior: 'smooth' });
    }

    btnNext.addEventListener('click', () => {
        if (current < 3) {
            if (validate(current)) show(current + 1);
            return;
        }
        if (validate(1) && validate(2)) {
            btnNext.disabled = true;
            btnNext.textContent = 'Envoi en cours…';
            try { localStorage.removeItem(DRAFT_KEY); } catch (e) {}
            form.submit();
        }
    });
    btnBack.addEventListener('click', () => show(current - 1));
    tabs.forEach((t) => t.addEventListener('click', () => { if (t.classList.contains('done')) show(+t.dataset.tab); }));
    form.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' && e.target.tagName === 'INPUT') { e.preventDefault(); btnNext.click(); }
    });

    /* ---------- Brouillon ---------- */
    function saveDraft() {
        const data = {};
        new FormData(form).forEach((v, k) => { if (k !== '_token') data[k] = v; });
        try { localStorage.setItem(DRAFT_KEY, JSON.stringify(data)); } catch (e) {}
    }
    function restoreDraft() {
        if (form.querySelector('.flash-error')) return; // les valeurs du serveur priment
        let data;
        try { data = JSON.parse(localStorage.getItem(DRAFT_KEY) || 'null'); } catch (e) { data = null; }
        if (!data) return;
        Object.entries(data).forEach(([k, v]) => {
            form.querySelectorAll(`[name="${k}"]`).forEach((el) => {
                if (el.type === 'radio' || el.type === 'checkbox') el.checked = el.value === v;
                else el.value = v;
            });
        });
    }
    document.getElementById('btn-clear').addEventListener('click', () => {
        try { localStorage.removeItem(DRAFT_KEY); } catch (e) {}
        form.querySelectorAll('input:not([type=hidden]), textarea').forEach((el) => {
            if (el.type === 'radio' || el.type === 'checkbox') el.checked = el.defaultChecked && el.name === 'quantity';
            else el.value = '';
        });
        form.querySelector('[name="desired_key"][value="generic"]').checked = true;
        form.querySelector('[name="quantity"][value="1"]').checked = true;
        form.querySelectorAll('.has-error').forEach((s) => s.classList.remove('has-error'));
        form.querySelectorAll('.section-error').forEach((s) => s.classList.remove('show'));
        refresh();
        show(1);
    });

    /* ---------- Adresse : géolocalisation + autocomplétion ---------- */
    const gpsMsg = document.getElementById('gps-msg');
    // Photon (OpenStreetMap) : recherche limitée au Cameroun (boîte englobante + code pays).
    const PHOTON = 'https://photon.komoot.io';
    const CM_BBOX = '8.4,1.6,16.2,13.1';
    const isCameroon = (p) => (p.countrycode || '').toUpperCase() === 'CM';
    const streetOf = (p) => [p.housenumber, p.street].filter(Boolean).join(' ') || p.name || '';
    const districtOf = (p) => p.district || p.locality || p.suburb || '';
    const cityOf = (p) => p.city || p.town || p.village || p.county || '';
    const setAddress = (p) => {
        form.address.value = streetOf(p);
        if (districtOf(p)) form.district.value = districtOf(p);
        if (cityOf(p)) form.city.value = cityOf(p);
        refresh(); saveDraft();
    };

    document.getElementById('geo-btn').addEventListener('click', () => {
        gpsMsg.className = 'gps-status-msg';
        if (!navigator.geolocation) { gpsMsg.textContent = 'La géolocalisation n’est pas disponible sur cet appareil.'; gpsMsg.classList.add('err'); return; }
        gpsMsg.textContent = 'Recherche de votre position…'; gpsMsg.classList.add('ok');
        navigator.geolocation.getCurrentPosition(async (pos) => {
            const { latitude, longitude } = pos.coords;
            form.notes.value = [form.notes.value.trim(), `Position GPS : https://maps.google.com/?q=${latitude.toFixed(6)},${longitude.toFixed(6)}`].filter(Boolean).join('\n');
            try {
                const r = await fetch(`${PHOTON}/reverse?lon=${longitude}&lat=${latitude}&lang=fr`);
                const f = ((await r.json()).features || [])[0];
                if (!f || !isCameroon(f.properties)) throw new Error();
                setAddress(f.properties);
                gpsMsg.textContent = '✓ Position détectée et ajoutée aux précisions. Vérifiez la rue, le quartier et la ville.';
            } catch (e) {
                refresh(); saveDraft();
                gpsMsg.textContent = '✓ Position GPS ajoutée aux précisions. Complétez la rue ou un repère, le quartier et la ville.';
            }
        }, () => {
            gpsMsg.className = 'gps-status-msg err';
            gpsMsg.textContent = 'Position refusée ou indisponible. Merci de saisir l’adresse ci-dessous.';
        }, { timeout: 10000 });
    });

    const acList = document.getElementById('ac-list');
    let acTimer;
    form.address.addEventListener('input', () => {
        clearTimeout(acTimer);
        const q = form.address.value.trim();
        if (q.length < 4) { acList.classList.remove('open'); return; }
        acTimer = setTimeout(async () => {
            try {
                const r = await fetch(`${PHOTON}/api/?q=${encodeURIComponent(q)}&limit=6&lang=fr&bbox=${CM_BBOX}`);
                const features = ((await r.json()).features || []).filter((f) => isCameroon(f.properties)).slice(0, 5);
                acList.innerHTML = '';
                features.forEach((f) => {
                    const p = f.properties;
                    const item = document.createElement('div');
                    item.className = 'ac-item';
                    item.textContent = streetOf(p);
                    const small = document.createElement('small');
                    small.textContent = [districtOf(p), cityOf(p)].filter(Boolean).join(', ');
                    item.appendChild(small);
                    item.addEventListener('mousedown', (e) => { e.preventDefault(); setAddress(p); acList.classList.remove('open'); });
                    acList.appendChild(item);
                });
                acList.classList.toggle('open', features.length > 0);
            } catch (e) { acList.classList.remove('open'); }
        }, 300);
    });
    form.address.addEventListener('blur', () => acList.classList.remove('open'));

    /* ---------- Initialisation ---------- */
    function refresh() { syncSelection(); renderRecap(); }
    form.addEventListener('change', () => { refresh(); saveDraft(); });
    form.addEventListener('input', () => { renderRecap(); saveDraft(); });

    restoreDraft();
    refresh();
    show(current, current !== 1);
})();
