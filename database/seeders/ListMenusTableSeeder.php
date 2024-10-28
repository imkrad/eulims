<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ListMenusTableSeeder extends Seeder
{
    /**
     * Auto generated seeder file.
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('list_menus')->delete();
        
        \DB::table('list_menus')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Dashboard',
                'icon' => 'ri-apps-line',
                'route' => '/executive',
                'path' => 'Modules/Executive/Dashboard',
                'group' => 'Menu',
                'module' => 'Executive',
                'order' => 1,
                'has_child' => 0,
                'is_mother' => 1,
                'is_active' => 1,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'User Management',
                'icon' => 'ri-team-fill ',
                'route' => '/executive/users',
                'path' => 'Modules/Executive/Users',
                'group' => 'Menu',
                'module' => 'Executive',
                'order' => 2,
                'has_child' => 0,
                'is_mother' => 1,
                'is_active' => 1,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Role Management',
                'icon' => 'ri-shield-check-line',
                'route' => '/executive/roles',
                'path' => 'Modules/Executive/Roles',
                'group' => 'Menu',
                'module' => 'Executive',
                'order' => 3,
                'has_child' => 0,
                'is_mother' => 1,
                'is_active' => 1,
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Menu Management',
                'icon' => 'ri-list-check',
                'route' => '/executive/menus',
                'path' => 'Modules/Executive/Menus',
                'group' => 'Menu',
                'module' => 'Executive',
                'order' => 4,
                'has_child' => 0,
                'is_mother' => 1,
                'is_active' => 1,
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Authentication Logs',
                'icon' => 'ri-time-fill',
                'route' => '/executive/authentications',
                'path' => 'Modules/Executive/Authentications',
                'group' => 'Menu',
                'module' => 'Executive',
                'order' => 5,
                'has_child' => 0,
                'is_mother' => 1,
                'is_active' => 1,
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Activity Logs',
                'icon' => 'ri-history-line',
                'route' => '/executive/activities',
                'path' => 'Modules/Executive/Activities',
                'group' => 'Menu',
                'module' => 'Executive',
                'order' => 6,
                'has_child' => 0,
                'is_mother' => 1,
                'is_active' => 1,
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'Backup and Restore',
                'icon' => 'ri-server-line ',
                'route' => '/executive/backups',
                'path' => 'Modules/Executive/Backups',
                'group' => 'Menu',
                'module' => 'Executive',
                'order' => 7,
                'has_child' => 0,
                'is_mother' => 1,
                'is_active' => 1,
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'Dashboard',
                'icon' => 'ri-apps-line',
                'route' => '/references',
                'path' => 'Modules/References/Dashboard',
                'group' => 'Menu',
                'module' => 'Reference',
                'order' => 1,
                'has_child' => 0,
                'is_mother' => 1,
                'is_active' => 1,
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'Agencies',
                'icon' => 'ri-hotel-fill',
                'route' => '/references/agencies',
                'path' => 'Modules/References/Agencies',
                'group' => 'Menu',
                'module' => 'Reference',
                'order' => 2,
                'has_child' => 0,
                'is_mother' => 1,
                'is_active' => 1,
            ),
            9 => 
            array (
                'id' => 10,
                'name' => 'Privileges',
                'icon' => 'ri-hand-coin-fill',
                'route' => '/references/privileges',
                'path' => 'Modules/References/Privileges',
                'group' => 'Menu',
                'module' => 'Reference',
                'order' => 3,
                'has_child' => 0,
                'is_mother' => 1,
                'is_active' => 1,
            ),
            10 => 
            array (
                'id' => 11,
                'name' => 'Programs',
                'icon' => 'ri-bill-fill',
                'route' => '/references/programs',
                'path' => 'Modules/References/Programs',
                'group' => 'Menu',
                'module' => 'Reference',
                'order' => 4,
                'has_child' => 0,
                'is_mother' => 1,
                'is_active' => 1,
            ),
            11 => 
            array (
                'id' => 12,
                'name' => 'Statuses',
                'icon' => 'ri-pages-fill',
                'route' => '/references/statuses',
                'path' => 'Modules/References/Statuses',
                'group' => 'Menu',
                'module' => 'Reference',
                'order' => 5,
                'has_child' => 0,
                'is_mother' => 1,
                'is_active' => 1,
            ),
            12 => 
            array (
                'id' => 13,
                'name' => 'Locations',
                'icon' => 'ri-earth-fill',
                'route' => NULL,
                'path' => 'Modules/References/Locations',
                'group' => 'List',
                'module' => 'Reference',
                'order' => 6,
                'has_child' => 1,
                'is_mother' => 1,
                'is_active' => 1,
            ),
            13 => 
            array (
                'id' => 14,
                'name' => 'Regions',
                'icon' => NULL,
                'route' => '/references/regions',
                'path' => '/references/regions',
                'group' => 'Locations',
                'module' => 'Reference',
                'order' => 1,
                'has_child' => 0,
                'is_mother' => 0,
                'is_active' => 1,
            ),
            14 => 
            array (
                'id' => 15,
                'name' => 'Provinces',
                'icon' => NULL,
                'route' => '/references/provinces',
                'path' => '/references/provinces',
                'group' => 'Locations',
                'module' => 'Reference',
                'order' => 2,
                'has_child' => 0,
                'is_mother' => 0,
                'is_active' => 1,
            ),
            15 => 
            array (
                'id' => 16,
                'name' => 'Municipalities',
                'icon' => NULL,
                'route' => '/references/municipalities',
                'path' => '/references/municipalities',
                'group' => 'Locations',
                'module' => 'Reference',
                'order' => 3,
                'has_child' => 0,
                'is_mother' => 0,
                'is_active' => 1,
            ),
            16 => 
            array (
                'id' => 17,
                'name' => 'Barangays',
                'icon' => NULL,
                'route' => '/references/barangays',
                'path' => '/references/barangays',
                'group' => 'Locations',
                'module' => 'Reference',
                'order' => 4,
                'has_child' => 0,
                'is_mother' => 0,
                'is_active' => 1,
            ),
            17 => 
            array (
                'id' => 18,
                'name' => 'Dropdowns',
                'icon' => 'ri-list-unordered',
                'route' => NULL,
                'path' => 'Modules/References/Dropdowns',
                'group' => 'List',
                'module' => 'Reference',
                'order' => 7,
                'has_child' => 1,
                'is_mother' => 1,
                'is_active' => 1,
            ),
            18 => 
            array (
                'id' => 19,
                'name' => 'Schools',
                'icon' => NULL,
                'route' => '/references/schools',
                'path' => '/references/schools',
                'group' => 'Dropdowns',
                'module' => 'Reference',
                'order' => 1,
                'has_child' => 0,
                'is_mother' => 0,
                'is_active' => 1,
            ),
            19 => 
            array (
                'id' => 20,
                'name' => 'Courses',
                'icon' => NULL,
                'route' => '/references/courses',
                'path' => '/references/courses',
                'group' => 'Dropdowns',
                'module' => 'Reference',
                'order' => 2,
                'has_child' => 0,
                'is_mother' => 0,
                'is_active' => 1,
            ),
            20 => 
            array (
                'id' => 21,
                'name' => 'Category',
                'icon' => NULL,
                'route' => '/references/categories',
                'path' => '/references/categories',
                'group' => 'Dropdowns',
                'module' => 'Reference',
                'order' => 3,
                'has_child' => 0,
                'is_mother' => 0,
                'is_active' => 1,
            ),
        ));

        
    }
}