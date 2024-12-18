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
        Schema::create('classes', function (Blueprint $table) {
            $table->bigIncrements("id"); 
            $table->string("nom"); 
            $table->unsignedBigInteger('filiere_id')->nullable();
            $table->foreign('filiere_id')->references("id")->on("filieres")->onDelete("cascade");
            $table->unsignedBigInteger('campus_id')->nullable();
            $table->foreign('campus_id')->references("id")->on("campuses")->onDelete("cascade");
            $table->unsignedBigInteger('academic_year_id')->nullable();
            $table->foreign('academic_year_id')->references("id")->on("academic_years")->onDelete("cascade");
            $table->boolean("is_deleting")->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};
