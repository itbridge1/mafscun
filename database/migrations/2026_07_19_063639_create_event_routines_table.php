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
        Schema::create('event_routines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->time('start_time');
            $table->time('end_time');
            $table->text('about')->nullable();
            $table->decimal('event_price', 10, 2);
            // price_type: 'per_person' or 'per_group'
            $table->string('price_type')->default('per_person'); // per_person|per_group
            $table->integer('group_size')->nullable(); // e.g. 2 for "2 persons = X price"
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
        Schema::dropIfExists('event_routines');
    }
};
