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
        Schema::table('events', function (Blueprint $table) {
            $table->decimal('event_price', 10, 2)->default(0)->after('total_seats');
            $table->string('price_type')->default('per_person')->after('event_price');
            $table->integer('group_size')->nullable()->after('price_type');
        });

        Schema::table('event_routines', function (Blueprint $table) {
            $table->dropColumn(['event_price', 'price_type', 'group_size']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event_routines', function (Blueprint $table) {
            $table->decimal('event_price', 10, 2)->default(0);
            $table->string('price_type')->default('per_person');
            $table->integer('group_size')->nullable();
        });

        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['event_price', 'price_type', 'group_size']);
        });
    }
};
