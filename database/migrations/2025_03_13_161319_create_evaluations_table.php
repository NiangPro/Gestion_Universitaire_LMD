<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->enum('type', ['devoir', 'examen', 'controle', 'rattrapage']);
            $table->date('date');
            $table->time('heure_debut');
            $table->time('heure_fin');
            $table->float('coefficient')->default(1);
            $table->unsignedBigInteger('cours_id')->nullable();
            $table->foreign('cours_id')->references('id')->on('cours')->onDelete('cascade');
            $table->unsignedBigInteger('campus_id')->nullable();
            $table->foreign('campus_id')->references('id')->on('campuses')->onDelete('cascade');
            $table->unsignedBigInteger('academic_year_id')->nullable();
            $table->foreign('academic_year_id')->references('id')->on('academic_years')->onDelete('cascade');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('salle_id')->nullable();
            $table->foreign('salle_id')->references('id')->on('salles')->onDelete('cascade');
            $table->enum('status', ['planifie', 'en_cours', 'termine'])->default('planifie');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('evaluations');
    }
};