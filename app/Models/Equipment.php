<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Equipment extends Model
{
    use HasFactory;

    protected $table = 'equipments';

    protected $fillable = [
        'category',
        'name',
        'brand',
        'model',
        'serial_number',
        'asset_code',
        'processor',
        'ram',
        'storage',
        'operating_system',
        'status',
        'location_id',
        'assigned_user_id',
        'assigned_area_id',
        'assigned_area',
        'assigned_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'assigned_at' => 'date',
        ];
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    public function assignedArea(): BelongsTo
    {
        return $this->belongsTo(MunicipalArea::class, 'assigned_area_id');
    }

    public function movements(): HasMany
    {
        return $this->hasMany(EquipmentMovement::class);
    }
}
