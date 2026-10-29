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
        Schema::table('event_booking_participants', function (Blueprint $table) {
            $table->string('token')->nullable()->after('designation');
            $table->text('qr_code')->nullable()->after('token');
            $table->boolean('is_present')->default(false)->after('qr_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event_booking_participants', function (Blueprint $table) {
            $table->dropColumn(['token', 'qr_code', 'is_present']);
        });
    }
};
