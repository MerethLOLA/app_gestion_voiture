<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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

        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'username')) {
                $table->string('username')->nullable()->after('id');
                $table->unique('username');
            }

            if (! Schema::hasColumn('users', 'password_hash')) {
                $table->string('password_hash')->nullable();
            }

            if (! Schema::hasColumn('users', 'role')) {
                $table->string('role', 30)->default('user');
            }

            if (! Schema::hasColumn('users', 'statut')) {
                $table->string('statut', 30)->default('actif');
            }

            if (! Schema::hasColumn('users', 'last_login')) {
                $table->timestamp('last_login')->nullable();
            }
        });

        if (Schema::hasColumn('users', 'name') && Schema::hasColumn('users', 'username')) {
            DB::table('users')
                ->whereNull('username')
                ->whereNotNull('name')
                ->update(['username' => DB::raw('name')]);
        }

        if (Schema::hasColumn('users', 'password') && Schema::hasColumn('users', 'password_hash')) {
            DB::table('users')
                ->whereNull('password_hash')
                ->whereNotNull('password')
                ->update(['password_hash' => DB::raw('password')]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('users')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'last_login')) {
                $table->dropColumn('last_login');
            }

            if (Schema::hasColumn('users', 'statut')) {
                $table->dropColumn('statut');
            }

            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }

            if (Schema::hasColumn('users', 'password_hash')) {
                $table->dropColumn('password_hash');
            }

            if (Schema::hasColumn('users', 'username')) {
                $table->dropUnique('users_username_unique');
                $table->dropColumn('username');
            }
        });
    }
};
