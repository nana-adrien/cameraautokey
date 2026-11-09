<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public const LABELS = [
        'company_name' => 'Nom de l’entreprise',
        'phone' => 'Téléphone principal',
        'phone_secondary' => 'Téléphone secondaire (Yaoundé ou autre agence)',
        'email' => 'E-mail de contact',
        'whatsapp' => 'Numéro WhatsApp (format international, sans +)',
        'address' => 'Zone / adresse',
        'garage_name' => 'Nom du garage',
        'garage_phone' => 'Téléphone du garage',
        'garage_whatsapp' => 'WhatsApp du garage (format international, sans +)',
        'opening_hours' => 'Horaires d’ouverture de l’atelier',
        'social_facebook' => 'Lien Facebook',
        'social_tiktok' => 'Lien TikTok',
        'social_youtube' => 'Lien YouTube',
        'social_instagram' => 'Lien Instagram',
        'hero_title' => 'Titre de la page d’accueil',
        'hero_subtitle' => 'Sous-titre de la page d’accueil',
    ];

    public function edit(): View
    {
        return view('admin.settings', ['values' => Setting::all_values(), 'labels' => self::LABELS]);
    }

    public function update(Request $request): RedirectResponse
    {
        $rules = array_fill_keys(array_keys(self::LABELS), ['nullable', 'string', 'max:300']);
        $rules['email'] = ['required', 'email'];
        $rules['company_name'] = ['required', 'string', 'max:100'];

        foreach ($request->validate($rules) as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return back()->with('success', 'Paramètres enregistrés.');
    }
}
