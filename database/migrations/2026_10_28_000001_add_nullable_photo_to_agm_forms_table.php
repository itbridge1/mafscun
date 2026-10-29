<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('agm_forms')) {
            return;
        }

        if (!Schema::hasColumn('agm_forms', 'photo')) {
            Schema::table('agm_forms', function (Blueprint $table) {
                $table->string('photo')->nullable();
            });
            return;
        }

        $driver = DB::connection()->getDriverName();
        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE `agm_forms` MODIFY `photo` VARCHAR(255) NULL');
        } elseif ($driver === 'pgsql') {
            DB::statement('ALTER TABLE agm_forms ALTER COLUMN photo DROP NOT NULL');
        }
    }

    public function down(): void
    {
        // Keep this optional upload column in place so existing AGM submissions remain intact.
    }
};
