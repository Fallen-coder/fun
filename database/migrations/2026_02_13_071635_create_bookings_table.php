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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('flight_id');
            $table->string('passenger_name');
            $table->string('passenger_email');
            $table->integer('passenger_count');
            $table->decimal('total_price', 10, 2);
            $table->string('booking_reference')->unique();
            $table->enum('status', ['confirmed', 'cancelled'])->default('confirmed');
            $table->timestamps();
            
            $table->foreign('flight_id')->references('id')->on('flights')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
