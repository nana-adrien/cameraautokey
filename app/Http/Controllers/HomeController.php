<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Service;
use App\Models\Testimonial;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        return view('pages.home', [
            'services' => Service::active()->get(),
            'testimonials' => Testimonial::where('is_published', true)->latest()->take(6)->get(),
            'brands' => Brand::active()->get()->groupBy('category'),
            'pricing' => config('pricing'),
        ]);
    }
}
