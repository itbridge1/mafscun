<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->decimal('non_member_price', 10, 2)->nullable()->after('event_price');
            $table->decimal('non_member_group_price', 10, 2)->nullable()->after('group_price');
        });

        Schema::table('event_routines', function (Blueprint $table) {
            $table->decimal('non_member_price', 10, 2)->nullable()->after('price');
            $table->decimal('non_member_group_price', 10, 2)->nullable()->after('group_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['non_member_price', 'non_member_group_price']);
        });

        Schema::table('event_routines', function (Blueprint $table) {
            $table->dropColumn(['non_member_price', 'non_member_group_price']);
        });
    }
};
