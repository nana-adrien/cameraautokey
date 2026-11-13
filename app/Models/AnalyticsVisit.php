<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AnalyticsVisit extends Model
{
    protected $fillable = [
        'uuid', 'session_id', 'ip_hash', 'country_code', 'city', 'device', 'browser', 'os',
        'referrer_host', 'landing_path', 'user_agent', 'started_at', 'last_seen_at',
        'converted', 'last_step', 'converted_at',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'last_seen_at' => 'datetime',
            'converted' => 'boolean',
            'converted_at' => 'datetime',
            'last_step' => 'integer',
        ];
    }

    public function pageViews(): HasMany
    {
        return $this->hasMany(AnalyticsPageView::class, 'visit_id');
    }

    public function events(): HasMany
    {
        return $this->hasMany(AnalyticsEvent::class, 'visit_id');
    }
}
