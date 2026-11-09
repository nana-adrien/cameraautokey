<?php

/*
| Grille tarifaire CAMERAUTOKEY (en francs CFA, FCFA).
| Utilisée par App\Services\PriceCalculator côté serveur et exposée au
| configurateur JavaScript pour l'affichage du prix en temps réel.
*/

return [
    'vehicle_types' => [
        'auto' => ['label' => 'Voiture - utilitaires', 'hint' => 'Voiture · SUV · Utilitaire', 'icon' => '🚗', 'multiplier' => 1.0],
        'pl' => ['label' => 'Camion poids lourd', 'hint' => 'Fourgon · Porteur · Semi', 'icon' => '🚚', 'multiplier' => 1.35],
        'moto' => ['label' => 'Moto / Scooter', 'hint' => '2 roues · 3 roues · Quad', 'icon' => '🏍️', 'multiplier' => 0.85],
    ],

    'needs' => [
        'double' => ['label' => 'Je veux un double de clé', 'hint' => 'J’ai encore au moins une clé qui fonctionne', 'base' => 25000],
        'lost' => ['label' => 'Aucune clé disponible — perte totale', 'hint' => 'Je n’ai plus aucune clé du véhicule', 'base' => 75000],
        'opening' => ['label' => 'Ouverture du véhicule sans clé et programmation', 'hint' => 'Clé enfermée ou véhicule bloqué', 'base' => 90000],
    ],

    'key_types' => [
        'simple' => ['label' => 'Clé simple', 'hint' => 'Sans puce, sans boutons', 'tooltip' => 'Clé entièrement mécanique : la lame suffit à ouvrir et démarrer. Courante sur les véhicules anciens et certaines motos.', 'extra' => 0],
        'transponder' => ['label' => 'Clé à transpondeur', 'hint' => 'Puce antivol, sans boutons', 'tooltip' => 'Une puce dans la tête de clé dialogue avec l’antidémarrage : la clé doit être programmée pour que le moteur démarre.', 'extra' => 15000],
        'remote' => ['label' => 'Clé télécommande', 'hint' => 'Boutons pour ouvrir / fermer les portes', 'tooltip' => 'Clé avec boutons de verrouillage à distance, démarrage en tournant la clé dans le contact.', 'extra' => 30000],
        'smart' => ['label' => 'Clé Smart / Keyless / Carte', 'hint' => 'Démarrage sans clé dans le contact', 'tooltip' => 'Démarrage par bouton Start/Stop : la clé ou la carte reste dans votre poche (système mains libres).', 'extra' => 60000],
    ],

    'desired_keys' => [
        'generic' => ['label' => 'Clé générique compatible', 'hint' => 'Économique et fiable, recommandée dans la plupart des cas', 'extra' => 0],
        'original' => ['label' => 'Clé d’origine constructeur', 'hint' => 'Modèle de marque avec logo constructeur', 'extra' => 50000],
    ],

    'second_key_discount' => 0.30,
    'warranty_extension' => 15000,
    'emergency' => 25000,
];
