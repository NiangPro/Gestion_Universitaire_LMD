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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('campus_id')->nullable();
            $table->foreign('campus_id')->references("id")->on("campuses")->onDelete("cascade");
            $table->unsignedBigInteger('pack_id')->nullable();
            $table->foreign('pack_id')->references("id")->on("packs")->onDelete("cascade");
            $table->date('start_date');
            $table->date('end_date');
            $table->string('status'); // active, expired, cancelled
            $table->string('payment_status'); // pending, paid, failed
            $table->decimal('amount_paid', 10, 2);
            $table->string('payment_method')->nullable();
            $table->string('payment_reference')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
