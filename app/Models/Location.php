<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
    ];

    public function incomingMovements(): HasMany
    {
        return $this->hasMany(InventoryMovement::class, 'to_location_id');
    }

    public function outgoingMovements(): HasMany
    {
        return $this->hasMany(InventoryMovement::class, 'from_location_id');
    }
}
