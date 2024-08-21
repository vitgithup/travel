<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('flight_id')->constrained()->onDelete('cascade');
            $table->integer('number_seats')->default(1);
            $table->decimal('total_cost', 10, 2);
            $table->string('passenger_name');
            $table->string('passenger_email');
            $table->text('credit_card_number');
            $table->text('credit_card_expiry_date');
            $table->text('credit_card_cvv');
            $table->enum('status', ['confirmed', 'cancelled', 'pending'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookings');
    }
};
