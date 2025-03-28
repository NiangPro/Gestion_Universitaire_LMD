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
        Schema::table('permissions', function (Blueprint $table) {
            $table->string('role')->nullable()->after('user_id');
            $table->unsignedBigInteger('campus_id')->nullable()->after('role');
            $table->foreign('campus_id')->references('id')->on('campuses')->onDelete('cascade');
            
            // Index pour la recherche par rôle
            $table->index(['role', 'campus_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropColumn('role');
            $table->dropColumn('campus_id');
            $table->dropIndex(['role', 'campus_id']);
        });
    }
};