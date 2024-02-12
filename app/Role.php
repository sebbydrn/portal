<?php

namespace App;

use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{
    protected $primaryKey = 'role_id';

    protected $fillable = [
        'name', 'display_name', 'description'
    ];

    // Get user's roles
    function getRoles($userid) {
        $roles = \DB::table('role_user')
        ->leftJoin('roles', 'roles.id', '=', 'role_user.role_id')
        ->select('roles.*')
        ->where('role_user.user_id', $userid)
        ->get();

        return $roles;
    }

    // Get roles for creating users
    function getRoles2() {
        $roles = \DB::table('roles')->select('*')->get();
        return $roles;
    }

    function addRole($input) {
        \DB::beginTransaction();
        try {
            // Insert role
            $roleid = \DB::table('roles')
            ->insertGetId([
                'name' => $input['name'],
                'display_name' => $input['display_name'],
                'description' => $input['description']
            ]);

            // Insert role's permissions
            foreach ($input['permissions'] as $key => $value) {
                \DB::table('permission_role')
                ->insert([
                    'permission_id' => $value,
                    'role_id' => $roleid
                ]);
            }

            \DB::commit();
            return "success";
        } catch (\Exception $e) {
            \DB::rollback();
            return $e->getMessage();
        }
    }

    // Get role
    function getRole($id) {
        $roles = \DB::table('roles')->select('*')->where('role_id', $id)->first();
        return $roles;
    }

    // Update role
    function updateRole($id, $input) {
        \DB::beginTransaction();
        try {
            // Update role
            \DB::table('roles')
            ->where('role_id', $id)
            ->update([
                'name' => $input['name'],
                'display_name' => $input['display_name'],
                'description' => $input['description'],
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            // Delete role permissions
            \DB::table('permission_role')->where('role_id', $id)->delete();

            // Insert role's permissions
            foreach ($input['permissions'] as $key => $value) {
                \DB::table('permission_role')
                ->insert([
                    'permission_id' => $value,
                    'role_id' => $id
                ]);
            }

            \DB::commit();
            return "success";
        } catch (\Exception $e) {
            \DB::rollback();
            return $e->getMessage();
        }
    }

    // Delete role
    function deleteRole($id) {
        \DB::beginTransaction();
        try {
            // Delete from roles table
            \DB::table('roles')->where('role_id', $id)->delete();

            // Delete from permission_role table
            \DB::table('permission_role')->where('role_id', $id)->delete();

            // Delete from role_user table
            \DB::table('role_user')->where('role_id', $id)->delete();

            \DB::commit();
            return "success";
        } catch (\Exception $e) {
            \DB::rollback();
            return $e->getMessage();
        }
    }
}
