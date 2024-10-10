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
        Schema::create('messages', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->text("content");
            $table->string("image")->nullable();
            $table->integer("is_read")->default(0);
            $table->unsignedBigInteger('sender_id')->nullable();
            $table->foreign('sender_id')->references("id")->on("users")->onDelete("cascade");
            $table->unsignedBigInteger('receiver_id')->nullable();
            $table->foreign('receiver_id')->references("id")->on("users")->onDelete("cascade");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
