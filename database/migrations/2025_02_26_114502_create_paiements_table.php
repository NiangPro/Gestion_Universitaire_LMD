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
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            $table->string("status");
            $table->string("type_paiement");
            $table->string("mode_paiement");
            $table->decimal("montant");
            $table->string("reference")->nullable();
            $table->string("observation")->nullable();
            $table->date("date_paiement")->nullable();
            $table->unsignedBigInteger("academic_year_id")->nullable();
            $table->foreign("academic_year_id")->references("id")->on("academic_years");
            $table->unsignedBigInteger("user_id")->nullable();
            $table->foreign("user_id")->references("id")->on("users");
            $table->unsignedBigInteger("campus_id")->nullable();
            $table->foreign("campus_id")->references("id")->on("campuses");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};
