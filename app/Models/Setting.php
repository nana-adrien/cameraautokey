<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    public const DEFAULTS = [
        'company_name' => 'CAMER AUTO KEY',
        'phone' => '+237 694 02 85 96',
        'phone_secondary' => '',
        'email' => 'camerautokeyy@gmail.com',
        'whatsapp' => '237694028596',
        'address' => 'Douala — Cameroun', // adresse de la boutique Camer Auto Key (à compléter)
        'garage_name' => '3K AUTO SERVICES SARL',
        'garage_phone' => '+237 678 12 00 47',
        'garage_whatsapp' => '237678120047',
        // Localisation (coordonnées GPS décimales : latitude, longitude)
        'shop_lat' => '',
        'shop_lng' => '',
        'garage_address' => 'Ndogsimbi, derrière l’agence Overline Voyages, Douala 5e',
        'garage_hours' => 'Ouvert de 8h00 à 17h30',
        'garage_lat' => '4.061387',
        'garage_lng' => '9.7346894',
        'garage_maps_url' => 'https://maps.google.com/?cid=15614242871044277534',
        'opening_hours' => 'Lundi – samedi, 8h – 18h',
        'social_facebook' => '',
        'social_tiktok' => '',
        'social_youtube' => '',
        'social_instagram' => '',
        'hero_title' => 'Clé perdue, abîmée ou en un seul exemplaire ?',
        'hero_subtitle' => 'Passez sans rendez-vous à la boutique, ou faites venir un technicien.',
    ];

    protected $fillable = ['key', 'value'];

    /** @return array<string, string> */
    public static function all_values(): array
    {
        return Cache::rememberForever('settings', function () {
            return array_merge(self::DEFAULTS, static::query()->pluck('value', 'key')->filter()->all());
        });
    }

    public static function get(string $key): string
    {
        return (string) (self::all_values()[$key] ?? '');
    }

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('settings'));
        static::deleted(fn () => Cache::forget('settings'));
    }
}
