<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'brand',
        'model',
        'unit',
    ];

    public function movements(): HasMany
    {
        return $this->hasMany(InventoryMovement::class);
    }

    public function currentStock(): int
    {
        return (int) $this->movements()
            ->selectRaw("
                COALESCE(
                    SUM(
                        CASE
                            WHEN to_location_id IS NOT NULL THEN quantity
                            ELSE 0
                        END
                    ) -
                    SUM(
                        CASE
                            WHEN from_location_id IS NOT NULL THEN quantity
                            ELSE 0
                        END
                    ),
                0) AS stock
            ")
            ->value('stock');
    }
}
