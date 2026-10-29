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
    public function up(): void
    {
        Schema::table('event_routines', function (Blueprint $table) {
            $table->boolean('has_group_pricing')->default(false)->after('price');
            $table->integer('group_size')->nullable()->after('has_group_pricing');
            $table->decimal('group_price', 10, 2)->nullable()->after('group_size');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('event_routines', function (Blueprint $table) {
            $table->dropColumn(['has_group_pricing', 'group_size', 'group_price']);
        });
    }
};
