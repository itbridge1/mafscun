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
            $table->timestamp('certificate_sent_at')->nullable()->after('is_present');
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
            $table->dropColumn('certificate_sent_at');
        });
    }
};
