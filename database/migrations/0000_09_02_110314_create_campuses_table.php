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
        Schema::create('campuses', function (Blueprint $table) {
            $table->id();
            $table->string("nom");
            $table->string("tel");
            $table->string("adresse");
            $table->date("date_fermeture");
            $table->string("email");
            $table->integer("statut")->default(1);
            $table->string("image")->nullable();
            $table->unsignedBigInteger('pack_id')->nullable();
            $table->foreign('pack_id')->references("id")->on("packs")->onDelete("cascade");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campuses');
    }
};
