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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('prenom');
            $table->string('nom');
            $table->string('role');
            $table->string('sexe')->nullable();
            $table->string('adresse')->nullable();
            $table->string('specialite')->nullable();
            $table->string('matricule')->nullable();
            $table->string('image')->nullable();
            $table->unsignedBigInteger('campus_id')->nullable();
            $table->foreign('campus_id')->references("id")->on("campuses")->onDelete("cascade");
            $table->string('username')->unique();
            $table->string('tel')->unique();
            $table->string('email')->unique();
            $table->integer('acces')->default(1);
            $table->integer('statut')->default(0);
            $table->string('etablissement_precedant')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->boolean("is_deleting")->default(false);
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
