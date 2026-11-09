<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    public const DEFAULTS = [
        'company_name' => 'CAMERAUTOKEY',
        'phone' => '01 23 45 67 89',
        'phone_secondary' => '',
        'email' => 'contact@cameraautokey.fr',
        'whatsapp' => '33123456789',
        'address' => 'France & Belgique',
        'social_facebook' => '',
        'social_tiktok' => '',
        'social_youtube' => '',
        'social_instagram' => '',
        'hero_title' => 'Clé de voiture perdue, cassée ou à refaire ?',
        'hero_subtitle' => 'Technicien équipé selon votre véhicule, intervention rapide à domicile.',
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
