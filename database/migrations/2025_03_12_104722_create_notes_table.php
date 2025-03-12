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
            $table->unsignedBigInteger('etudiant_id')->nullable();
            $table->foreign('etudiant_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('cours_id')->nullable();
            $table->foreign('cours_id')->references('id')->on('cours')->onDelete('cascade');
            $table->string('type_evaluation');
            $table->decimal('note', 5, 2);
            $table->decimal('coefficient', 5, 2);
            $table->string('observation')->nullable();
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
