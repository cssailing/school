<?php

class PermissionsTableSeeder extends Seeder {

    public function run()
    {
        DB::table('permissions')->delete();


        $permissions = array(
            //designed for superadmins and admins
            array(
                'name'              => 'manage_blogs',
                'display_name'      => 'manage blogs'
            ),
            //designed for all users
            array(
                'name'              => 'manage_own_blogs',
                'display_name'      => 'manage own blogs'
            ),

            //designed for superadmins and admins
            array(
                'name'              => 'manage_comments',
                'display_name'      => 'manage comments'
            ),
            //designed for all users
            array(
                'name'              => 'post_comment',
                'display_name'      => 'post comment'
            ),

            //designed for superadmin only
            array(
                'name'              => 'manage_users',
                'display_name'      => 'manage users'
            ),
            array(
                'name'              => 'manage_roles',
                'display_name'      => 'manage roles'
            ),

        );

        DB::table('permissions')->insert( $permissions );

        DB::table('permission_role')->delete();

        $permissions = array(
            array(
                'role_id'      => 1,
                'permission_id' => 1
            ),
            array(
                'role_id'      => 1,
                'permission_id' => 2
            ),
            array(
                'role_id'      => 1,
                'permission_id' => 3
            ),
            array(
                'role_id'      => 1,
                'permission_id' => 4
            ),
            array(
                'role_id'      => 1,
                'permission_id' => 5
            ),
            array(
                'role_id'      => 1,
                'permission_id' => 6
            ),
            array(
                'role_id'      => 3,
                'permission_id' => 6
            ),
            array(
                'role_id'      => 3,
                'permission_id' => 3
            )
        );

        DB::table('permission_role')->insert( $permissions );
    }

}
