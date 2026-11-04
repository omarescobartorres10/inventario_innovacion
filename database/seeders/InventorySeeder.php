<?php

namespace Database\Seeders;

use App\Models\InventoryMovement;
use App\Models\Item;
use App\Models\Location;
use Illuminate\Database\Seeder;

class InventorySeeder extends Seeder
{
    public function run(): void
    {
        $bodega = Location::query()->firstOrCreate(
            ['code' => 'BODEGA'],
            ['name' => 'Bodega', 'description' => 'Almacen principal']
        );

        $office = Location::query()->firstOrCreate(
            ['code' => 'OFICINA'],
            ['name' => 'Oficina', 'description' => 'Stock de atencion inmediata']
        );

        $items = [
            [
                'name' => 'Tinta',
                'brand' => 'Brother',
                'model' => 'BTD60BK',
                'unit' => 'unidad',
                'movements' => [
                    ['type' => InventoryMovement::TYPE_ENTRY, 'quantity' => 6, 'notes' => 'Ingreso inicial a bodega', 'to_location_id' => $bodega->id],
                    ['type' => InventoryMovement::TYPE_TRANSFER, 'quantity' => 2, 'notes' => 'Reposicion para oficina', 'from_location_id' => $bodega->id, 'to_location_id' => $office->id],
                    ['type' => InventoryMovement::TYPE_EXIT, 'quantity' => 1, 'notes' => 'Uso en impresion', 'from_location_id' => $office->id],
                ],
            ],
            [
                'name' => 'Teclado',
                'brand' => 'Logitech',
                'model' => 'MK120',
                'unit' => 'unidad',
                'movements' => [
                    ['type' => InventoryMovement::TYPE_ENTRY, 'quantity' => 4, 'notes' => 'Compra para bodega', 'to_location_id' => $bodega->id],
                    ['type' => InventoryMovement::TYPE_TRANSFER, 'quantity' => 1, 'notes' => 'Entrega para oficina', 'from_location_id' => $bodega->id, 'to_location_id' => $office->id],
                    ['type' => InventoryMovement::TYPE_EXIT, 'quantity' => 1, 'notes' => 'Asignado a soporte', 'from_location_id' => $office->id],
                ],
            ],
        ];

        foreach ($items as $definition) {
            $item = Item::query()->firstOrCreate([
                'name' => $definition['name'],
                'brand' => $definition['brand'],
                'model' => $definition['model'],
            ], [
                'unit' => $definition['unit'],
            ]);

            if ($item->movements()->exists()) {
                continue;
            }

            foreach ($definition['movements'] as $movement) {
                $item->movements()->create([
                    ...$movement,
                    'performed_at' => now(),
                ]);
            }
        }
    }
}
