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
        Schema::create('municipalities', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('nepali_name', 255)->nullable();
            $table->string('district_name', 255)->nullable();
            $table->text('link')->nullable();
            $table->text('photo')->nullable();
            $table->unsignedBigInteger('district_id');
            $table->unsignedBigInteger('province_id')->nullable();
            $table->boolean('status')->default(1);
            $table->timestamps();
            $table->foreign('district_id')->references('id')->on('districts')->cascadeOnDelete();
            $table->foreign('province_id')->references('id')->on('provinces')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('municipalities');
    }
};
