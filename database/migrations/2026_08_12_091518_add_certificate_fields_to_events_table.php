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
            $table->string('certificate_bg_image')->nullable()->after('photo');
            $table->string('certificate_orientation')->default('horizontal')->after('certificate_bg_image');
            $table->json('certificate_settings')->nullable()->after('certificate_orientation');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['certificate_bg_image', 'certificate_orientation', 'certificate_settings']);
        });
    }
};
