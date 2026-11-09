<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Service;
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
            'others' => Service::active()->whereKeyNot($service->id)->take(3)->get(),
        ]);
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
            'services' => Service::active()->take(4)->get(),
        ]);
    }
}
