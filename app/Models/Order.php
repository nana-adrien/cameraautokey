<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Order extends Model
{
    public const STATUSES = [
        'pending' => 'En attente',
        'assigned' => 'Technicien attribué',
        'in_progress' => 'Intervention en cours',
        'completed' => 'Terminée',
        'cancelled' => 'Annulée',
    ];

    protected $fillable = [
        'reference', 'vehicle_type', 'need', 'key_type', 'desired_key', 'quantity',
        'warranty_extension', 'emergency', 'plate', 'brand_model', 'year',
        'customer_name', 'customer_phone', 'customer_email', 'address', 'district', 'city',
        'notes', 'estimated_price', 'status', 'technician_id', 'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'warranty_extension' => 'boolean',
            'emergency' => 'boolean',
            'quantity' => 'integer',
            'year' => 'integer',
            'estimated_price' => 'integer',
            'completed_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Order $order) {
            $order->reference ??= 'CAK-'.now()->format('ymd').'-'.Str::upper(Str::random(5));
        });
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(Technician::class);
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function label(string $field): string
    {
        return config("pricing.{$field}s.{$this->{$field}}.label", $this->{$field});
    }
}
