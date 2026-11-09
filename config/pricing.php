<?php

/*
| Grille tarifaire CAMERAUTOKEY (en euros TTC).
| Utilisée par App\Services\PriceCalculator côté serveur et exposée au
| configurateur JavaScript pour l'affichage du prix en temps réel.
*/

return [
    'vehicle_types' => [
        'auto' => ['label' => 'Voiture - utilitaire', 'hint' => 'Voiture · SUV · Utilitaire', 'icon' => '🚗', 'multiplier' => 1.0],
        'pl' => ['label' => 'Camion poids lourd', 'hint' => 'Fourgon · Porteur · Semi', 'icon' => '🚚', 'multiplier' => 1.35],
        'moto' => ['label' => 'Moto / Scooter', 'hint' => '2 roues · 3 roues · Quad', 'icon' => '🏍️', 'multiplier' => 0.85],
    ],

    'needs' => [
        'double' => ['label' => 'Double de clé', 'hint' => 'J’ai encore au moins une clé qui fonctionne', 'base' => 49],
        'lost' => ['label' => 'Perte totale des clés', 'hint' => 'Aucune clé disponible', 'base' => 129],
        'opening' => ['label' => 'Ouverture + programmation', 'hint' => 'Ouvrir le véhicule sans clé puis programmer une clé', 'base' => 169],
    ],

    'key_types' => [
        'simple' => ['label' => 'Clé simple', 'hint' => 'Sans puce, sans boutons', 'extra' => 0],
        'transponder' => ['label' => 'Clé à transpondeur', 'hint' => 'Puce antivol, sans boutons — programmation nécessaire', 'extra' => 30],
        'remote' => ['label' => 'Clé télécommande', 'hint' => 'Boutons d’ouverture / fermeture des portes', 'extra' => 60],
        'smart' => ['label' => 'Clé Smart / Keyless / Carte', 'hint' => 'Démarrage sans clé ou carte mains libres', 'extra' => 120],
    ],

    'desired_keys' => [
        'generic' => ['label' => 'Clé générique compatible', 'hint' => 'Économique et fiable, recommandée dans la plupart des cas', 'extra' => 0],
        'original' => ['label' => 'Clé d’origine constructeur', 'hint' => 'Modèle de marque avec logo constructeur', 'extra' => 90],
    ],

    'second_key_discount' => 0.30,
    'warranty_extension' => 29,
    'emergency' => 60,
];
