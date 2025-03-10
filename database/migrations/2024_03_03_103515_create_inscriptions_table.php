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
        Schema::create('inscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('campus_id')->constrained()->onDelete('cascade');
            $table->foreignId('academic_year_id')->constrained('academic_years')->onDelete('cascade');
            $table->foreignId('classe_id')->constrained()->onDelete('cascade');
            $table->foreignId('tuteur_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('medical_id')->nullable()->constrained('medicals')->onDelete('set null');
            $table->string('relation');
            $table->decimal('montant', 10, 2);
            $table->decimal('restant', 10, 2)->default(0);
            $table->enum('tenue', ['Payé', 'Avance', 'Pas encore'])->default('Pas encore');
            $table->text('commentaire')->nullable();
            $table->enum('status', ['en_cours', 'termine', 'annule'])->default('en_cours');
            $table->date('date_inscription');
            $table->timestamps();

            // Index pour améliorer les performances des requêtes
            $table->index(['user_id', 'academic_year_id', 'classe_id']);
            $table->index(['campus_id', 'academic_year_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inscriptions');
    }
};
