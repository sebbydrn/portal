<?php

namespace App;

use Zizaco\Entrust\EntrustPermission;


class Permission extends EntrustPermission
{
    protected $primaryKey = 'permission_id';

    protected $fillable = ['name', 'display_name'];

    function addPermission($input) {
        \DB::beginTransaction();
        try {
            // Insert permission
            \DB::table('permissions')
            ->insert([
                'name' => $input['name'],
                'display_name' => $input['display_name'],
                'description' => $input['description']
            ]);

            \DB::commit();
            return "success";
        } catch (\Exception $e) {
            \DB::rollback();
            return $e->getMessage();
        }
    }

    // Get all permissions
    function getPermissions() {
        $permissions = \DB::table('permissions')->select('*')->orderBy('display_name', 'asc')->get();
        return $permissions;
    }

    // Get permission
    function getPermission($id) {
        $permission = \DB::table('permissions')
        ->select('*')
        ->where('permission_id', $id)
        ->first();

        return $permission;
    }

    // Update permission
    function updatePermission($id, $input) {
        \DB::beginTransaction();
        try {
            \DB::table('permissions')
            ->where('permission_id', $id)
            ->update([
                'name' => $input['name'],
                'display_name' => $input['display_name'],
                'description' => $input['description'],
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            \DB::commit();
            return "success";
        } catch (\Exception $e) {
            \DB::rollback();
            return $e->getMessage();
        }
    }

    // Delete permission
    function deletePermission($id) {
        \DB::beginTransaction();
        try {
            \DB::table('permissions')->where('permission_id', $id)->delete();
            \DB::table('permission_role')->where('permission_id', $id)->delete();

            \DB::commit();
            return "success";
        } catch (\Exception $e) {
            \DB::rollback();
            return $e->getMessage();
        }
    }

    // Get permissions of role
    function getRolePermissions($id) {
        $permissions = \DB::table('permission_role')
        ->leftJoin('permissions', 'permissions.permission_id', '=', 'permission_role.permission_id')
        ->select('permissions.*')
        ->where('permission_role.role_id', $id)
        ->orderBy('permissions.display_name', 'asc')
        ->get();

        return $permissions;
    }
}
