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
        Schema::create('packs', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->unsignedBigInteger("mensuel");
            $table->unsignedBigInteger("annuel");
            $table->unsignedInteger("limite");
            $table->string("couleur");
            $table->string("text");
            $table->string("nom");
            $table->boolean("is_deleting")->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packs');
    }
};
