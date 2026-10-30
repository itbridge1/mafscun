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
        if (!Schema::hasTable('resource_sub_types')) {
            Schema::create('resource_sub_types', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('resource_type_id');
                $table->string('title');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->integer('rank')->default(1);
                $table->boolean('status')->default(true);
                $table->timestamps();

                $table->foreign('resource_type_id')
                    ->references('id')
                    ->on('resource_types')
                    ->onDelete('cascade');
            });
        }

        if (Schema::hasTable('resources') && !Schema::hasColumn('resources', 'resource_sub_type_id')) {
            Schema::table('resources', function (Blueprint $table) {
                $table->unsignedBigInteger('resource_sub_type_id')->nullable()->after('resource_type_id');

                $table->foreign('resource_sub_type_id')
                    ->references('id')
                    ->on('resource_sub_types')
                    ->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('resources') && Schema::hasColumn('resources', 'resource_sub_type_id')) {
            Schema::table('resources', function (Blueprint $table) {
                $table->dropForeign(['resource_sub_type_id']);
                $table->dropColumn('resource_sub_type_id');
            });
        }

        Schema::dropIfExists('resource_sub_types');
    }
};
