<?php

/*
|--------------------------------------------------------------------------
| Point d'entrée Vercel — version de TEST (runtime vercel-php)
|--------------------------------------------------------------------------
| Sur Vercel, seul /tmp est inscriptible. On y redirige les caches et les
| vues compilées, puis on délègue la requête à public/index.php.
|
| Toutes les valeurs ci-dessous sont des valeurs PAR DÉFAUT : une variable
| définie dans Vercel (Settings → Environment Variables) les remplace.
| Secrets obligatoires à définir dans Vercel : APP_KEY, ADMIN_PASSWORD.
*/

$tmp = '/tmp/cameraautokey';

$defaults = [
    'APP_NAME' => 'CAMER AUTO KEY',
    'APP_ENV' => 'staging',
    'APP_DEBUG' => 'false',
    'APP_LOCALE' => 'fr',
    'APP_FALLBACK_LOCALE' => 'fr',
    'APP_TIMEZONE' => 'Africa/Douala',
    'APP_CONFIG_CACHE' => "$tmp/config.php",
    'APP_EVENTS_CACHE' => "$tmp/events.php",
    'APP_PACKAGES_CACHE' => "$tmp/packages.php",
    'APP_ROUTES_CACHE' => "$tmp/routes.php",
    'APP_SERVICES_CACHE' => "$tmp/services.php",
    'VIEW_COMPILED_PATH' => "$tmp/views",
    'LOG_CHANNEL' => 'stderr',
    'SESSION_DRIVER' => 'cookie',
    'SESSION_SECURE_COOKIE' => 'true',
    // Cache en base (et non en mémoire) pour que la limite de tentatives de connexion fonctionne.
    'CACHE_STORE' => 'database',
    'QUEUE_CONNECTION' => 'sync',
    'MAIL_MAILER' => 'log',
    // Base de démonstration : SQLite dans /tmp, recréée à chaque démarrage à froid.
    // Pour des données persistantes : DB_CONNECTION=pgsql + DB_URL dans Vercel.
    'DB_CONNECTION' => 'sqlite',
    'DB_DATABASE' => "$tmp/database.sqlite",
];

foreach ($defaults as $key => $value) {
    if (getenv($key) === false) {
        putenv("$key=$value");
        $_ENV[$key] = $_SERVER[$key] = $value;
    }
}

$missing = array_filter(['APP_KEY', 'ADMIN_PASSWORD'], fn ($key) => ! getenv($key));

if ($missing) {
    http_response_code(500);
    header('Content-Type: text/plain; charset=utf-8');
    exit('Configuration incomplète : définissez '.implode(' et ', $missing)." dans Vercel (voir DEPLOIEMENT.md).\n");
}

if (! is_dir("$tmp/views")) {
    mkdir("$tmp/views", 0775, true);
}

// Base SQLite de démonstration : création + migrations + données au premier appel du conteneur.
if (getenv('DB_CONNECTION') === 'sqlite') {
    $database = getenv('DB_DATABASE');
    $ready = $database.'.ready';

    if (! file_exists($ready)) {
        $lock = fopen($database.'.lock', 'c');
        flock($lock, LOCK_EX);

        if (! file_exists($ready)) {
            touch($database);
            require __DIR__.'/../vendor/autoload.php';
            $app = require __DIR__.'/../bootstrap/app.php';
            $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
            $kernel->bootstrap();
            $status = $kernel->call('migrate', ['--force' => true, '--seed' => true]);

            if ($status === 0) {
                touch($ready);
            }

            $app->flush();
        }

        flock($lock, LOCK_UN);
        fclose($lock);
    }
}

require __DIR__.'/../public/index.php';
