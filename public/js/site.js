/* Scripts communs aux deux sites (clés + garage). */
(function () {
    /* Menu mobile plein écran */
    const btn = document.getElementById('menu-btn'), nav = document.getElementById('nav-links');
    if (btn && nav) {
        const toggle = (open) => {
            nav.classList.toggle('show', open); btn.classList.toggle('active', open);
            document.body.classList.toggle('nav-open', open); btn.setAttribute('aria-expanded', open);
        };
        btn.addEventListener('click', () => toggle(!nav.classList.contains('show')));
        const close = nav.querySelector('[data-close-menu]');
        if (close) close.addEventListener('click', () => toggle(false));
        nav.querySelectorAll('.has-sub > a').forEach((a) => a.addEventListener('click', (e) => {
            if (window.innerWidth > 900) return;
            e.preventDefault(); a.parentElement.classList.toggle('active-mobile');
        }));
        nav.querySelectorAll('a[href*="#"]').forEach((a) => a.addEventListener('click', () => toggle(false)));
    }

    /* Logo de l'en-tête : bascule vers le logo du garage quand sa section passe au milieu de l'écran. */
    const zone = document.querySelector('[data-brand-switch="garage"]');
    const roll = document.querySelector('.brand-roll');
    if (zone && roll && 'IntersectionObserver' in window) {
        const faces = roll.querySelectorAll('.brand-face');
        new IntersectionObserver(([entry]) => {
            const garage = entry.isIntersecting;
            document.body.classList.toggle('brand-garage', garage);
            faces[0].setAttribute('aria-hidden', garage ? 'true' : 'false');
            faces[1].setAttribute('aria-hidden', garage ? 'false' : 'true');
        }, { rootMargin: '-45% 0px -45% 0px' }).observe(zone);
    }

    /* Carrousels de photos (.hero-slider) : fondu toutes les 6 s */
    document.querySelectorAll('.hero-slider').forEach((slider) => {
        const slides = slider.querySelectorAll('.hero-slide');
        if (slides.length < 2) return;
        let i = [...slides].findIndex((s) => s.classList.contains('active'));
        if (i < 0) { i = 0; slides[0].classList.add('active'); }
        setInterval(() => {
            slides[i].classList.remove('active');
            i = (i + 1) % slides.length;
            slides[i].classList.add('active');
        }, 6000);
    });
})();
