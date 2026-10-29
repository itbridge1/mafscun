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
        Schema::table('event_bookings', function (Blueprint $table) {
            $table->boolean('is_member')->default(0)->after('user_id');
            $table->unsignedBigInteger('cooperative_id')->nullable()->after('is_member');
            $table->string('cooperative_name')->nullable()->after('cooperative_id');

            $table->foreign('cooperative_id')
                ->references('id')
                ->on('cooperative_informations')
                ->onDelete('set null');
        });

        Schema::table('agm_forms', function (Blueprint $table) {
            $table->string('cooperative_name')->nullable()->after('saccos_union');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('agm_forms', function (Blueprint $table) {
            $table->dropColumn('cooperative_name');
        });

        Schema::table('event_bookings', function (Blueprint $table) {
            $table->dropForeign(['cooperative_id']);
            $table->dropColumn(['is_member', 'cooperative_id', 'cooperative_name']);
        });
    }
};
