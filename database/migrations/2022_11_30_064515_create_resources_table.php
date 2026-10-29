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
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('resource_type_id')->nullable();
            $table->string('title',155);
            $table->string('sub_title',155)->nullable();
            $table->string('slug')->unique();
            $table->string('type')->nullable();
            $table->string('file')->nullable();
            $table->mediumText('description')->nullable();
            $table->integer('rank')->nullable();
            $table->boolean('status')->default(0);
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
        Schema::dropIfExists('resources');
    }
};
