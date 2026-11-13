<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\CareerController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\GarageController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SeoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Site public
|--------------------------------------------------------------------------
*/
Route::get('/', HomeController::class)->name('home');
Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [SeoController::class, 'robots'])->name('robots');

Route::post('/demande', [OrderController::class, 'store'])->middleware('throttle:10,1')->name('orders.store');
Route::get('/demande/{order:reference}/confirmation', [OrderController::class, 'confirmation'])->middleware('signed')->name('orders.confirmation');
Route::get('/demande/{order:reference}/pdf', [OrderController::class, 'pdf'])->middleware(['signed', 'feature:pdf'])->name('orders.pdf');
Route::post('/_track', AnalyticsController::class)->middleware('throttle:120,1')->name('analytics.track');
Route::get('/suivre-ma-demande', [OrderController::class, 'trackForm'])->middleware('feature:tracking')->name('orders.track');
Route::post('/suivre-ma-demande', [OrderController::class, 'track'])->middleware(['throttle:10,1', 'feature:tracking'])->name('orders.track.search');

// Purge des données d'audience, déclenchée par le cron Vercel (Authorization: Bearer <CRON_SECRET>).
Route::get('/cron/analytics-prune', function (Request $request) {
    $secret = (string) config('services.cron.secret');

    abort_if($secret === '' || ! hash_equals($secret, (string) $request->bearerToken()), 403);

    Artisan::call('analytics:prune');

    return response()->noContent();
})->middleware('throttle:6,1')->name('cron.analytics-prune');

Route::get('/services', [CatalogController::class, 'services'])->name('services.index');
Route::get('/service/reproduction-cle-{brand}', [CatalogController::class, 'brand'])->name('brands.show');
Route::get('/service/{service}', [CatalogController::class, 'service'])->name('services.show');
Route::get('/marques', [CatalogController::class, 'brands'])->name('brands.index');

/*
| Mini-site du garage 3K AUTO SERVICES
*/
Route::prefix('garage-3k-auto-services')->controller(GarageController::class)->group(function () {
    Route::get('/', 'home')->name('garage');
    Route::get('/prestations/{slug}', 'service')->where('slug', '[a-z0-9-]+')->name('garage.service');
});

/*
| Espace recrutement (CAMER AUTO KEY et 3K AUTO SERVICES)
*/
Route::prefix('recrutement')->name('careers.')->middleware('feature:recruitment')->controller(CareerController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/{jobOffer}', 'show')->name('show');
    Route::post('/{jobOffer}/postuler', 'apply')->middleware('throttle:5,1')->name('apply');
});

Route::controller(PageController::class)->group(function () {
    Route::get('/qui-sommes-nous', 'about')->name('about');
    Route::get('/professionnels', 'professionals')->name('professionals');
    Route::get('/devenir-intervenant', 'becomeTechnician')->name('become-technician');
    Route::get('/faq', 'faq')->name('faq');
    Route::get('/contact', 'contact')->name('contact');
    Route::get('/mentions-legales', 'legal')->name('legal');
    Route::get('/conditions-intervention', 'terms')->name('terms');
    Route::post('/newsletter', 'subscribe')->middleware('throttle:5,1')->name('newsletter');
    Route::post('/contact', 'sendMessage')->middleware('throttle:5,1')->name('contact.send');
});

/*
|--------------------------------------------------------------------------
| Dashboard administrateur
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [Admin\AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [Admin\AuthController::class, 'login'])->name('login.attempt');

    Route::middleware('admin')->group(function () {
        Route::post('/logout', [Admin\AuthController::class, 'logout'])->name('logout');
        Route::get('/', Admin\DashboardController::class)->name('dashboard');
        Route::get('/analytics', Admin\AnalyticsController::class)->middleware('feature:analytics')->name('analytics');

        Route::resource('orders', Admin\OrderController::class)->only(['index', 'show', 'update', 'destroy']);
        Route::get('/orders/{order}/pdf', [Admin\OrderController::class, 'pdf'])->middleware('feature:pdf')->name('orders.pdf');
        Route::resource('messages', Admin\MessageController::class)->only(['index', 'show', 'destroy']);
        Route::get('/messages/{message}/pdf', [Admin\MessageController::class, 'pdf'])->middleware('feature:pdf')->name('messages.pdf');

        Route::resource('services', Admin\ServiceController::class)->except('show');
        Route::resource('brands', Admin\BrandController::class)->except('show');
        Route::resource('technicians', Admin\TechnicianController::class)->except('show')->middleware('feature:technicians');
        Route::resource('testimonials', Admin\TestimonialController::class)->except('show')->middleware('feature:testimonials');
        Route::resource('faqs', Admin\FaqController::class)->except('show')->middleware('feature:faqs');

        Route::middleware('feature:recruitment')->group(function () {
            Route::resource('job-offers', Admin\JobOfferController::class)->except('show');
            Route::resource('applications', Admin\JobApplicationController::class)->only(['index', 'show', 'update', 'destroy']);
            Route::get('/applications/{application}/cv', [Admin\JobApplicationController::class, 'cv'])->name('applications.cv');
        });

        Route::get('/settings', [Admin\SettingController::class, 'edit'])->name('settings.edit');
        Route::put('/settings', [Admin\SettingController::class, 'update'])->name('settings.update');
    });
});
