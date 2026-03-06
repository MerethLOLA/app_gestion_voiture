<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('users')) {
            return;
        }

        if (Schema::hasColumn('users', 'username') && Schema::hasColumn('users', 'password_hash')) {
            DB::table('users')->updateOrInsert(
                ['username' => 'LolaRebecca'],
                [
                    'email' => 'lolasemerethrebbecca@gmail.com',
                    'password_hash' => '$2a$12$ycF990kV4uB4rls4h1blY.QAAW0rRieI8amGVnOX/ECrdCH32Z5ra',
                    'role' => 'admin',
                    'statut' => 'actif',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        if (Schema::hasTable('permissions') && Schema::hasTable('role_permissions')) {
            $permissions = DB::table('permissions')->pluck('id');

            foreach ($permissions as $permissionId) {
                DB::table('role_permissions')->updateOrInsert(
                    [
                        'role' => 'admin',
                        'permission_id' => $permissionId,
                    ]
                );
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'username')) {
            DB::table('users')->where('username', 'LolaRebecca')->delete();
        }
    }
};
