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
        if (!Schema::hasTable('service_sub_categories')) {
            Schema::create('service_sub_categories', function (Blueprint $table) {
                $table->id();
                $table->foreignId('service_category_id')->constrained('service_categories')->onDelete('cascade');
                $table->string('title');
                $table->string('sub_title')->nullable();
                $table->string('slug')->unique();
                $table->string('photo')->nullable();
                $table->longText('description')->nullable();
                $table->integer('rank')->default(1);
                $table->boolean('status')->default(1);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('services')) {
            Schema::create('services', function (Blueprint $table) {
                $table->id();
                $table->foreignId('service_category_id')->constrained('service_categories')->onDelete('cascade');
                $table->foreignId('service_sub_category_id')->nullable()->constrained('service_sub_categories')->onDelete('set null');
                $table->string('title');
                $table->string('sub_title')->nullable();
                $table->string('slug')->unique();
                $table->string('photo')->nullable();
                $table->longText('description')->nullable();
                $table->integer('rank')->default(1);
                $table->boolean('status')->default(1);
                $table->timestamps();
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
        Schema::dropIfExists('services');
        Schema::dropIfExists('service_sub_categories');
    }
};
