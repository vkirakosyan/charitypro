<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Enum\{Table, UserRole, PermissionRole};
use App\{Role, Permission};

class AddRolesAndPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $roleAdmin            = Role::firstOrCreate(['name' => UserRole::ADMINISTRATOR, 'label' => UserRole::ADMINISTRATOR]);
        $roleSimpleUser       = Role::firstOrCreate(['name' => UserRole::SIMPLE_USER, 'label' => UserRole::SIMPLE_USER]);
        $permissonAdmin       = Permission::firstOrCreate(['name' => PermissionRole::ADMINISTRATOR, 'label' => PermissionRole::ADMINISTRATOR]);
        $permissionSimpleUser = Permission::firstOrCreate(['name' => PermissionRole::SIMPLE_USER, 'label' => PermissionRole::SIMPLE_USER]);

        $permissonAdminId       = $permissonAdmin->id;
        $permissionSimpleUserId = $permissionSimpleUser->id;
        $roleAdminId            = $roleAdmin->id;
        $roleSimpleUserId       = $roleSimpleUser->id;

        $checkPermissionRoleAdmin = \DB::table(Table::PERMISSION_ROLE)->where([
                'permission_id' => $permissonAdminId,
                'role_id'       => $roleAdminId
            ])->first();

        $checkPermissionRoleSimpleUser = \DB::table(Table::PERMISSION_ROLE)->where([
                'permission_id' => $permissionSimpleUserId,
                'role_id'       => $roleSimpleUserId
            ])->first();

        if (is_null($checkPermissionRoleAdmin)) {
            \DB::table(Table::PERMISSION_ROLE)->insert([
                    'permission_id' => $permissonAdminId,
                    'role_id'       => $roleAdminId
                ]);
        }

        if (is_null($checkPermissionRoleSimpleUser)) {
            \DB::table(Table::PERMISSION_ROLE)->insert([
                    'permission_id' => $permissionSimpleUserId,
                    'role_id'       => $roleSimpleUserId
                ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
