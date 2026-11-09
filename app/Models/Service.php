<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = ['title', 'slug', 'icon', 'excerpt', 'content', 'price_from', 'position', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean', 'price_from' => 'integer', 'position' => 'integer'];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('position');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
