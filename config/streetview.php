<?php

/*
|--------------------------------------------------------------------------
| Street View des adresses (page Contact et page Garage)
|--------------------------------------------------------------------------
| Une entrée par adresse : 'shop' (boutique Camer Auto Key, Akwa) et
| 'garage' (3K Auto Services, Ndogsimbi).
|
| Quand la vraie vue 360° est prête :
|   1. Sur Google Maps, ouvrir la vue 360° devant l'adresse.
|   2. Partager → « Intégrer une carte » → copier l'adresse entre les guillemets de src="…".
|   3. La coller dans 'embed_url', puis mettre 'test' => false.
|
| Sans 'embed_url', la vue est cherchée par coordonnées : Google affiche le
| panorama le plus proche de 'lat' / 'lng', orienté selon 'heading' (0 = nord,
| 90 = est, 180 = sud, 270 = ouest) et 'pitch' (inclinaison, 0 = horizon).
|
| 'enabled' => false masque complètement le bouton « Street View ».
*/

return [

    'shop' => [
        'enabled' => true,
        // Vue de TEST : photo 360° publique près du boulevard de la Liberté (Akwa),
        // à environ 700 m de la boutique. À remplacer par la vraie vue.
        'test' => true,
        'embed_url' => null,
        'lat' => 4.0485,
        'lng' => 9.6950,
        'heading' => 326,
        'pitch' => 0,
    ],

    'garage' => [
        'enabled' => false,
        'test' => false,
        'embed_url' => null,
        'lat' => null,
        'lng' => null,
        'heading' => 0,
        'pitch' => 0,
    ],

];
