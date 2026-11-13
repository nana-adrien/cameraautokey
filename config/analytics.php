<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Mesure d'audience
    |--------------------------------------------------------------------------
    |
    | La collecte est faite côté serveur (pages vues) et côté navigateur
    | (temps passé, étapes du formulaire, abandons). Les adresses IP ne sont
    | jamais stockées en clair : seul un hachage est conservé.
    |
    */

    'enabled' => env('ANALYTICS_ENABLED', true),

    // Durée de conservation des visites, en jours. Utilisée par analytics:prune.
    'retention_days' => (int) env('ANALYTICS_RETENTION_DAYS', 365),

];
