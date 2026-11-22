<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('equipment_movements', function (Blueprint $table) {
            $table->foreignId('assigned_user_id')->nullable()->after('to_area_id')->constrained('users')->nullOnDelete();
            $table->string('assigned_user_name', 150)->nullable()->after('assigned_user_id');
            $table->string('assigned_area_name', 180)->nullable()->after('assigned_user_name');
        });
    }

    public function down(): void
    {
        Schema::table('equipment_movements', function (Blueprint $table) {
            $table->dropConstrainedForeignId('assigned_user_id');
            $table->dropColumn(['assigned_user_name', 'assigned_area_name']);
        });
    }
};
