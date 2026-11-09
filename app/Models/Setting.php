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
        'address' => 'Akwa, Douala — Cameroun',
        'garage_name' => '3K AUTO SERVICES SARL',
        'garage_phone' => '+237 678 12 00 47',
        'garage_whatsapp' => '237678120047',
        'opening_hours' => 'Lundi – samedi, 8h – 18h',
        'social_facebook' => '',
        'social_tiktok' => '',
        'social_youtube' => '',
        'social_instagram' => '',
        'hero_title' => 'Clé de voiture perdue, cassée ou à refaire ?',
        'hero_subtitle' => 'Nous taillons, programmons et refaisons toutes les clés auto en quelques minutes, à l’atelier d’Akwa ou chez vous.',
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
