# CAMERAUTOKEY — site vitrine & dashboard admin (Laravel)

Site de serrurerie automobile mobile (reproduction, programmation et ouverture de clés)
avec configurateur de demande en 3 étapes et prix estimé en temps réel, plus un
dashboard d’administration complet.

## Démarrage

```bash
composer install
cp .env.example .env && php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
php artisan serve
```

- Site public : http://localhost:8000
- Dashboard : http://localhost:8000/admin
  (compte de développement créé par `DatabaseSeeder` — surchargeable avec `ADMIN_EMAIL` / `ADMIN_PASSWORD` dans `.env`).
  **Changez ce mot de passe avant toute mise en production.**

Aucun build front n’est nécessaire : le CSS et le JS sont dans `public/css` et `public/js`.

## Fonctionnalités

**Site public**
- Accueil : diaporama hero, barre d’étapes, formulaire en cartes (brouillon sauvegardé, géolocalisation et autocomplétion d’adresse via l’API Adresse), logos des marques, carrousel d’avis (Swiper), section SEO + FAQ. Configurateur (type de véhicule, besoin, type de clé, clé souhaitée, quantité avec −30 % sur la 2ᵉ clé, garantie, urgence, véhicule → coordonnées → récapitulatif), services, avis, marques, texte SEO.
- Pages services (`/service/{slug}`) et marques (`/service/reproduction-cle-{marque}`), avec méga-menu.
- Suivi de demande par référence + e-mail, page de confirmation par URL signée.
- À propos, Professionnels, Devenir intervenant, FAQ, Contact, Mentions légales, CGI.

**Dashboard admin** (`/admin`)
- Statistiques, graphique des demandes sur 14 jours, répartition par statut.
- Demandes : recherche, filtre par statut, attribution d’un technicien, changement de statut et de prix, suppression.
- Messages (contact, candidatures intervenant, demandes pro) avec marquage lu/non lu.
- CRUD : services, marques, techniciens, avis clients, FAQ.
- Paramètres : nom, téléphones, e-mail, WhatsApp, textes de l’accueil.

## Images provisoires à remplacer

Toutes les images sont des visuels temporaires (SVG) à remplacer par les photos du client,
en gardant le même nom de fichier (ou en adaptant le chemin dans la vue) :

| Fichier | Où | Format conseillé |
|---|---|---|
| `public/images/logo.svg` / `logo-white.svg` | En-tête, menu mobile | SVG ou PNG transparent, ~190×40 |
| `public/images/placeholders/hero-1.svg` → `hero-3.svg` | Diaporama de l’accueil (fondu 18 s) | JPG/WebP 1920×800 |
| `public/images/placeholders/page-hero.svg` | Bandeau des pages internes | JPG/WebP 1920×600 |
| `public/images/placeholders/vehicle-*.svg` | Cartes « Type de véhicule » | PNG transparent 160×80 |
| `public/images/placeholders/brand-logos.svg` | Bandeau des logos de marques | WebP ~2000 px de large |

## Tarification

La grille est dans `config/pricing.php`. Le prix est affiché en direct par
`public/js/configurator.js` mais **toujours recalculé côté serveur** par
`App\Services\PriceCalculator` lors de l’enregistrement.

## À compléter avant production
- Mentions légales (SIRET, hébergeur…) et CGI à faire valider juridiquement.
- Envoi d’e-mails de confirmation (configurer `MAIL_*`).
- Paiement en ligne (ex. Stripe) si souhaité.
