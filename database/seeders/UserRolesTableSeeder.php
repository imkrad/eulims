<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserRolesTableSeeder extends Seeder
{
    /**
     * Auto generated seeder file.
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('user_roles')->delete();
        
        \DB::table('user_roles')->insert(array (
            0 => 
            array (
                'id' => 1,
                'laboratory_id' => 14,
                'laboratory_type' => NULL,
                'user_id' => 2,
                'role_id' => 3,
                'created_at' => '2024-07-30 11:04:54',
                'updated_at' => '2024-07-30 11:04:54',
            ),
            1 => 
            array (
                'id' => 2,
                'laboratory_id' => 14,
                'laboratory_type' => NULL,
                'user_id' => 3,
                'role_id' => 6,
                'created_at' => '2024-08-03 14:46:56',
                'updated_at' => '2024-08-03 14:46:56',
            ),
            2 => 
            array (
                'id' => 3,
                'laboratory_id' => 14,
                'laboratory_type' => NULL,
                'user_id' => 4,
                'role_id' => 5,
                'created_at' => '2024-08-03 14:47:33',
                'updated_at' => '2024-08-03 14:47:33',
            ),
            3 => 
            array (
                'id' => 4,
                'laboratory_id' => 14,
                'laboratory_type' => 3,
                'user_id' => 5,
                'role_id' => 2,
                'created_at' => '2024-09-02 10:54:01',
                'updated_at' => '2024-09-02 10:54:01',
            ),
            4 => 
            array (
                'id' => 5,
                'laboratory_id' => 14,
                'laboratory_type' => 1,
                'user_id' => 6,
                'role_id' => 2,
                'created_at' => '2024-09-02 10:54:37',
                'updated_at' => '2024-09-02 10:54:37',
            ),
            5 => 
            array (
                'id' => 6,
                'laboratory_id' => 14,
                'laboratory_type' => 2,
                'user_id' => 6,
                'role_id' => 2,
                'created_at' => '2024-09-02 10:54:37',
                'updated_at' => '2024-09-02 10:54:37',
            ),
            6 => 
            array (
                'id' => 7,
                'laboratory_id' => 14,
                'laboratory_type' => 1,
                'user_id' => 7,
                'role_id' => 4,
                'created_at' => '2024-09-02 11:24:53',
                'updated_at' => '2024-09-02 11:24:53',
            ),
        ));

        
    }
}