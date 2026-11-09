<?php

namespace App\Support;

use App\Models\Brand;
use App\Models\Service;
use App\Models\Setting;
use Illuminate\Support\Collection;

/**
 * Données structurées schema.org (JSON-LD) pour Google :
 * les deux entreprises (boutique de clés + garage), le site, le fil d'Ariane,
 * les prestations et la FAQ.
 */
class Seo
{
    public static function json(array $data): string
    {
        return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG);
    }

    /** CAMER AUTO KEY — boutique de clés (Akwa). */
    public static function shop(): array
    {
        $s = Setting::all_values();

        return array_filter([
            '@context' => 'https://schema.org',
            '@type' => ['Locksmith', 'AutomotiveBusiness'],
            '@id' => url('/').'#camer-auto-key',
            'name' => $s['company_name'],
            'description' => 'Copie, codage et remplacement de clés de voitures, camions et motos à Douala : clé mécanique, à puce, télécommande, mains libres et carte de démarrage.',
            'url' => url('/'),
            'logo' => asset('images/logo-camer-auto-key.png'),
            'image' => [asset(config('seo.og_image')), asset('images/photos/facade-camer-auto-key.webp')],
            'telephone' => self::phone($s['phone']),
            'email' => $s['email'] ?: null,
            'address' => self::address('Boulevard de la République (en face de l’ancienne direction de BOCOM), Akwa'),
            'geo' => self::geo($s['shop_lat'], $s['shop_lng']),
            'hasMap' => $s['shop_maps_url'] ?: null,
            'areaServed' => self::areaServed(),
            'currenciesAccepted' => 'XAF',
            'paymentAccepted' => 'Espèces, Orange Money, MTN Mobile Money, Carte bancaire',
            'priceRange' => 'À partir de 25 000 FCFA',
            'sameAs' => self::sameAs($s),
            'hasOfferCatalog' => [
                '@type' => 'OfferCatalog',
                'name' => 'Prestations clés automobiles',
                'itemListElement' => Service::active()->get()->map(fn (Service $service) => [
                    '@type' => 'Offer',
                    'itemOffered' => ['@type' => 'Service', 'name' => $service->title, 'url' => route('services.show', $service)],
                    'priceSpecification' => ['@type' => 'PriceSpecification', 'minPrice' => $service->price_from, 'priceCurrency' => 'XAF'],
                ])->all(),
            ],
        ]);
    }

    /** 3K AUTO SERVICES — garage (Ndogsimbi). */
    public static function garage(): array
    {
        $s = Setting::all_values();

        return array_filter([
            '@context' => 'https://schema.org',
            '@type' => 'AutoRepair',
            '@id' => route('garage').'#3k-auto-services',
            'name' => $s['garage_name'],
            'description' => 'Garage automobile à Douala : mécanique générale, entretien et vidange, diagnostic électronique, freinage, alarme, GPS et caméras.',
            'url' => route('garage'),
            'logo' => asset('images/logo-3k-auto-services.webp'),
            'image' => [asset(config('seo.og_image_garage')), asset('images/photos/atelier-cour.webp')],
            'telephone' => self::phone($s['garage_phone']),
            'address' => self::address('Ndogsimbi, derrière l’agence Overline Voyages, Douala 5e'),
            'geo' => self::geo($s['garage_lat'], $s['garage_lng']),
            'hasMap' => $s['garage_maps_url'] ?: null,
            'areaServed' => self::areaServed(),
            'currenciesAccepted' => 'XAF',
            'paymentAccepted' => 'Espèces, Orange Money, MTN Mobile Money',
            'makesOffer' => collect(config('garage.services'))
                ->map(fn ($gs, $slug) => ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => $gs['title'], 'url' => route('garage.service', $slug)]])
                ->values()->all(),
            'sameAs' => array_values(array_filter([$s['garage_maps_url'] ?? null])) ?: null,
        ]);
    }

    public static function website(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            '@id' => url('/').'#website',
            'name' => Setting::get('company_name'),
            'url' => url('/'),
            'inLanguage' => 'fr-CM',
            'publisher' => ['@id' => url('/').'#camer-auto-key'],
        ];
    }

    /**
     * @param array<int, array{0:string, 1:?string}> $items [libellé, url] — le dernier élément est la page courante
     * @param array{0:string, 1:string}|null $root racine (par défaut : accueil du site des clés)
     */
    public static function breadcrumb(array $items, ?array $root = null): array
    {
        $items = array_merge([$root ?? ['Accueil', url('/')]], $items);

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => collect($items)->values()->map(fn ($item, $i) => array_filter([
                '@type' => 'ListItem',
                'position' => $i + 1,
                'name' => $item[0],
                'item' => $item[1] ?? url()->current(),
            ]))->all(),
        ];
    }

    public static function service(string $name, string $description, ?int $priceFrom = null): array
    {
        return array_filter([
            '@context' => 'https://schema.org',
            '@type' => 'Service',
            'name' => $name,
            'description' => $description,
            'serviceType' => 'Serrurerie automobile',
            'provider' => ['@id' => url('/').'#camer-auto-key'],
            'areaServed' => self::areaServed(),
            'url' => url()->current(),
            'offers' => $priceFrom ? ['@type' => 'Offer', 'priceCurrency' => 'XAF', 'price' => $priceFrom, 'description' => 'Prix de départ, confirmé avant intervention'] : null,
        ]);
    }

    public static function brandService(Brand $brand): array
    {
        return self::service('Clé '.$brand->name.' à Douala', (string) $brand->description);
    }

    /** Prestation du garage : fournie par 3K Auto Services. */
    public static function garageService(array $service): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Service',
            'name' => $service['seo_title'],
            'description' => $service['excerpt'],
            'serviceType' => $service['title'],
            'provider' => ['@id' => route('garage').'#3k-auto-services'],
            'areaServed' => self::areaServed(),
            'url' => url()->current(),
        ];
    }

    /** @param array<int, array{0:string, 1:string}> $faqs [question, réponse] */
    public static function faqFromArray(array $faqs): array
    {
        return self::faq(collect($faqs)->map(fn ($f) => (object) ['question' => $f[0], 'answer' => $f[1]]));
    }

    public static function faq(Collection $faqs): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $faqs->map(fn ($faq) => [
                '@type' => 'Question',
                'name' => $faq->question,
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => $faq->answer],
            ])->all(),
        ];
    }

    private static function address(string $street): array
    {
        return [
            '@type' => 'PostalAddress',
            'streetAddress' => $street,
            'addressLocality' => 'Douala',
            'addressRegion' => 'Littoral',
            'addressCountry' => 'CM',
        ];
    }

    private static function geo(string $lat, string $lng): ?array
    {
        return is_numeric($lat) && is_numeric($lng)
            ? ['@type' => 'GeoCoordinates', 'latitude' => (float) $lat, 'longitude' => (float) $lng]
            : null;
    }

    private static function areaServed(): array
    {
        return [
            ['@type' => 'City', 'name' => 'Douala'],
            ['@type' => 'City', 'name' => 'Yaoundé'],
            ['@type' => 'Country', 'name' => 'Cameroun'],
        ];
    }

    private static function phone(string $phone): ?string
    {
        $digits = preg_replace('/[^\d+]/', '', $phone);

        return $digits !== '' ? $digits : null;
    }

    private static function sameAs(array $s): ?array
    {
        $links = array_values(array_filter([
            $s['social_facebook'] ?? '', $s['social_instagram'] ?? '', $s['social_tiktok'] ?? '', $s['social_youtube'] ?? '',
        ], fn ($url) => str_starts_with((string) $url, 'http')));

        return $links ?: null;
    }
}
