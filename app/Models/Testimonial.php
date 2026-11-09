<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = ['name', 'city', 'content', 'rating', 'is_published'];

    protected function casts(): array
    {
        return ['is_published' => 'boolean', 'rating' => 'integer'];
    }

    public function initials(): string
    {
        return collect(explode(' ', $this->name))
            ->map(fn (string $part) => mb_strtoupper(mb_substr($part, 0, 1)))
            ->take(2)
            ->implode('');
    }
}
