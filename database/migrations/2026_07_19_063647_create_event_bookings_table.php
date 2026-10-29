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
        Schema::create('event_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->foreignId('event_routine_id')->nullable()->constrained('event_routines')->onDelete('cascade');
            $table->integer('total_participants');
            $table->decimal('total_amount', 10, 2);
            // status: 0=pending, 1=approved, 2=rejected
            $table->tinyInteger('status')->default(0);
            $table->string('booking_token')->unique(); // unique token for QR
            $table->string('qr_code')->nullable();     // generated QR image path
            $table->string('contact_email');
            $table->string('contact_phone')->nullable();
            // attendance: 0=not present, 1=present
            $table->tinyInteger('is_present')->default(0);
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
        Schema::dropIfExists('event_bookings');
    }
};
