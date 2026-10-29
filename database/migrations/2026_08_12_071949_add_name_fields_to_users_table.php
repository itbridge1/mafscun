<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::statement('ALTER TABLE users ADD COLUMN first_name VARCHAR(255) NULL AFTER name');
        DB::statement('ALTER TABLE users ADD COLUMN middle_name VARCHAR(255) NULL AFTER first_name');
        DB::statement('ALTER TABLE users ADD COLUMN last_name VARCHAR(255) NULL AFTER middle_name');
        DB::statement('ALTER TABLE users MODIFY name VARCHAR(255) NULL');
    }

    public function down()
    {
        DB::statement('ALTER TABLE users MODIFY name VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE users DROP COLUMN first_name');
        DB::statement('ALTER TABLE users DROP COLUMN middle_name');
        DB::statement('ALTER TABLE users DROP COLUMN last_name');
    }
};
