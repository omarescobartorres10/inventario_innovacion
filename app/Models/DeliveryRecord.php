<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DeliveryRecord extends Model
{
    public const TYPE_SUPPLIES = 'supplies';

    public const TYPE_EQUIPMENTS = 'equipments';

    protected $fillable = [
        'type',
        'delivery_date',
        'district',
        'area_name',
        'responsible_name',
        'receives',
        'from_location_name',
        'from_location_id',
        'to_location_name',
        'assigned_area_id',
        'location_id',
        'total_lines',
        'created_by',
        'annulled_at',
        'annulled_by',
    ];

    protected function casts(): array
    {
        return [
            'delivery_date' => 'date',
            'annulled_at' => 'datetime',
        ];
    }

    public function lines(): HasMany
    {
        return $this->hasMany(DeliveryRecordLine::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function annulledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'annulled_by');
    }

    public function fromLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'from_location_id');
    }

    public function assignedArea(): BelongsTo
    {
        return $this->belongsTo(MunicipalArea::class, 'assigned_area_id');
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'location_id');
    }
}
