<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    public const TYPES = [
        'contact' => 'Contact',
        'intervenant' => 'Candidature intervenant',
        'professionnel' => 'Demande professionnelle',
        'newsletter' => 'Inscription newsletter',
    ];

    protected $fillable = ['type', 'name', 'email', 'phone', 'company', 'subject', 'message', 'read_at'];

    protected function casts(): array
    {
        return ['read_at' => 'datetime'];
    }

    public function typeLabel(): string
    {
        return self::TYPES[$this->type] ?? $this->type;
    }
}
