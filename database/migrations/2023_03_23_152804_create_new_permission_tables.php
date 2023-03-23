<?php

use App\Uavsms\UserRole\Permission;
use App\Uavsms\UserRole\Role;
use App\Uavsms\UserRole\RolePermission;
use App\UserRole;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewPermissionTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id')->startingValue(1000);
            $table->string('title', 200)->index();
            $table->boolean('locked');
            $table->timestamps();
        });

        Schema::create('role_permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('role_id')->index();
            $table->string('permission', 200);
            $table->timestamps();
        });

        $roles = UserRole::$roles;

        foreach ($roles as $value) {
            $role = new Role();

            if (isset($value['id'])) {
                $role->id = $value['id'];
            }

            $role->title = $value['title'];
            $role->locked = $value['locked'];

            $role->save();

            foreach ($value['permissions'] as $permission) {
                if (Permission::exists($permission)) {
                    $new_permission = new RolePermission();

                    $new_permission->role_id = $role->id;
                    $new_permission->permission = $permission;

                    $new_permission->save();
                }
            }
        }

        DB::update('ALTER TABLE roles AUTO_INCREMENT = 1000;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');

        Schema::dropIfExists('role_permissions');
    }
}
