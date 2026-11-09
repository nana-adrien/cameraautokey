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

## Images

**Photos du client** (`public/images/photos/`, format WebP, miniatures de galerie dans `photos/thumbs/`) :

| Fichier | Utilisation |
|---|---|
| `cles-toyota-tacoma.webp`, `taille-cle-atelier.webp`, `cles-toyota-interieur.webp`, `taille-cle-condor.webp`, `cles-toyota-tundra-blanc.webp` | Diaporama de l’accueil (pleine largeur, rotation toutes les 6 s — liste dans `pages/home.blade.php`) ; `taille-cle-atelier.webp` aussi dans la section « Refonte de clés » |
| `equipe-camer-auto-key.webp` | Bandeau et photo de la page À propos |
| `cles-toyota-tundra-blanc.webp`, `cles-toyota-interieur.webp`, `atelier-cour.webp`, `vidange-moteur.webp`… | Bandeaux des autres pages |
| 12 photos | Galerie « Nos réalisations » de l’accueil |

Pour remplacer une photo : déposer la nouvelle image (paysage, au moins 1600 px de large) sous le même nom,
et régénérer sa miniature (`cwebp -q 80 -resize 800 0 photo.webp -o thumbs/photo.webp`).

**Logos des deux entreprises du client** (`public/images/`) :

| Fichier | Entreprise | Utilisation |
|---|---|---|
| `logo-camer-auto-key.png` (+ `-160.png`, `favicon-64.png`) | CAMER AUTO KEY — vente et reproduction de clés | En-tête, menu mobile, pied de page, onglet du navigateur |
| `logo-3k-auto-services.webp` | 3K AUTO SERVICES SARL — garage | Page `/garage-3k-auto-services`, bandeau de l’accueil, pied de page |

Le logo 3K est découpé dans l’affiche de recrutement (188 px) : le remplacer par le fichier original si disponible.

**Pictogrammes des véhicules** : `public/images/vehicles/{voiture,camion,moto}.svg` — dessins originaux (SVG, couleurs du site).

Il ne reste plus d’image provisoire sur le site.

## Localisation : Cameroun

- Monnaie : **FCFA** (`config/pricing.php`, formatage via `App\Support\Money::fcfa()`), prix arrondis à 500 FCFA.
- Fuseau horaire : `Africa/Douala` (modifiable via `APP_TIMEZONE`).
- Coordonnées par défaut (`app/Models/Setting.php`, modifiables dans l’admin → Paramètres) : atelier à Akwa (Douala),
  clés : **+237 694 02 85 96** (enseigne), garage 3K : **+237 678 12 00 47**, e-mail **camerautokeyy@gmail.com**
  (repris de l’affiche du client — à confirmer), horaires.
- Adresse d’intervention : rue ou repère + **quartier** + ville (pas de code postal).
- Recherche d’adresse et « Utiliser ma position » : Photon / OpenStreetMap, limité au Cameroun ; la position GPS
  est aussi ajoutée en lien Google Maps dans les précisions de la demande.
- Paiement affiché : Orange Money, MTN MoMo, espèces, carte.
- Mentions légales : RCCM, NIU et loi n° 2024/017 sur les données personnelles (à compléter).

## Street View (page Contact et page Garage)

Réglages dans **`config/streetview.php`**, une entrée par adresse (`shop` = boutique d’Akwa, `garage` = 3K à Ndogsimbi) :

- `enabled` : affiche ou masque le bouton « Street View » (bascule avec la carte).
- `test` : affiche l’étiquette « Vue de test ». La boutique utilise actuellement un **point de test**
  (photo 360° publique près du boulevard de la Liberté, à ~700 m) : Google n’a pas de Street View devant les deux adresses.
- `embed_url` : méthode recommandée quand la vraie vue existe — sur Google Maps, ouvrir la vue 360°,
  *Partager → Intégrer une carte*, copier l’adresse du `src="…"`, puis mettre `test` à `false`.
- Sinon `lat`, `lng`, `heading` (0 = nord, 90 = est…) et `pitch` : Google affiche le panorama le plus proche.

Le panorama ne se charge qu’au clic sur « Street View ». Pour obtenir une vraie vue, le client peut publier
une photo 360° devant sa boutique sur sa fiche Google Maps.

## Logos des marques

Les logos sont dans `public/images/brands/{slug-de-la-marque}.svg` (source : Simple Icons, licence CC0,
voir `SOURCE.txt`). Une marque sans fichier logo s’affiche avec son nom en style « wordmark ».
Pour ajouter ou remplacer un logo, déposer un SVG portant le slug de la marque (visible dans l’admin → Marques).
Logos encore absents : Alfa Romeo, Cupra, Jaguar, Land Rover, Lexus, Mercedes, Harley-Davidson, Kawasaki, Lancia.

## Tarification

La grille est dans `config/pricing.php`. Le prix est affiché en direct par
`public/js/configurator.js` mais **toujours recalculé côté serveur** par
`App\Services\PriceCalculator` lors de l’enregistrement.

## À compléter avant production
- Mentions légales (SIRET, hébergeur…) et CGI à faire valider juridiquement.
- Envoi d’e-mails de confirmation (configurer `MAIL_*`).
- Paiement en ligne (ex. Stripe) si souhaité.
