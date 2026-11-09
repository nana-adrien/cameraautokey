<?php

namespace App\Providers;

use App\Models\Brand;
use App\Models\ContactMessage;
use App\Models\Order;
use App\Models\Service;
use App\Models\Setting;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Carbon::setLocale('fr');
        Paginator::defaultView('partials.pagination');

        View::composer('layouts.site', function ($view) {
            $view->with([
                'settings' => Setting::all_values(),
                'navServices' => Service::active()->get(['title', 'slug']),
                'navBrands' => Brand::active()->get(['name', 'slug', 'category'])->groupBy('category'),
            ]);
        });

        View::composer('layouts.admin', function ($view) {
            $view->with([
                'pendingOrders' => Order::where('status', 'pending')->count(),
                'unreadMessages' => ContactMessage::whereNull('read_at')->count(),
            ]);
        });

        View::composer('pages.*', fn ($view) => $view->with('settings', Setting::all_values()));

        View::composer(['layouts.site', 'pages.garage.*'], fn ($view) => $view->with([
            'settings' => Setting::all_values(),
            'garageServices' => config('garage.services'),
        ]));
    }
}
