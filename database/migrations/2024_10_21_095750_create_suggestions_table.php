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
        Schema::create('suggestions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150)->nullable();
            $table->string('organization', 255)->nullable();
            $table->string('address', 255)->nullable();
            $table->enum('management_feedback', ['very_good', 'good', 'satisfactory', 'needs_improvement'])->nullable(false);
            $table->enum('notification', ['yes', 'no'])->nullable(false);
            $table->text('report_feedback')->nullable();
            $table->text('policy_feedback')->nullable();
            $table->text('other_suggestions')->nullable();
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
        Schema::dropIfExists('suggestions');
    }
};
