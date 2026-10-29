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
        Schema::table('event_routines', function (Blueprint $table) {
            $table->decimal('price', 10, 2)->default(0.00)->after('about');
        });

        Schema::table('event_bookings', function (Blueprint $table) {
            $table->dropForeign(['event_routine_id']);
            $table->foreign('event_routine_id')
                ->references('id')
                ->on('event_routines')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event_bookings', function (Blueprint $table) {
            $table->dropForeign(['event_routine_id']);
            $table->foreign('event_routine_id')
                ->references('id')
                ->on('event_routines')
                ->onDelete('cascade');
        });

        Schema::table('event_routines', function (Blueprint $table) {
            $table->dropColumn('price');
        });
    }
};
