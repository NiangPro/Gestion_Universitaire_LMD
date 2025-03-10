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
        Schema::create('tenues', function (Blueprint $table) {
            $table->id();
            $table->string("status");
            $table->string("mode_paiement");
            $table->decimal("montant");
            $table->foreignId("user_id")->constrained("users");
            $table->foreignId("campus_id")->constrained("campuses");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenues');
    }
};
