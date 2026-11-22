<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('delivery_records', function (Blueprint $table) {
            $table->foreignId('from_location_id')->nullable()->after('from_location_name')->constrained('locations')->nullOnDelete();
            $table->foreignId('assigned_area_id')->nullable()->after('to_location_name')->constrained('municipal_areas')->nullOnDelete();
            $table->foreignId('location_id')->nullable()->after('assigned_area_id')->constrained('locations')->nullOnDelete();
        });

        Schema::table('delivery_record_lines', function (Blueprint $table) {
            $table->foreignId('item_id')->nullable()->after('description')->constrained('items')->nullOnDelete();
            $table->foreignId('equipment_id')->nullable()->after('item_id')->constrained('equipments')->nullOnDelete();
        });

        Schema::table('inventory_movements', function (Blueprint $table) {
            $table->foreignId('delivery_record_id')->nullable()->after('item_id')->constrained('delivery_records')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('inventory_movements', function (Blueprint $table) {
            $table->dropConstrainedForeignId('delivery_record_id');
        });

        Schema::table('delivery_record_lines', function (Blueprint $table) {
            $table->dropConstrainedForeignId('item_id');
            $table->dropConstrainedForeignId('equipment_id');
        });

        Schema::table('delivery_records', function (Blueprint $table) {
            $table->dropConstrainedForeignId('from_location_id');
            $table->dropConstrainedForeignId('assigned_area_id');
            $table->dropConstrainedForeignId('location_id');
        });
    }
};
