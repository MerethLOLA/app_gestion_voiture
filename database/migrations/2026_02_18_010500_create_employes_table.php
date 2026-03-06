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
        if (! Schema::hasTable('Employes')) {
            Schema::create('Employes', function (Blueprint $table) {
                $table->id();
                $table->string('nom', 50);
                $table->string('prenom', 50);
                $table->string('poste', 50)->nullable();
                $table->string('adresse', 200)->nullable();
                $table->string('email', 100)->nullable();
                $table->decimal('salaire', 10, 2)->nullable();
                $table->date('date_embauche')->nullable();
                $table->enum('contrat', ['stage', 'cdd', 'cdi'])->default('cdi');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Employes');
    }
};
