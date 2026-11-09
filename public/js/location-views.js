/* Bascule « Carte / Street View » des cartes de localisation.
 * Le panorama Street View n'est chargé qu'au premier clic (attribut data-src). */
(function () {
    document.querySelectorAll('[data-view-target]').forEach((button) => {
        button.addEventListener('click', () => {
            const box = document.getElementById(button.dataset.viewTarget);
            if (!box) return;
            const view = button.dataset.view;
            box.querySelectorAll('[data-view-pane]').forEach((pane) => {
                const show = pane.dataset.viewPane === view;
                if (show && pane.dataset.src && !pane.src) pane.src = pane.dataset.src;
                pane.hidden = !show;
            });
            button.parentElement.querySelectorAll('[data-view-target]').forEach((b) => {
                const active = b === button;
                b.classList.toggle('active', active);
                b.setAttribute('aria-pressed', active ? 'true' : 'false');
            });
        });
    });
})();
