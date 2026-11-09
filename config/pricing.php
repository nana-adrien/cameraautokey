<?php

/*
| Grille tarifaire CAMERAUTOKEY (en francs CFA, FCFA).
| Utilisée par App\Services\PriceCalculator côté serveur et exposée au
| configurateur JavaScript pour l'affichage du prix en temps réel.
*/

return [
    'vehicle_types' => [
        'auto' => ['label' => 'Voiture & 4x4', 'hint' => 'Berline, SUV, pick-up, utilitaire', 'icon' => '🚗', 'multiplier' => 1.0],
        'pl' => ['label' => 'Camion & bus', 'hint' => 'Porteur, tracteur, minibus', 'icon' => '🚚', 'multiplier' => 1.35],
        'moto' => ['label' => 'Deux-roues', 'hint' => 'Moto, scooter, tricycle', 'icon' => '🏍️', 'multiplier' => 0.85],
    ],

    'needs' => [
        'double' => ['label' => 'J’ai encore une clé et je veux un double', 'hint' => 'Une clé fonctionne : on la copie à l’identique', 'base' => 25000],
        'lost' => ['label' => 'Je n’ai plus aucune clé', 'hint' => 'Clés perdues, volées ou toutes hors service', 'base' => 75000],
        'opening' => ['label' => 'Mon véhicule est fermé et je n’ai pas de clé', 'hint' => 'Ouverture du véhicule, puis fabrication d’une clé', 'base' => 90000],
    ],

    'key_types' => [
        'simple' => ['label' => 'Clé mécanique', 'hint' => 'Lame métallique uniquement', 'tooltip' => 'Une lame taillée, sans électronique : fréquente sur les véhicules anciens, les camions et de nombreuses motos.', 'extra' => 0],
        'transponder' => ['label' => 'Clé à puce', 'hint' => 'Tête en plastique, aucun bouton', 'tooltip' => 'La tête cache une puce codée reconnue par l’antidémarrage. Sans programmation, le moteur ne démarre pas.', 'extra' => 15000],
        'remote' => ['label' => 'Clé à boutons', 'hint' => 'Fixe ou pliante, avec télécommande', 'tooltip' => 'Lame (fixe ou rétractable) et boutons pour verrouiller les portes. On démarre en tournant la clé.', 'extra' => 30000],
        'smart' => ['label' => 'Clé mains libres', 'hint' => 'Démarrage par bouton Start / Stop', 'tooltip' => 'Boîtier sans lame apparente : il reste dans la poche, le véhicule démarre par un bouton.', 'extra' => 60000],
        'card' => ['label' => 'Carte de démarrage', 'hint' => 'Format carte de crédit', 'tooltip' => 'Carte plate glissée dans un lecteur ou gardée sur soi, notamment chez Renault.', 'extra' => 60000],
    ],

    'desired_keys' => [
        'generic' => ['label' => 'Gamme compatible', 'hint' => 'Qualité professionnelle, le meilleur rapport qualité-prix', 'extra' => 0],
        'original' => ['label' => 'Gamme origine', 'hint' => 'Pièce du constructeur, avec son logo', 'extra' => 50000],
    ],

    'second_key_discount' => 0.30,
    'warranty_extension' => 15000,
    'emergency' => 25000,
];
