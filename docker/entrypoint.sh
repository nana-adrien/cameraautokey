#!/bin/sh
# Démarrage du conteneur (Vercel ou local) — version de TEST.
set -e
cd /app

# 1. Secrets obligatoires
missing=""
[ -z "$APP_KEY" ] && missing="$missing APP_KEY"
[ -z "$ADMIN_PASSWORD" ] && missing="$missing ADMIN_PASSWORD"
if [ -n "$missing" ]; then
    echo "Configuration incomplète : définissez$missing dans Vercel (voir DEPLOIEMENT.md)." >&2
    exit 1
fi

# 2. Base de données
#    - SQLite (par défaut) : base éphémère, recréée et remplie à chaque démarrage du conteneur.
#    - Autre (pgsql…) : migrations uniquement ; données de démo seulement si SEED_ON_BOOT=true.
if [ "$DB_CONNECTION" = "sqlite" ]; then
    mkdir -p "$(dirname "$DB_DATABASE")"
    touch "$DB_DATABASE"
    php artisan migrate --force --seed --no-interaction
else
    php artisan migrate --force --no-interaction
    if [ "$SEED_ON_BOOT" = "true" ]; then
        php artisan db:seed --force --no-interaction
    fi
fi

# 3. Caches Laravel (avec les variables d'environnement réelles du conteneur)
php artisan config:cache --no-interaction
php artisan route:cache --no-interaction
php artisan view:cache --no-interaction
chown -R www-data:www-data storage bootstrap/cache database

# 4. Serveur web FrankenPHP
exec frankenphp php-server --listen ":${PORT:-80}" --root public/
