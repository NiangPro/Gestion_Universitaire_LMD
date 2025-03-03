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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'email_verified_at')) {
                $table->timestamp('email_verified_at')->nullable();
            }
            if (!Schema::hasColumn('users', 'is_locked')) {
                $table->boolean('is_locked')->default(false);
            }
            if (!Schema::hasColumn('users', 'locked_at')) {
                $table->timestamp('locked_at')->nullable();
            }
            if (!Schema::hasColumn('users', 'failed_login_attempts')) {
                $table->integer('failed_login_attempts')->default(0);
            }
            if (!Schema::hasColumn('users', 'remember_token')) {
                $table->rememberToken();
            }
            if (!Schema::hasColumn('users', 'password_changed_at')) {
                $table->string('password_changed_at')->nullable();
            }
            if (!Schema::hasColumn('users', 'force_password_change')) {
                $table->boolean('force_password_change')->default(false);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'email_verified_at',
                'is_locked',
                'locked_at',
                'failed_login_attempts',
                'remember_token',
                'password_changed_at',
                'force_password_change'
            ]);
        });
    }
};
