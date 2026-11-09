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
- Accueil : hero, configurateur (type de véhicule, besoin, type de clé, clé souhaitée, quantité avec −30 % sur la 2ᵉ clé, garantie, urgence, véhicule → coordonnées → récapitulatif), services, avis, marques, texte SEO.
- Pages services (`/service/{slug}`) et marques (`/service/reproduction-cle-{marque}`), avec méga-menu.
- Suivi de demande par référence + e-mail, page de confirmation par URL signée.
- À propos, Professionnels, Devenir intervenant, FAQ, Contact, Mentions légales, CGI.

**Dashboard admin** (`/admin`)
- Statistiques, graphique des demandes sur 14 jours, répartition par statut.
- Demandes : recherche, filtre par statut, attribution d’un technicien, changement de statut et de prix, suppression.
- Messages (contact, candidatures intervenant, demandes pro) avec marquage lu/non lu.
- CRUD : services, marques, techniciens, avis clients, FAQ.
- Paramètres : nom, téléphones, e-mail, WhatsApp, textes de l’accueil.

## Tarification

La grille est dans `config/pricing.php`. Le prix est affiché en direct par
`public/js/configurator.js` mais **toujours recalculé côté serveur** par
`App\Services\PriceCalculator` lors de l’enregistrement.

## À compléter avant production
- Mentions légales (SIRET, hébergeur…) et CGI à faire valider juridiquement.
- Envoi d’e-mails de confirmation (configurer `MAIL_*`).
- Paiement en ligne (ex. Stripe) si souhaité.
