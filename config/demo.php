<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Version de démonstration
    |--------------------------------------------------------------------------
    |
    | Les fonctionnalités listées dans DEMO_LOCKED_FEATURES (séparées par des
    | virgules) affichent « En cours de développement » au lieu de fonctionner.
    | On les retire de la liste au fur et à mesure des rendez-vous ; une liste
    | vide correspond au site complet, tel qu'il est livré.
    |
    | Exemple : DEMO_LOCKED_FEATURES=tracking,pdf,analytics,telegram,technicians,testimonials,faqs,recruitment
    |
    */

    'locked' => array_values(array_filter(array_map('trim', explode(',', (string) env('DEMO_LOCKED_FEATURES', ''))))),

    // Libellés affichés sur la page « En cours de développement ».
    'features' => [
        'tracking' => 'Suivi de demande en ligne',
        'pdf' => 'Récapitulatifs PDF',
        'analytics' => 'Statistiques d’audience',
        'telegram' => 'Notifications Telegram',
        'technicians' => 'Gestion des techniciens',
        'testimonials' => 'Gestion des avis clients',
        'faqs' => 'Gestion de la FAQ',
        'recruitment' => 'Espace recrutement',
        'brands' => 'Pages par marque',
        'garage_services' => 'Pages des prestations du garage',
        'info_pages' => 'Pages d’information',
        'orders_admin' => 'Gestion des demandes',
        'messages_admin' => 'Messages reçus',
        'catalog_admin' => 'Gestion des services et des marques',
        'settings_admin' => 'Paramètres du site',
    ],

];
