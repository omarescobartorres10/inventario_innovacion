<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('delivery_records', function (Blueprint $table) {
            $table->timestamp('annulled_at')->nullable()->after('created_by');
            $table->foreignId('annulled_by')->nullable()->after('annulled_at')->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('delivery_records', function (Blueprint $table) {
            $table->dropConstrainedForeignId('annulled_by');
            $table->dropColumn('annulled_at');
        });
    }
};
