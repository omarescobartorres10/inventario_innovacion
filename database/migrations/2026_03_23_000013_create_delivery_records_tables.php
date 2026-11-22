<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('delivery_records', function (Blueprint $table) {
            $table->id();
            $table->string('type', 40);
            $table->date('delivery_date');
            $table->string('district', 120);
            $table->string('area_name', 255);
            $table->string('responsible_name', 255)->nullable();
            $table->string('receives', 255);
            $table->string('from_location_name', 120)->nullable();
            $table->string('to_location_name', 120)->nullable();
            $table->unsignedInteger('total_lines')->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('delivery_record_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_record_id')->constrained('delivery_records')->cascadeOnDelete();
            $table->string('description', 255);
            $table->unsignedInteger('quantity')->nullable();
            $table->string('asset_code', 120)->nullable();
            $table->string('serial_number', 120)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_record_lines');
        Schema::dropIfExists('delivery_records');
    }
};
