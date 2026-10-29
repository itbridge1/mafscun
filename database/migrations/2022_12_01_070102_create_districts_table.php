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
        Schema::create('districts', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('nepali_name', 255)->nullable();
            $table->unsignedBigInteger('province_id');
            $table->string('headquarter', 255)->nullable();
            $table->bigInteger('area')->nullable()->default(12);
            $table->text('photo')->nullable();
            $table->boolean('status')->default(1);
            $table->timestamps();

            $table->foreign('province_id')->references('id')->on('provinces');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('districts');
    }
};
