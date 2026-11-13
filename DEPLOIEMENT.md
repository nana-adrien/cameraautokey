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

Pour le pipeline complet (notifications internes + statistiques), ajoutez aussi :

```bash
vercel env add TELEGRAM_BOT_TOKEN preview
vercel env add TELEGRAM_CHAT_ID preview
vercel env add CRON_SECRET preview
vercel env add ANALYTICS_ENABLED preview
vercel env add ANALYTICS_RETENTION_DAYS preview
```

Sans `APP_KEY` et `ADMIN_PASSWORD`, le conteneur s’arrête avec le message « Configuration incomplète » (visible dans `vercel logs`).
Sans `TELEGRAM_*`, les notifications sont simplement ignorées : la demande reste enregistrée et le visiteur ne voit rien.

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
  **Pour un dashboard fiable, utilisez PostgreSQL** (voir section 6).
- **Données persistantes** (optionnel) : dans Vercel, `DB_CONNECTION=pgsql` + `DB_URL=postgresql://…`
  (Supabase, Neon…). Les migrations s’exécutent au démarrage ; ajoutez `SEED_ON_BOOT=true` une seule fois
  pour charger les données de démonstration, puis retirez-la (sinon les contenus modifiés dans l’admin seraient réécrits).
- **Sessions** en cookies chiffrés `secure` ; **cache** et limite de tentatives de connexion en base ;
  **journaux** dans la console Vercel ; **e-mails** écrits dans les journaux.
- **Indexation** bloquée tant que `APP_ENV` n’est pas `production` (gérée par l’application, voir `config/seo.php`).
  Pour la mise en ligne définitive : `APP_ENV=production`, `APP_URL=https://votre-domaine` dans Vercel.
- Toute valeur `ENV` du `Dockerfile.vercel` peut être remplacée par une variable Vercel du même nom.

## 4. Notifications Telegram (invisibles pour le visiteur)

Les demandes d’intervention et les messages (sauf newsletter) partent vers Telegram avec leur PDF et un bouton
« Ouvrir dans l’admin ». Le visiteur ne voit jamais ce canal ; un échec Telegram ne bloque jamais sa demande.

1. Créez un bot avec **@BotFather** et récupérez le token.
2. Envoyez un message au bot (ou ajoutez-le à un groupe), puis ouvrez
   `https://api.telegram.org/bot<TOKEN>/getUpdates` pour récupérer le `chat_id`.
   Pour un groupe, l’identifiant est négatif (ex. `-1001234567890`).
3. Renseignez `TELEGRAM_BOT_TOKEN` et `TELEGRAM_CHAT_ID` dans Vercel (Preview **et** Production).

L’API Bot Telegram est gratuite, officielle, et accepte directement les PDF (contrairement à WhatsApp Cloud API
qui exige des templates validés). Aucune passerelle non officielle n’est utilisée.

## 5. Mesure d’audience

La mesure est **first-party** : aucune ressource Google Analytics, aucun cookie publicitaire.

- Pages vues, pays/ville (en-têtes `X-Vercel-IP-Country` / `X-Vercel-IP-City`), appareil, navigateur, OS.
- Temps passé par page, début du formulaire, étape atteinte, envoi, abandon.
- L’adresse IP n’est jamais stockée : seul un hachage SHA-256 est conservé.
- En local, les pays ne sont pas renseignés (les en-têtes Vercel n’existent pas).
- `ANALYTICS_ENABLED=false` coupe la collecte sans toucher au reste du site.
- `ANALYTICS_RETENTION_DAYS` (365 par défaut) règle la purge.

La purge est exposée sur `GET /cron/analytics-prune`, protégée par `Authorization: Bearer <CRON_SECRET>`.
`vercel.json` déclenche ce chemin chaque jour à 03:30. Vercel ajoute automatiquement l’en-tête si
`CRON_SECRET` est défini dans le projet ; sans ce secret, la route répond 403.

## 6. Base PostgreSQL (recommandé en production)

SQLite suffit pour tester, pas pour un dashboard exploitable : le conteneur peut être recréé et les données
sont perdues. Pour conserver les demandes, les messages et les statistiques :

1. Créez une base PostgreSQL (Neon, Supabase, Railway…).
2. Dans Vercel : `DB_CONNECTION=pgsql` et `DB_URL=postgresql://user:password@host/db?sslmode=require`.
3. Laissez `docker/entrypoint.sh` exécuter `php artisan migrate --force` au démarrage.
4. Si vous voulez les données de démonstration : `SEED_ON_BOOT=true` **une seule fois**, puis retirez la variable.
5. Vérifiez dans l’admin que les demandes et les statistiques restent après un redéploiement.

Les migrations analytics sont compatibles PostgreSQL (`DATE()`, compteurs `DISTINCT`, JSON).

## Tester l’image en local

```bash
docker build -f Dockerfile.vercel -t cameraautokey-test .
```

```bash
docker run --rm -p 8080:80 -e APP_KEY="$(php artisan key:generate --show)" -e ADMIN_PASSWORD="un-mot-de-passe-de-test" -e SESSION_SECURE_COOKIE=false cameraautokey-test
```

Puis ouvrir http://localhost:8080 (`SESSION_SECURE_COOKIE=false` uniquement en local, sans HTTPS).
