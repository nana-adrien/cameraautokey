/*
 * Mesure d'audience CAMERAUTOKEY — first-party, sans dépendance externe.
 * - temps passé par page (page_leave)
 * - formulaires génériques : début, envoi, abandon
 * - le formulaire de demande est suivi par configurator.js (étapes + envoi)
 */
(function () {
    'use strict';

    const cfg = window.__CAK_ANALYTICS;

    if (!cfg || !cfg.visit || !cfg.csrf) {
        return;
    }

    const endpoint = '/_track';
    let visibleSince = document.visibilityState === 'visible' ? Date.now() : null;
    let accumulated = 0;

    function send(event, data) {
        const body = new FormData();
        body.append('_token', cfg.csrf);
        body.append('visit', cfg.visit);

        if (cfg.pageView) {
            body.append('page_view', cfg.pageView);
        }

        body.append('event', event);

        Object.keys(data || {}).forEach(function (key) {
            const value = data[key];

            if (value !== null && value !== undefined && value !== '') {
                body.append(key, value);
            }
        });

        try {
            if (navigator.sendBeacon && navigator.sendBeacon(endpoint, body)) {
                return;
            }
        } catch (e) {
            // sendBeacon indisponible : on passe au fetch ci-dessous.
        }

        fetch(endpoint, {
            method: 'POST',
            body: body,
            keepalive: true,
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
        }).catch(function () {});
    }

    // Exposé pour configurator.js (étapes et envoi du formulaire de demande).
    window.cakTrack = send;

    function flushDuration() {
        if (visibleSince === null) {
            return;
        }

        accumulated += Date.now() - visibleSince;
        visibleSince = null;

        if (accumulated >= 500) {
            send('page_leave', { duration_ms: Math.min(accumulated, 3600000) });
        }
    }

    document.addEventListener('visibilitychange', function () {
        if (document.visibilityState === 'hidden') {
            flushDuration();
        } else {
            visibleSince = Date.now();
        }
    });

    window.addEventListener('pagehide', flushDuration);

    document.querySelectorAll('form[data-track-form]').forEach(function (form) {
        const name = form.dataset.trackForm;

        // Le formulaire de demande est géré par configurator.js (étapes multiples).
        if (!name || name === 'order') {
            return;
        }

        let started = false;
        let submitted = false;
        let abandoned = false;

        const start = function () {
            if (started) {
                return;
            }

            started = true;
            send('form_start', { form: name });
        };

        form.addEventListener('input', start, { passive: true });
        form.addEventListener('change', start, { passive: true });
        form.addEventListener('submit', function () {
            submitted = true;
            send('form_submit', { form: name });
        });

        window.addEventListener('pagehide', function () {
            if (!started || submitted || abandoned) {
                return;
            }

            abandoned = true;
            send('form_abandon', { form: name });
        });
    });
})();
