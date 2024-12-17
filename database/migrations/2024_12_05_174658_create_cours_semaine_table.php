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
        Schema::create('cours_semaine', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->unsignedBigInteger('cours_id')->nullable();
            $table->foreign('cours_id')->references("id")->on("cours")->onDelete("cascade");
            $table->unsignedBigInteger('semaine_id')->nullable();
            $table->foreign('semaine_id')->references("id")->on("semaines")->onDelete("cascade");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cours_semaine');
    }
};
