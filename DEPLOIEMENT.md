# Déploiement de la version TEST sur Vercel (conteneur Docker)

Le site est déployé sur Vercel comme **conteneur** : Vercel construit `Dockerfile.vercel`
(Laravel servi par FrankenPHP, PHP 8.5) et envoie tout le trafic sur le port 80 du conteneur.

| Fichier | Rôle |
|---|---|
| `Dockerfile.vercel` | Image : extensions PHP, dépendances Composer (sans dev), valeurs par défaut de la version test |
| `docker/entrypoint.sh` | Démarrage : vérifie les secrets, prépare la base, met en cache config/routes/vues, lance FrankenPHP |
| `vercel.json` | Force le mode conteneur (`"framework": "container"`) + en-têtes de sécurité et `noindex` |
| `.vercelignore` / `.dockerignore` | N’envoient jamais `.env`, `vendor/`, les bases locales ni les journaux |

## 1. Variables à définir dans Vercel (une seule fois)

Pour l’environnement **Preview** (tableau de bord → *Settings → Environment Variables*, ou CLI) :

```bash
php artisan key:generate --show
```

```bash
vercel env add APP_KEY preview
```

```bash
vercel env add ADMIN_PASSWORD preview
```

- `APP_KEY` : une clé **différente** de celle du `.env` local.
- `ADMIN_PASSWORD` : mot de passe du compte admin de test (`ADMIN_EMAIL` facultatif, défaut `admin@cameraautokey.fr`).

Sans ces deux variables, le conteneur s’arrête avec le message « Configuration incomplète » (visible dans `vercel logs`).

## 2. Lancer un déploiement de test

```bash
vercel
```

Sans `--prod`, Vercel crée un déploiement **Preview**. ⚠️ Le projet est relié au dépôt GitHub : chaque `git push`
sur `main` déclenche un déploiement **Production** (qui aura besoin des mêmes variables pour l’environnement Production).

## 3. Comportement de la version test

- **Base de données** : SQLite dans le conteneur, recréée avec les données de démonstration à chaque démarrage.
  Vercel met le conteneur en veille après 30 s sans trafic en preview (5 min en production) : les demandes
  saisies **disparaissent**, et plusieurs conteneurs peuvent tourner en parallèle avec chacun leur base.
- **Données persistantes** (optionnel) : dans Vercel, `DB_CONNECTION=pgsql` + `DB_URL=postgresql://…`
  (Supabase, Neon…). Les migrations s’exécutent au démarrage ; ajoutez `SEED_ON_BOOT=true` une seule fois
  pour charger les données de démonstration, puis retirez-la (sinon les contenus modifiés dans l’admin seraient réécrits).
- **Sessions** en cookies chiffrés `secure` ; **cache** et limite de tentatives de connexion en base ;
  **journaux** dans la console Vercel ; **e-mails** écrits dans les journaux.
- **Indexation** bloquée tant que `APP_ENV` n’est pas `production` (gérée par l’application, voir `config/seo.php`).
  Pour la mise en ligne définitive : `APP_ENV=production`, `APP_URL=https://votre-domaine` dans Vercel.
- Toute valeur `ENV` du `Dockerfile.vercel` peut être remplacée par une variable Vercel du même nom.

## Tester l’image en local

```bash
docker build -f Dockerfile.vercel -t cameraautokey-test .
```

```bash
docker run --rm -p 8080:80 -e APP_KEY="$(php artisan key:generate --show)" -e ADMIN_PASSWORD="un-mot-de-passe-de-test" -e SESSION_SECURE_COOKIE=false cameraautokey-test
```

Puis ouvrir http://localhost:8080 (`SESSION_SECURE_COOKIE=false` uniquement en local, sans HTTPS).
