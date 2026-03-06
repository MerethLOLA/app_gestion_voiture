<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVoituresTable extends Migration
{
    public function up()
    {
        Schema::create('Voitures', function (Blueprint $table) {
            $table->id();
            $table->string('marque', 50);
            $table->string('model', 50);
            $table->year('annee');
            $table->string('couleur', 30)->nullable();
            $table->decimal('prix', 15, 2);
            $table->integer('kilometrage')->nullable();
            $table->string('numero_de_chassis', 50)->unique();
            $table->enum('etat', ['neuf', 'occasion'])->default('occasion');
            $table->date('date_d_acquisition')->nullable();
            $table->foreignId('id_categorie')->constrained('categorie_voiture');
            $table->foreignId('id_fournisseur')->constrained('fournisseurs');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('Voitures');
    }
}
