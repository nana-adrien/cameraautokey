<?php

/*
|--------------------------------------------------------------------------
| Référencement (SEO)
|--------------------------------------------------------------------------
| 'indexing' : le site peut-il apparaître dans Google ?
|   - false par défaut hors production (version de test, previews Vercel) :
|     balise <meta name="robots" content="noindex"> + en-tête X-Robots-Tag + robots.txt fermé.
|   - true en production (APP_ENV=production) ou en forçant SEO_INDEXING=true.
*/

return [
    'indexing' => (bool) env('SEO_INDEXING', env('APP_ENV') === 'production'),

    // Images d'aperçu (WhatsApp, Facebook…) — 1200 × 630, dans public/
    'og_image' => 'images/og/camer-auto-key.jpg',
    'og_image_garage' => 'images/og/3k-auto-services.jpg',

    // Région administrative (ISO 3166-2) pour les balises géographiques : Littoral
    'region' => 'CM-LT',
];
