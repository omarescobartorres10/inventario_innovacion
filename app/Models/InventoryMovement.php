<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryMovement extends Model
{
    use HasFactory;

    public const TYPE_ENTRY = 'entry';
    public const TYPE_EXIT = 'exit';
    public const TYPE_TRANSFER = 'transfer';

    protected $fillable = [
        'item_id',
        'delivery_record_id',
        'from_location_id',
        'to_location_id',
        'type',
        'quantity',
        'notes',
        'performed_at',
    ];

    protected function casts(): array
    {
        return [
            'performed_at' => 'datetime',
            'quantity' => 'integer',
        ];
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function deliveryRecord(): BelongsTo
    {
        return $this->belongsTo(DeliveryRecord::class);
    }

    public function fromLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'from_location_id');
    }

    public function toLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'to_location_id');
    }
}
