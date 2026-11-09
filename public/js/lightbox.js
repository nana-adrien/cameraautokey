/* Visionneuse photo partagée : tout bouton .gallery-item[data-full] ouvre #lightbox. */
(function () {
    const box = document.getElementById('lightbox');
    if (!box) return;
    const img = box.querySelector('img'), cap = box.querySelector('figcaption');
    document.querySelectorAll('.gallery-item[data-full]').forEach((item) => item.addEventListener('click', () => {
        img.src = item.dataset.full; img.alt = item.dataset.caption || ''; cap.textContent = item.dataset.caption || '';
        box.hidden = false;
    }));
    const close = () => { box.hidden = true; img.src = ''; };
    box.addEventListener('click', (e) => { if (e.target === box || e.target.closest('.lightbox-close')) close(); });
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && !box.hidden) close(); });
})();
