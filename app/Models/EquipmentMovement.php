<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EquipmentMovement extends Model
{
    public const TYPE_WITHDRAWAL = 'withdrawal';

    public const TYPE_TRANSFER = 'transfer';

    public const TYPE_RETIREMENT = 'retirement';

    protected $fillable = [
        'equipment_id',
        'type',
        'performed_by',
        'from_location_id',
        'to_location_id',
        'from_area_id',
        'to_area_id',
        'assigned_user_id',
        'assigned_user_name',
        'assigned_area_name',
        'notes',
        'performed_at',
    ];

    protected function casts(): array
    {
        return [
            'performed_at' => 'datetime',
        ];
    }

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }

    public function performer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'performed_by');
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    public function fromLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'from_location_id');
    }

    public function toLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'to_location_id');
    }

    public function fromArea(): BelongsTo
    {
        return $this->belongsTo(MunicipalArea::class, 'from_area_id');
    }

    public function toArea(): BelongsTo
    {
        return $this->belongsTo(MunicipalArea::class, 'to_area_id');
    }
}
