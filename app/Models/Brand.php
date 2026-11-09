<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    public const CATEGORIES = [
        'auto' => 'Automobiles',
        'pl' => 'Poids lourds',
        'moto' => 'Motos',
    ];

    protected $fillable = ['name', 'slug', 'category', 'description', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('name');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /** URL du logo (public/images/brands/{slug}.svg) ou null si aucun logo n'est disponible. */
    public function logoUrl(): ?string
    {
        return is_file(public_path("images/brands/{$this->slug}.svg")) ? asset("images/brands/{$this->slug}.svg") : null;
    }

    public function categoryLabel(): string
    {
        return self::CATEGORIES[$this->category] ?? $this->category;
    }
}
