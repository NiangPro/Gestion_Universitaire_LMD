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
        Schema::create('cours', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->unsignedBigInteger('professeur_id')->nullable();
            $table->foreign('professeur_id')->references("id")->on("users")->onDelete("cascade");
            $table->unsignedBigInteger('academic_year_id')->nullable();
            $table->foreign('academic_year_id')->references("id")->on("academic_years")->onDelete("cascade");
            $table->unsignedBigInteger('matiere_id')->nullable();
            $table->foreign('matiere_id')->references("id")->on("matieres")->onDelete("cascade");
            $table->boolean("is_deleting")->default(false);
            $table->unsignedBigInteger('semaine_id')->nullable();
            $table->foreign('semaine_id')->references("id")->on("semaines")->onDelete("cascade");
            $table->unsignedBigInteger('classe_id')->nullable();
            $table->foreign('classe_id')->references("id")->on("classes")->onDelete("cascade");
            $table->date('heure_debut');
            $table->date('heure_fin')->nullable();
            $table->enum('statut', ['en attente', 'encours', 'terminé'])->default('en attente');
            $table->unsignedBigInteger('campus_id')->nullable();
            $table->foreign('campus_id')->references("id")->on("campuses")->onDelete("cascade");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cours');
    }
};
