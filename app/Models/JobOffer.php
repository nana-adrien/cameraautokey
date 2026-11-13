<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobOffer extends Model
{
    use HasFactory;

    public const COMPANIES = [
        'cak' => 'CAMER AUTO KEY',
        '3k' => '3K Auto Services',
    ];

    protected $fillable = [
        'company', 'title', 'slug', 'contract_type', 'location', 'summary',
        'description', 'requirements', 'closes_at', 'is_published',
    ];

    protected function casts(): array
    {
        return [
            'closes_at' => 'date',
            'is_published' => 'boolean',
        ];
    }

    public function applications(): HasMany
    {
        return $this->hasMany(JobApplication::class);
    }

    /**
     * Offres publiées dont la date limite n'est pas dépassée.
     */
    public function scopeOpen(Builder $query): void
    {
        $query->where('is_published', true)
            ->where(fn (Builder $q) => $q->whereNull('closes_at')->orWhereDate('closes_at', '>=', today()));
    }

    public function companyLabel(): string
    {
        return self::COMPANIES[$this->company] ?? $this->company;
    }

    /**
     * Nom lisible de l'entreprise, utilisé dans les listes de l'administration.
     */
    protected function companyName(): Attribute
    {
        return Attribute::get(fn (): string => $this->companyLabel());
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
