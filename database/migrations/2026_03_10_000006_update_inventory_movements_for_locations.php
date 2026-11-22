<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inventory_movements', function (Blueprint $table) {
            $table->enum('type', ['entry', 'exit', 'transfer'])->change();
            $table->foreignId('from_location_id')->nullable()->after('item_id')->constrained('locations')->nullOnDelete();
            $table->foreignId('to_location_id')->nullable()->after('from_location_id')->constrained('locations')->nullOnDelete();
        });

        if (!DB::table('locations')->where('code', 'BODEGA')->exists()) {
            DB::table('locations')->insert([
                'name' => 'Bodega',
                'code' => 'BODEGA',
                'description' => 'Almacen principal',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if (!DB::table('locations')->where('code', 'OFICINA')->exists()) {
            DB::table('locations')->insert([
                'name' => 'Oficina',
                'code' => 'OFICINA',
                'description' => 'Stock de atencion inmediata',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $bodegaId = DB::table('locations')->where('code', 'BODEGA')->value('id');

        if ($bodegaId) {
            DB::table('inventory_movements')
                ->where('type', 'entry')
                ->whereNull('to_location_id')
                ->update(['to_location_id' => $bodegaId]);

            DB::table('inventory_movements')
                ->where('type', 'exit')
                ->whereNull('from_location_id')
                ->update(['from_location_id' => $bodegaId]);
        }
    }

    public function down(): void
    {
        Schema::table('inventory_movements', function (Blueprint $table) {
            $table->dropConstrainedForeignId('from_location_id');
            $table->dropConstrainedForeignId('to_location_id');
        });

        Schema::table('inventory_movements', function (Blueprint $table) {
            $table->enum('type', ['entry', 'exit'])->change();
        });
    }
};
