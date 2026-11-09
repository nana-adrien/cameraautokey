<?php

/*
|--------------------------------------------------------------------------
| Mini-site du garage 3K AUTO SERVICES
|--------------------------------------------------------------------------
| Contenus du garage (prestations, FAQ, carrousel). Chaque prestation a sa
| propre page : /garage-3k-auto-services/prestations/{slug}.
| Coordonnées (adresse, téléphone, GPS, horaires) : admin → Paramètres.
*/

return [

    'hero_slides' => [
        ['f150-pont-elevateur.webp', '55% 55%'],
        ['mecanicien-capot-toyota.webp', '60% 45%'],
        ['atelier-cour.webp', '55% 50%'],
        ['installation-camera-recul.webp', '62% 50%'],
        ['equipe-camer-auto-key.webp', '50% 30%'],
    ],

    'services' => [
        'mecanique-generale' => [
            'title' => 'Mécanique générale',
            'seo_title' => 'Mécanique auto à Douala',
            'icon' => 'fas fa-cogs',
            'photo' => 'f150-pont-elevateur.webp',
            'excerpt' => 'Moteur, embrayage, distribution, boîte de vitesses : nos mécaniciens réparent votre véhicule sur pont élévateur.',
            'intro' => 'Bruit suspect, perte de puissance, fuite ou voyant moteur : au garage 3K Auto Services, nos mécaniciens recherchent la cause avant de remplacer quoi que ce soit, puis vous remettent un devis clair avant les travaux.',
            'points' => ['Moteur : joints, fuites, courroies, distribution', 'Embrayage et boîte de vitesses', 'Transmission et boîtiers de traction', 'Refroidissement : radiateur, pompe à eau, thermostat', 'Véhicules légers, SUV, 4x4 et pick-up'],
        ],
        'vidange-entretien' => [
            'title' => 'Vidange et entretien',
            'seo_title' => 'Vidange et entretien auto à Douala',
            'icon' => 'fas fa-oil-can',
            'photo' => 'vidange-moteur.webp',
            'excerpt' => 'Huile moteur, filtres, niveaux et contrôle complet : l’entretien régulier qui évite les grosses pannes.',
            'intro' => 'Avec la chaleur, la poussière et les embouteillages de Douala, un moteur s’use plus vite. Une vidange au bon moment, avec une huile adaptée à votre moteur, prolonge sa durée de vie et réduit la consommation.',
            'points' => ['Vidange huile moteur et filtre à huile', 'Filtres à air, à carburant et d’habitacle', 'Contrôle des niveaux : liquide de refroidissement, freins, direction', 'Révision selon le carnet du constructeur', 'Contrôle visuel complet offert à chaque vidange'],
        ],
        'diagnostic-auto' => [
            'title' => 'Diagnostic électronique',
            'seo_title' => 'Diagnostic auto électronique à Douala',
            'icon' => 'fas fa-laptop-medical',
            'photo' => 'diagnostic-tablette.webp',
            'excerpt' => 'Voyant allumé, panne intermittente, calculateur : lecture des défauts avec nos valises de diagnostic.',
            'intro' => 'Les véhicules récents enregistrent leurs défauts dans leurs calculateurs. Nos valises de diagnostic les lisent, les interprètent et permettent de cibler la vraie panne, sans remplacer de pièces au hasard.',
            'points' => ['Lecture et effacement des codes défaut', 'Voyant moteur, ABS, airbag, boîte automatique', 'Contrôle des capteurs et des calculateurs', 'Diagnostic avant achat d’un véhicule d’occasion', 'Compte rendu expliqué avant toute réparation'],
        ],
        'freinage' => [
            'title' => 'Freinage',
            'seo_title' => 'Freins, plaquettes et disques à Douala',
            'icon' => 'fas fa-compact-disc',
            'photo' => 'pont-elevateur-toyota.webp',
            'excerpt' => 'Plaquettes, disques, liquide de frein et réparation complète du système de freinage.',
            'intro' => 'Freins qui grincent, pédale molle ou véhicule qui tire d’un côté : ne prenez pas de risque. Nous contrôlons tout le circuit et remplaçons uniquement ce qui doit l’être.',
            'points' => ['Plaquettes et disques avant / arrière', 'Mâchoires et tambours', 'Purge et remplacement du liquide de frein', 'Étriers, flexibles et maître-cylindre', 'Contrôle de l’ABS par diagnostic'],
        ],
        'suspension-direction-pneus' => [
            'title' => 'Suspension, direction et pneus',
            'seo_title' => 'Suspension, direction et parallélisme à Douala',
            'icon' => 'fas fa-car-side',
            'photo' => 'pont-elevateur-toyota.webp',
            'excerpt' => 'Amortisseurs, rotules, direction, alignement des roues et pneus : pour une conduite sûre sur toutes les routes.',
            'intro' => 'Nids-de-poule et routes dégradées fatiguent vite les trains roulants. Un véhicule qui tape, qui tire ou qui use ses pneus de travers a besoin d’un contrôle de la suspension et de la direction.',
            'points' => ['Amortisseurs, silentblocs, rotules et biellettes', 'Réparation de direction et crémaillère', 'Alignement des roues (parallélisme)', 'Montage et équilibrage des pneus', 'Spécial 4x4 et pick-up'],
        ],
        'climatisation' => [
            'title' => 'Climatisation',
            'seo_title' => 'Climatisation auto à Douala',
            'icon' => 'fas fa-snowflake',
            'photo' => 'mecanicien-capot-toyota.webp',
            'excerpt' => 'Recharge, recherche de fuite et réparation de la climatisation de votre véhicule.',
            'intro' => 'À Douala, une climatisation qui ne refroidit plus rend chaque trajet pénible. Nous vérifions le circuit, trouvons la fuite éventuelle et remettons votre clim en état.',
            'points' => ['Recharge en gaz et contrôle de la pression', 'Recherche de fuite', 'Compresseur, condenseur et ventilateur', 'Filtre d’habitacle et mauvaises odeurs', 'Diagnostic des commandes électroniques'],
        ],
        'electricite-alarme-gps' => [
            'title' => 'Électricité, alarme et GPS',
            'seo_title' => 'Électricité auto, alarme et GPS à Douala',
            'icon' => 'fas fa-car-battery',
            'photo' => 'diagnostic-tablette.webp',
            'excerpt' => 'Batterie, démarreur, alternateur, faisceaux, alarmes et traceurs GPS pour protéger votre véhicule.',
            'intro' => 'Pannes électriques, batterie qui se décharge, accessoires qui ne fonctionnent plus : nous réparons l’électricité de votre véhicule et installons alarmes et traceurs GPS.',
            'points' => ['Batterie, démarreur et alternateur', 'Recherche de panne électrique et faisceaux', 'Éclairage et accessoires', 'Pose d’alarme et d’antidémarrage', 'Installation de traceur GPS'],
        ],
        'cameras-ecrans' => [
            'title' => 'Caméras et écrans',
            'seo_title' => 'Caméra de recul et écran auto à Douala',
            'icon' => 'fas fa-video',
            'photo' => 'installation-camera-recul.webp',
            'excerpt' => 'Caméra de recul, vision 360°, écran multimédia et accessoires installés proprement.',
            'intro' => 'Se garer dans les rues étroites de Douala devient simple avec une caméra de recul ou une vision 360°. Nous installons et raccordons les équipements proprement, sans bricolage.',
            'points' => ['Caméra de recul et vision 360°', 'Écran multimédia Android / CarPlay', 'Dashcam', 'Raccordement propre au faisceau d’origine', 'Réglage et démonstration à la livraison'],
        ],
        'carrosserie' => [
            'title' => 'Carrosserie',
            'seo_title' => 'Carrosserie auto à Douala',
            'icon' => 'fas fa-paint-roller',
            'photo' => 'atelier-cour.webp',
            'excerpt' => 'Réparation de carrosserie après un choc, remise en forme et finitions.',
            'intro' => 'Pare-chocs abîmé, portière enfoncée ou rayures : nous remettons la carrosserie en état et vous donnons un devis avant de commencer.',
            'points' => ['Débosselage et redressage', 'Remplacement d’éléments', 'Pare-chocs et optiques', 'Préparation avant peinture', 'Devis gratuit sur photo'],
        ],
    ],

    'faqs' => [
        ['Faut-il prendre rendez-vous au garage ?', 'C’est préférable pour être servi rapidement : un message WhatsApp ou un appel suffit. Pour une urgence, appelez-nous directement.'],
        ['Comment se passe le devis ?', 'Nous examinons le véhicule (avec un diagnostic électronique si besoin), puis nous vous annonçons le prix en FCFA. Aucun travail n’est fait sans votre accord.'],
        ['Où se trouve le garage ?', 'À Ndogsimbi, derrière l’agence Overline Voyages (Douala 5e). Utilisez le bouton « Itinéraire » de la page Contact pour être guidé.'],
        ['Quels véhicules prenez-vous en charge ?', 'Voitures, SUV, 4x4, pick-up et utilitaires de la plupart des marques : Toyota, Hyundai, Kia, Nissan, Mercedes, Ford, RAM…'],
        ['Puis-je aussi faire refaire une clé ?', 'Oui : notre boutique partenaire CAMER AUTO KEY, à Akwa, s’occupe des clés, télécommandes et cartes de démarrage.'],
    ],

];
