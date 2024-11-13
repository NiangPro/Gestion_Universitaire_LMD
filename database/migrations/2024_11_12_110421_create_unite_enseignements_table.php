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
        Schema::create('unite_enseignements', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->string("nom");
            $table->unsignedInteger("credit");
            $table->boolean("is_deleting")->default(false);
            $table->unsignedBigInteger('filiere_id')->nullable();
            $table->foreign('filiere_id')->references("id")->on("filieres")->onDelete("cascade");
            $table->unsignedBigInteger('niveau_etude_id')->nullable();
            $table->foreign('niveau_etude_id')->references("id")->on("niveau_etudes")->onDelete("cascade");
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
        Schema::dropIfExists('unite_enseignements');
    }
};
