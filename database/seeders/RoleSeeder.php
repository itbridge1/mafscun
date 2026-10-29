<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*
        * Only for test
        */
        Schema::disableForeignKeyConstraints();
        DB::table('roles')->truncate();
        DB::table('roles')->insert([
            [
                'name' => 'super-dev',
                'guard_name' => 'web',
                'display_name' => 'Super Dev',
                'type' => 'SuperDev',
                'description' => 'Super Dev',
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
            ],
            [
                'name' => 'super-admin',
                'guard_name' => 'web',
                'display_name' => 'Super Admin',
                'type' => 'SuperAdmin',
                'description' => 'Super Admin',
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
            ],
            [
                'name' => 'organization',
                'guard_name' => 'web',
                'display_name' => 'Organization',
                'type' => 'User',
                'description' => 'Organization',
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
            ],
            [
                'name' => 'user',
                'guard_name' => 'web',
                'display_name' => 'User',
                'description' => 'User',
                'type' => 'User',
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
            ],
        ]);
        Schema::enableForeignKeyConstraints();

        $adminRole = Role::findByName('super-admin');
        $permissions = Permission::pluck('id')->toArray();
        $adminRole->syncPermissions($permissions);
    }
}
