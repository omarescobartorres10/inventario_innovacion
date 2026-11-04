<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->json('permissions')->nullable()->after('role');
        });

        DB::table('users')->orderBy('id')->get()->each(function ($user): void {
            $permissions = $user->role === User::ROLE_SUPPLIES_OPERATOR
                ? [
                    User::PERMISSION_ITEMS,
                    User::PERMISSION_DELIVERY_ACTS,
                ]
                : User::defaultModulePermissions();

            DB::table('users')
                ->where('id', $user->id)
                ->update(['permissions' => json_encode($permissions)]);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('permissions');
        });
    }
};
