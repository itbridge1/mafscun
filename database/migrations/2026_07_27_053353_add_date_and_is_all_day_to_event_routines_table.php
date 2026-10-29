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
            $table->date('date')->nullable()->after('event_id');
            $table->boolean('is_all_day')->default(false)->after('end_time');
        });
        
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE event_routines MODIFY start_time TIME NULL, MODIFY end_time TIME NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event_routines', function (Blueprint $table) {
            $table->dropColumn(['date', 'is_all_day']);
        });
        
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE event_routines MODIFY start_time TIME NOT NULL, MODIFY end_time TIME NOT NULL");
    }
};
