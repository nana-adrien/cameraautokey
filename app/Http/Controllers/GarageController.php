<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

/** Mini-site du garage 3K AUTO SERVICES (contenus : config/garage.php). */
class GarageController extends Controller
{
    public function home(): View
    {
        return view('pages.garage.home');
    }

    public function service(string $slug): View
    {
        $service = config("garage.services.$slug");
        abort_unless($service, 404);

        return view('pages.garage.service', [
            'slug' => $slug,
            'service' => $service,
            'others' => collect(config('garage.services'))->except($slug),
        ]);
    }
}
