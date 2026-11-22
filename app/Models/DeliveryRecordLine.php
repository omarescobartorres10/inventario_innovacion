<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeliveryRecordLine extends Model
{
    protected $fillable = [
        'delivery_record_id',
        'description',
        'item_id',
        'equipment_id',
        'quantity',
        'asset_code',
        'serial_number',
    ];

    public function deliveryRecord(): BelongsTo
    {
        return $this->belongsTo(DeliveryRecord::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }
}
