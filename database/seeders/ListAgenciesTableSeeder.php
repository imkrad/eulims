<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ListAgenciesTableSeeder extends Seeder
{
    /**
     * Auto generated seeder file.
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('list_agencies')->delete();
        
        \DB::table('list_agencies')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Department of Science and Technology - I',
                'code' => 'R1',
                'acronym' => 'DOST-I',
                'avatar' => 'avatar.jpg',
                'is_active' => 1,
                'region_code' => '010000000',
                'created_at' => '2024-10-03 13:22:46',
                'updated_at' => '2024-10-03 13:22:46',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Department of Science and Technology - II',
                'code' => 'R2',
                'acronym' => 'DOST-II',
                'avatar' => 'avatar.jpg',
                'is_active' => 1,
                'region_code' => '020000000',
                'created_at' => '2024-10-03 13:23:49',
                'updated_at' => '2024-10-03 13:23:49',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Department of Science and Technology - III',
                'code' => 'R3',
                'acronym' => 'DOST-III',
                'avatar' => 'avatar.jpg',
                'is_active' => 1,
                'region_code' => '030000000',
                'created_at' => '2024-10-03 13:26:02',
                'updated_at' => '2024-10-03 13:26:02',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Department of Science and Technology - IVA',
                'code' => 'CALABARZON',
                'acronym' => 'DOST-IVA',
                'avatar' => 'avatar.jpg',
                'is_active' => 1,
                'region_code' => '040000000',
                'created_at' => '2024-10-03 13:26:36',
                'updated_at' => '2024-10-03 13:26:36',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Department of Science and Technology - IVB',
                'code' => 'MIMAROPA',
                'acronym' => 'DOST-IVB',
                'avatar' => 'avatar.jpg',
                'is_active' => 1,
                'region_code' => '170000000',
                'created_at' => '2024-10-03 13:26:58',
                'updated_at' => '2024-10-03 13:26:58',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Department of Science and Technology - V',
                'code' => 'R5',
                'acronym' => 'DOST-V',
                'avatar' => 'avatar.jpg',
                'is_active' => 1,
                'region_code' => '050000000',
                'created_at' => '2024-10-03 13:27:17',
                'updated_at' => '2024-10-03 13:27:17',
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'Department of Science and Technology - VI',
                'code' => 'R6',
                'acronym' => 'DOST-VI',
                'avatar' => 'avatar.jpg',
                'is_active' => 1,
                'region_code' => '060000000',
                'created_at' => '2024-10-03 13:27:37',
                'updated_at' => '2024-10-03 13:27:37',
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'Department of Science and Technology - VII',
                'code' => 'R7',
                'acronym' => 'DOST-VII',
                'avatar' => 'avatar.jpg',
                'is_active' => 1,
                'region_code' => '070000000',
                'created_at' => '2024-10-03 13:27:50',
                'updated_at' => '2024-10-03 13:27:50',
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'Department of Science and Technology - VIII',
                'code' => 'R8',
                'acronym' => 'DOST-VIII',
                'avatar' => 'avatar.jpg',
                'is_active' => 1,
                'region_code' => '080000000',
                'created_at' => '2024-10-03 13:28:06',
                'updated_at' => '2024-10-03 13:28:06',
            ),
            9 => 
            array (
                'id' => 10,
                'name' => 'Department of Science and Technology - IX',
                'code' => 'R9',
                'acronym' => 'DOST-IX',
                'avatar' => 'avatar.jpg',
                'is_active' => 1,
                'region_code' => '090000000',
                'created_at' => '2024-10-03 13:28:22',
                'updated_at' => '2024-10-03 13:28:22',
            ),
            10 => 
            array (
                'id' => 11,
                'name' => 'Department of Science and Technology - X',
                'code' => 'R10',
                'acronym' => 'DOST-X',
                'avatar' => 'avatar.jpg',
                'is_active' => 1,
                'region_code' => '100000000',
                'created_at' => '2024-10-03 13:28:35',
                'updated_at' => '2024-10-03 13:28:35',
            ),
            11 => 
            array (
                'id' => 12,
                'name' => 'Department of Science and Technology - XI',
                'code' => 'R11',
                'acronym' => 'DOST-XI',
                'avatar' => 'avatar.jpg',
                'is_active' => 1,
                'region_code' => '110000000',
                'created_at' => '2024-10-03 13:29:00',
                'updated_at' => '2024-10-03 13:29:00',
            ),
            12 => 
            array (
                'id' => 13,
                'name' => 'Department of Science and Technology - XII',
                'code' => 'R12',
                'acronym' => 'DOST-XII',
                'avatar' => 'avatar.jpg',
                'is_active' => 1,
                'region_code' => '120000000',
                'created_at' => '2024-10-03 13:29:28',
                'updated_at' => '2024-10-03 13:29:28',
            ),
            13 => 
            array (
                'id' => 14,
                'name' => 'Department of Science and Technology - CARAGA',
                'code' => 'Caraga',
                'acronym' => 'DOST-CARAGA',
                'avatar' => 'avatar.jpg',
                'is_active' => 1,
                'region_code' => '160000000',
                'created_at' => '2024-10-03 13:31:34',
                'updated_at' => '2024-10-03 13:31:34',
            ),
            14 => 
            array (
                'id' => 15,
                'name' => 'Ministry of Science and Technology - BARMM',
                'code' => 'BARMM',
                'acronym' => 'MOST-BARMM',
                'avatar' => 'avatar.jpg',
                'is_active' => 1,
                'region_code' => '150000000',
                'created_at' => '2024-10-03 13:32:12',
                'updated_at' => '2024-10-03 13:32:12',
            ),
            15 => 
            array (
                'id' => 16,
                'name' => 'Department of Science and Technology - CAR',
                'code' => 'CAR',
                'acronym' => 'DOST-CAR',
                'avatar' => 'avatar.jpg',
                'is_active' => 1,
                'region_code' => '140000000',
                'created_at' => '2024-10-03 13:32:43',
                'updated_at' => '2024-10-03 13:32:43',
            ),
            16 => 
            array (
                'id' => 17,
                'name' => 'Science Education Institute',
                'code' => 'SEI',
                'acronym' => 'DOST-SEI',
                'avatar' => 'avatar.jpg',
                'is_active' => 1,
                'region_code' => '130000000',
                'created_at' => '2024-10-03 13:33:32',
                'updated_at' => '2024-10-03 13:33:32',
            ),
        ));

        
    }
}