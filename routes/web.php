<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Site public
|--------------------------------------------------------------------------
*/
Route::get('/', HomeController::class)->name('home');
Route::get('/sitemap.xml', [\App\Http\Controllers\SeoController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [\App\Http\Controllers\SeoController::class, 'robots'])->name('robots');

Route::post('/demande', [OrderController::class, 'store'])->middleware('throttle:10,1')->name('orders.store');
Route::get('/demande/{order:reference}/confirmation', [OrderController::class, 'confirmation'])->middleware('signed')->name('orders.confirmation');
Route::get('/suivre-ma-demande', [OrderController::class, 'trackForm'])->name('orders.track');
Route::post('/suivre-ma-demande', [OrderController::class, 'track'])->middleware('throttle:10,1')->name('orders.track.search');

Route::get('/services', [CatalogController::class, 'services'])->name('services.index');
Route::get('/service/reproduction-cle-{brand}', [CatalogController::class, 'brand'])->name('brands.show');
Route::get('/service/{service}', [CatalogController::class, 'service'])->name('services.show');
Route::get('/marques', [CatalogController::class, 'brands'])->name('brands.index');

/*
| Mini-site du garage 3K AUTO SERVICES
*/
Route::prefix('garage-3k-auto-services')->controller(\App\Http\Controllers\GarageController::class)->group(function () {
    Route::get('/', 'home')->name('garage');
    Route::get('/prestations/{slug}', 'service')->where('slug', '[a-z0-9-]+')->name('garage.service');
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

        Route::resource('orders', Admin\OrderController::class)->only(['index', 'show', 'update', 'destroy']);
        Route::resource('messages', Admin\MessageController::class)->only(['index', 'show', 'destroy']);

        Route::resource('services', Admin\ServiceController::class)->except('show');
        Route::resource('brands', Admin\BrandController::class)->except('show');
        Route::resource('technicians', Admin\TechnicianController::class)->except('show');
        Route::resource('testimonials', Admin\TestimonialController::class)->except('show');
        Route::resource('faqs', Admin\FaqController::class)->except('show');

        Route::get('/settings', [Admin\SettingController::class, 'edit'])->name('settings.edit');
        Route::put('/settings', [Admin\SettingController::class, 'update'])->name('settings.update');
    });
});
