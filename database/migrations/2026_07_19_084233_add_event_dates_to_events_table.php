<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->date('start_date')->nullable()->after('total_seats');
            $table->date('end_date')->nullable()->after('start_date');
            $table->string('start_date_bs', 20)->nullable()->after('end_date');
            $table->string('end_date_bs', 20)->nullable()->after('start_date_bs');
        });
    }

    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['start_date', 'end_date', 'start_date_bs', 'end_date_bs']);
        });
    }
};
