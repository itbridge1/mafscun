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
        Schema::create('member_organizations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->foreignId('municipality_id')->constrained('municipalities')->cascadeOnDelete();
            $table->foreignId('district_id')->constrained('municipalities')->cascadeOnDelete();
            $table->string('bod_chief')->nullable();
            $table->string('manager')->nullable();
            $table->string('contact_no')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('total_shareholder')->nullable();
            $table->string('join_year')->nullable();
            $table->boolean('status')->nullable();
            $table->integer('rank')->nullable();
            $table->string('association')->nullable();
            $table->string('pan_no')->nullable();
            $table->string('reg_no')->nullable();
            $table->string('photo')->nullable();
            $table->text('description')->nullable();
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
        Schema::dropIfExists('member_organizations');
    }
};
