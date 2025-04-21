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
        Schema::create('notes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('matiere_id')->nullable();
            $table->foreign('matiere_id')->references('id')->on('matieres')->onDelete('cascade');
            $table->unsignedBigInteger('etudiant_id')->nullable();
            $table->foreign('etudiant_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('academic_year_id')->nullable();
            $table->foreign('academic_year_id')->references('id')->on('academic_years')->onDelete('cascade');
            $table->unsignedBigInteger('type_evaluation_id')->nullable();
            $table->foreign('type_evaluation_id')->references('id')->on('type_evaluations')->onDelete('cascade');
            $table->decimal('note', 5, 2);
            $table->string('observation')->nullable();
            $table->unsignedBigInteger('campus_id')->nullable();
            $table->foreign('campus_id')->references('id')->on('campuses')->onDelete('cascade');
            $table->unsignedBigInteger('semestre_id');
            $table->foreign('semestre_id')->references('id')->on('semestres')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};
