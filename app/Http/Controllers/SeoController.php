<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Service;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    /** Plan du site pour Google (pages publiques, services, marques). */
    public function sitemap(): Response
    {
        $urls = collect([
            [route('home'), '1.0', 'weekly', null],
            [route('garage'), '0.9', 'monthly', null],
            [route('services.index'), '0.8', 'monthly', null],
            [route('brands.index'), '0.7', 'monthly', null],
            [route('contact'), '0.8', 'yearly', null],
            [route('about'), '0.6', 'yearly', null],
            [route('faq'), '0.6', 'monthly', null],
            [route('professionals'), '0.5', 'yearly', null],
            [route('become-technician'), '0.4', 'yearly', null],
            [route('terms'), '0.2', 'yearly', null],
            [route('legal'), '0.1', 'yearly', null],
        ])
            ->merge(Service::active()->get()->map(fn (Service $s) => [route('services.show', $s), '0.8', 'monthly', $s->updated_at]))
            ->merge(Brand::active()->get()->map(fn (Brand $b) => [route('brands.show', $b), '0.6', 'monthly', $b->updated_at]))
            ->merge(collect(config('garage.services'))->keys()->map(fn ($slug) => [route('garage.service', $slug), '0.8', 'monthly', null]));

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n".'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
        foreach ($urls as [$loc, $priority, $freq, $updated]) {
            $xml .= '  <url><loc>'.e($loc).'</loc>'
                .($updated ? '<lastmod>'.$updated->toAtomString().'</lastmod>' : '')
                .'<changefreq>'.$freq.'</changefreq><priority>'.$priority.'</priority></url>'."\n";
        }
        $xml .= '</urlset>'."\n";

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }

    /** robots.txt : fermé hors production, ouvert (sauf admin/commandes) en production. */
    public function robots(): Response
    {
        $lines = config('seo.indexing')
            ? ['User-agent: *', 'Disallow: /admin', 'Disallow: /demande', 'Disallow: /suivre-ma-demande', 'Allow: /', '', 'Sitemap: '.route('sitemap')]
            : ['# Version de test : indexation désactivée (config/seo.php)', 'User-agent: *', 'Disallow: /'];

        return response(implode("\n", $lines)."\n", 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }
}
