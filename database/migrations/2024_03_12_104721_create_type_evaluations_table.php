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
        Schema::create('type_evaluations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nom');
            $table->text('description')->nullable();
            $table->integer('is_deleting')->default(0);
            $table->unsignedBigInteger('campus_id')->nullable();
            $table->foreign('campus_id')->references('id')->on('campuses')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('type_evaluations');
    }
};