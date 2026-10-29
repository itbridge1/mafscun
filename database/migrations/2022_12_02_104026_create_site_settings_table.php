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
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('sub_title')->nullable();
            $table->string('site_logo')->nullable();
            $table->string('site_favicon')->nullable();
            $table->string('company_logo')->nullable();
            $table->string('stat_map')->nullable();
            $table->string('company_alias')->nullable();
            $table->string('pan_no')->nullable();
            $table->string('reg_no')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('location')->nullable();
            $table->text('footer_text')->nullable();
            $table->string('statistics_text')->nullable();
            $table->unsignedInteger('footer_1')->nullable();
            $table->unsignedInteger('footer_2')->nullable();
            $table->unsignedInteger('footer_3')->nullable();
            $table->unsignedInteger('footer_4')->nullable();
            $table->unsignedInteger('about_1')->nullable();
            $table->unsignedInteger('about_2')->nullable();
            $table->unsignedInteger('about_3')->nullable();
            $table->unsignedInteger('about_4')->nullable();
            $table->text('map')->nullable();
            $table->json('social')->nullable();
            $table->string('copyright_text')->nullable();
            $table->string('powered_by')->nullable();
            $table->string('powered_by_link')->nullable();
            $table->string('powered_by_logo')->nullable();
            $table->text('keywords')->nullable();
            $table->text('description')->nullable();
            $table->integer('view_count')->nullable();
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
        Schema::dropIfExists('site_settings');
    }
};
