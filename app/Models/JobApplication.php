<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobApplication extends Model
{
    use HasFactory;

    public const STATUSES = [
        'new' => 'Nouvelle',
        'shortlisted' => 'Retenue',
        'interview' => 'Entretien',
        'rejected' => 'Refusée',
    ];

    protected $fillable = [
        'job_offer_id', 'name', 'email', 'phone', 'message',
        'cv_path', 'cv_original_name', 'status', 'read_at',
    ];

    protected function casts(): array
    {
        return ['read_at' => 'datetime'];
    }

    public function jobOffer(): BelongsTo
    {
        return $this->belongsTo(JobOffer::class);
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }
}
