<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Faq;
use App\Models\Service;
use App\Models\Testimonial;
use Illuminate\View\View;

class CatalogController extends Controller
{
    public function services(): View
    {
        return view('pages.services', ['services' => Service::active()->get()]);
    }

    public function service(Service $service): View
    {
        abort_unless($service->is_active, 404);

        return view('pages.service', [
            'service' => $service,
            'others' => Service::active()->whereKeyNot($service->id)->get(),
        ] + $this->sharedBlocks());
    }

    public function brands(): View
    {
        return view('pages.brands', ['brands' => Brand::active()->get()->groupBy('category')]);
    }

    public function brand(Brand $brand): View
    {
        abort_unless($brand->is_active, 404);

        return view('pages.brand', [
            'brand' => $brand,
            'services' => Service::active()->get(),
        ] + $this->sharedBlocks());
    }

    /** Blocs communs aux pages service et marque (FAQ, marques, avis). */
    private function sharedBlocks(): array
    {
        return [
            'faqs' => Faq::where('is_published', true)->orderBy('position')->take(5)->get(),
            'brands' => Brand::active()->get(),
            'testimonials' => Testimonial::where('is_published', true)->latest()->take(2)->get(),
        ];
    }
}
