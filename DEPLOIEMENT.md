# Déploiement de la version TEST sur Vercel

Le site Laravel tourne sur Vercel grâce au runtime communautaire **vercel-php@0.9.0** (PHP 8.5,
extensions `pdo_sqlite` et `pdo_pgsql` incluses). Les fichiers concernés :

| Fichier | Rôle |
|---|---|
| `vercel.json` | Runtime PHP, routes (fichiers de `public/` servis directement, le reste vers Laravel), en-têtes de sécurité, `noindex` |
| `api/index.php` | Point d’entrée : redirige caches et vues vers `/tmp`, vérifie les secrets, crée la base de démonstration, puis lance `public/index.php` |
| `.vercelignore` | Exclut `vendor/` (réinstallé par Composer sur Vercel), `.env`, bases SQLite locales, journaux… |

## 1. Variables à définir dans Vercel (une seule fois)

Uniquement pour l’environnement **Preview**, dans le tableau de bord (*Settings → Environment Variables*)
ou en ligne de commande depuis la racine du projet :

```bash
vercel link
```

```bash
php artisan key:generate --show
```

```bash
vercel env add APP_KEY preview
```

```bash
vercel env add ADMIN_PASSWORD preview
```

- `APP_KEY` : collez la clé affichée par `key:generate --show` (une clé **différente** de celle du `.env` local).
- `ADMIN_PASSWORD` : mot de passe du compte admin de test (long et unique). Facultatif : `ADMIN_EMAIL`.

Sans ces deux variables, le site affiche « Configuration incomplète » au lieu de démarrer.

## 2. Lancer un déploiement de test

```bash
vercel
```

`vercel` (sans `--prod`) crée un déploiement **Preview** avec une URL du type `https://cameraautokey-xxxx.vercel.app`.

## 3. Comportement de la version test

- **Base de données** : SQLite dans `/tmp`, créée et remplie avec les données de démonstration au premier
  appel de chaque instance. Les demandes et modifications **disparaissent** quand Vercel recycle l’instance.
- **Données persistantes** (optionnel) : ajoutez dans Vercel `DB_CONNECTION=pgsql` et `DB_URL=postgresql://…`
  (Supabase, Neon…), puis lancez une fois les migrations vers cette base depuis votre poste :
  `DB_CONNECTION=pgsql DB_URL=… php artisan migrate --seed --force`.
- **Indexation** : en-tête `X-Robots-Tag: noindex, nofollow` sur tout le site (la version test n’apparaît pas sur Google).
- **Sessions** : cookies chiffrés et `secure` ; **cache** et limite de tentatives de connexion en base ;
  **journaux** dans la console Vercel (`vercel logs <url>`) ; **e-mails** écrits dans les journaux.
- Toute valeur par défaut de `api/index.php` peut être remplacée par une variable Vercel du même nom.

## Vérifié en local

Simulation sans fichier `.env` via `api/index.php` : création de la base dans `/tmp`, toutes les pages et
fichiers statiques en 200, demande d’intervention complète (session, CSRF, lien signé), compte admin créé
avec `ADMIN_PASSWORD` (le mot de passe `password` est refusé), blocage après 5 tentatives de connexion
ratées, message clair si un secret manque. Le build réel sur les serveurs Vercel n’a pas encore été lancé.
