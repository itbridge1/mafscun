<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
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
        DB::table('permissions')->truncate();
        Schema::enableForeignKeyConstraints();

        //SUPER-ADMIN GROUP
        $superAdminPermissions = $this->generatePermissionWithGroupHead(
            $this->superAdminGroupHeadArray(),$this->getSuperAdminGroup()
        );

        foreach($superAdminPermissions as $key => $permission){
            Permission::create($permission);
        }
        Permission::create([
            'group'=> 'SiteSetting',
            'name' => 'siteSetting-index',
            'display_name' => 'Index',
            'description' => 'SiteSetting Index'
        ]);
        Permission::create([
            'group'=> 'SiteSetting',
            'name' => 'siteSetting-update',
            'display_name' => 'Update',
            'description' => 'SiteSetting Update'
        ]);
        Permission::create([
            'group'=> 'Statistic',
            'name' => 'statistic-index',
            'display_name' => 'Index',
            'description' => 'Statistic Index'
        ]);
        Permission::create([
            'group'=> 'Statistic',
            'name' => 'statistic-create',
            'display_name' => 'Create',
            'description' => 'Statistic Create'
        ]);
    }

    public function generatePermissionWithGroupHead($groupHeadPermissionArray, $groupHeadNameArray)
    {
        $superAdminHead = $groupHeadPermissionArray;
        $groups = $groupHeadNameArray;
        $collection = [];
        foreach($groups as $key => $group){
            $collection[] = [
                $this->getIndexPermission($group),
                $this->getCreatePermission($group),
                $this->getEditPermission($group),
                $this->getDeletePermission($group)
            ];
        }

        $mergedArray = call_user_func_array('array_merge',$collection);

        $mergedSuperAdmin = array_merge([$superAdminHead],$mergedArray);

        return $mergedSuperAdmin;
    }

    public function getCreatePermission($group)
    {
        return [
            'group'=> ucfirst($group),
            'name' => $group.'-create',
            'display_name' => 'Create',
            'description' => ucfirst($group).' Add'
        ];
    }

    public function getIndexPermission($group)
    {
        return [
            'group'=> ucfirst($group),
            'name' => $group.'-index',
            'display_name' => 'Index',
            'description' => ucfirst($group).' Index'
        ];
    }

    public function getViewPermission($group)
    {
        return [
            'group'=> ucfirst($group),
            'name' => $group.'-view',
            'display_name' => 'View',
            'description' => 'View '.ucfirst($group),
        ];
    }

    public function getEditPermission($group)
    {
        return [
            'group'=>ucfirst($group),
            'name' => $group.'-edit',
            'display_name' => 'Edit',
            'description' => 'Edit '.ucfirst($group),
        ];
    }

    public function getDeletePermission($group)
    {
        return [
            'group'=>ucfirst($group),
            'name' => $group.'-delete',
            'display_name' => 'Delete',
            'description' => 'Delete '.ucfirst($group),
        ];
    }

    public function superAdminGroupHeadArray()
    {
        return [
            'group'=>'Super Admin Setup Permission',
            'name' => 'super-admin-index',
            'display_name' => 'Super Admin Setup Permission',
            'description' => 'Super Admin Setup Permission',
            'group_head' => '1'
        ];
    }

    public function getSuperAdminGroup()
    {
        return [
            'user',
            'serviceCategory',
            'resourceType',
            'category',
            'aboutUs',
            'service',
            'district',
            'municipality',
            'news',
            'notice',
            'resource',
            'slider',
            'gallery',
            'successStory',
            'hrm',
            'team',
            'menu',
            'affiliation',
            'contactForm',
            'faq',
            'memberOrganization',
        ];
    }
}
