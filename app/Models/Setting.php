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
        'hero_title' => 'Clé de voiture perdue, cassée ou à dupliquer ?',
        'hero_subtitle' => 'Un technicien CAMERAUTOKEY vient à vous, 24h/24 et 7j/7. Prix annoncé avant de partir, sans surprise.',
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
