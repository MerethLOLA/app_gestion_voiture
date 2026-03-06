<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'id_employe')) {
            Schema::table('users', function (Blueprint $table) {
                $table->unsignedBigInteger('id_employe')->nullable()->after('statut');
                $table->unique('id_employe');
                $table->index('id_employe');
            });
        }

        if (Schema::hasTable('Employes') && ! $this->hasForeignKey('users', 'users_id_employe_foreign')) {
            Schema::table('users', function (Blueprint $table) {
                $table->foreign('id_employe')->references('id')->on('Employes')->cascadeOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if ($this->hasForeignKey('users', 'users_id_employe_foreign')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropForeign('users_id_employe_foreign');
            });
        }

        if (Schema::hasColumn('users', 'id_employe')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropUnique('users_id_employe_unique');
                $table->dropIndex('users_id_employe_index');
                $table->dropColumn('id_employe');
            });
        }
    }

    private function hasForeignKey(string $table, string $foreignKey): bool
    {
        $connection = Schema::getConnection();
        $database = $connection->getDatabaseName();

        return (bool) $connection
            ->table('information_schema.TABLE_CONSTRAINTS')
            ->where('CONSTRAINT_SCHEMA', $database)
            ->where('TABLE_NAME', $table)
            ->where('CONSTRAINT_NAME', $foreignKey)
            ->where('CONSTRAINT_TYPE', 'FOREIGN KEY')
            ->exists();
    }
};
