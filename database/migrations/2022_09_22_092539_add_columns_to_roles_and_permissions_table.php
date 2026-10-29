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
        Schema::table('roles', function (Blueprint $table) {
            $table->string('type')->nullable();
            $table->string('display_name')->nullable();
            $table->string('description')->nullable();
        });

        Schema::table('permissions', function (Blueprint $table) {
            $table->string('group')->nullable();
            $table->string('display_name')->nullable();
            $table->string('description')->nullable();
            $table->boolean('group_head')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('roles', function (Blueprint $table) {
            //
        });
        Schema::table('permissions', function (Blueprint $table) {
            //
        });
    }
};
