<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipments', function (Blueprint $table) {
            $table->id();
            $table->string('category', 60);
            $table->string('name');
            $table->string('brand', 120)->nullable();
            $table->string('model', 120)->nullable();
            $table->string('serial_number', 150)->nullable()->unique();
            $table->string('asset_code', 120)->nullable()->unique();
            $table->string('processor')->nullable();
            $table->string('ram', 80)->nullable();
            $table->string('storage', 80)->nullable();
            $table->string('operating_system')->nullable();
            $table->string('status', 40)->default('available');
            $table->foreignId('location_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('assigned_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('assigned_area')->nullable();
            $table->date('assigned_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipments');
    }
};
