<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ListDropdownsTableSeeder extends Seeder
{
    /**
     * Auto generated seeder file.
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('list_dropdowns')->delete();
        
        \DB::table('list_dropdowns')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'n/a',
                'classification' => 'n/a',
                'type' => 'n/a',
                'color' => 'n/a',
                'others' => 'n/a',
                'is_active' => 1,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Regional Standards and Testing Laboratories',
                'classification' => 'Agency Type',
                'type' => 'n/a',
                'color' => 'n/a',
                'others' => 'RSTL',
                'is_active' => 1,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Research and Development Institutes',
                'classification' => 'Agency Type',
                'type' => 'n/a',
                'color' => 'n/a',
                'others' => 'RDI',
                'is_active' => 1,
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Regional Metrology Lab',
                'classification' => 'Agency Type',
                'type' => 'n/a',
                'color' => 'n/a',
                'others' => 'RML',
                'is_active' => 1,
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Regional Volumetric Calibration Laboratory',
                'classification' => 'Agency Type',
                'type' => 'n/a',
                'color' => 'n/a',
                'others' => 'RVCL',
                'is_active' => 1,
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Cavite Water & Wastewater Testing Laboratory',
                'classification' => 'Agency Type',
                'type' => 'n/a',
                'color' => 'n/a',
                'others' => 'CWWTL',
                'is_active' => 1,
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'Private Sector',
                'classification' => 'Agency Type',
                'type' => 'n/a',
                'color' => 'n/a',
                'others' => 'PS',
                'is_active' => 1,
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'Firm',
                'classification' => 'Class',
                'type' => 'n/a',
                'color' => 'n/a',
                'others' => 'n/a',
                'is_active' => 1,
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'Individual',
                'classification' => 'Class',
                'type' => 'n/a',
                'color' => 'n/a',
                'others' => 'n/a',
                'is_active' => 1,
            ),
            9 => 
            array (
                'id' => 10,
                'name' => 'Percentage',
                'classification' => 'Discount',
                'type' => 'Subtype',
                'color' => 'n/a',
                'others' => 'n/a',
                'is_active' => 1,
            ),
            10 => 
            array (
                'id' => 11,
                'name' => 'Amount',
                'classification' => 'Discount',
                'type' => 'Subtype',
                'color' => 'n/a',
                'others' => 'n/a',
                'is_active' => 1,
            ),
            11 => 
            array (
                'id' => 12,
                'name' => 'Unlimited',
                'classification' => 'Discount',
                'type' => 'Type',
                'color' => 'n/a',
                'others' => 'n/a',
                'is_active' => 1,
            ),
            12 => 
            array (
                'id' => 13,
                'name' => 'Limited',
                'classification' => 'Discount',
                'type' => 'Type',
                'color' => 'n/a',
                'others' => 'n/a',
                'is_active' => 1,
            ),
            13 => 
            array (
                'id' => 14,
                'name' => 'Total Based',
                'classification' => 'Discount',
                'type' => 'Based',
                'color' => 'n/a',
                'others' => 'n/a',
                'is_active' => 1,
            ),
            14 => 
            array (
                'id' => 15,
                'name' => 'Item Based',
                'classification' => 'Discount',
                'type' => 'Based',
                'color' => 'n/a',
                'others' => 'n/a',
                'is_active' => 1,
            ),
            15 => 
            array (
                'id' => 16,
                'name' => 'Occasional Based',
                'classification' => 'Discount',
                'type' => 'Based',
                'color' => 'n/a',
                'others' => 'n/a',
                'is_active' => 1,
            ),
            16 => 
            array (
                'id' => 17,
                'name' => 'Cash',
                'classification' => 'Payment Mode',
                'type' => 'n/a',
                'color' => 'n/a',
                'others' => 'n/a',
                'is_active' => 1,
            ),
            17 => 
            array (
                'id' => 18,
                'name' => 'Cheque',
                'classification' => 'Payment Mode',
                'type' => 'n/a',
                'color' => 'n/a',
                'others' => 'Cheque Number',
                'is_active' => 1,
            ),
            18 => 
            array (
                'id' => 19,
                'name' => 'Money Order',
                'classification' => 'Payment Mode',
                'type' => 'n/a',
                'color' => 'n/a',
                'others' => 'n/a',
                'is_active' => 0,
            ),
            19 => 
            array (
                'id' => 20,
                'name' => 'Bank Deposit',
                'classification' => 'Payment Mode',
                'type' => 'n/a',
                'color' => 'n/a',
                'others' => 'Number',
                'is_active' => 1,
            ),
            20 => 
            array (
                'id' => 21,
                'name' => 'Wallet',
                'classification' => 'Payment Mode',
                'type' => 'wallet',
                'color' => 'n/a',
                'others' => 'n/a',
                'is_active' => 1,
            ),
            21 => 
            array (
                'id' => 22,
                'name' => 'Online Transfer',
                'classification' => 'Payment Mode',
                'type' => 'n/a',
                'color' => 'n/a',
                'others' => 'Source Account',
                'is_active' => 1,
            ),
            22 => 
            array (
                'id' => 23,
                'name' => 'Analysis Fee',
                'classification' => 'Collection Type',
                'type' => 'Laboratory',
                'color' => 'n/a',
                'others' => 'n/a',
                'is_active' => 1,
            ),
            23 => 
            array (
                'id' => 24,
                'name' => 'Calibration Fee',
                'classification' => 'Collection Type',
                'type' => 'Laboratory',
                'color' => 'n/a',
                'others' => 'n/a',
                'is_active' => 1,
            ),
            24 => 
            array (
                'id' => 25,
                'name' => 'Analysis/Calibration Fee',
                'classification' => 'Collection Type',
                'type' => 'Laboratory',
                'color' => 'n/a',
                'others' => 'n/a',
                'is_active' => 1,
            ),
            25 => 
            array (
                'id' => 26,
                'name' => 'Bureau of Treasury',
                'classification' => 'Deposit Type',
                'type' => 'n/a',
                'color' => 'n/a',
                'others' => 'n/a',
                'is_active' => 1,
            ),
            26 => 
            array (
                'id' => 27,
                'name' => 'Project',
                'classification' => 'Deposit Type',
                'type' => 'n/a',
                'color' => 'n/a',
                'others' => 'n/a',
                'is_active' => 1,
            ),
            27 => 
            array (
                'id' => 28,
                'name' => 'Method',
                'classification' => 'Service Name',
                'type' => 'n/a',
                'color' => 'n/a',
                'others' => 'n/a',
                'is_active' => 1,
            ),
            28 => 
            array (
                'id' => 29,
                'name' => 'Reference',
                'classification' => 'Service Name',
                'type' => 'n/a',
                'color' => 'n/a',
                'others' => 'n/a',
                'is_active' => 1,
            ),
            29 => 
            array (
                'id' => 30,
                'name' => 'Sample Type',
                'classification' => 'Service Name',
                'type' => 'n/a',
                'color' => 'n/a',
                'others' => 'n/a',
                'is_active' => 1,
            ),
            30 => 
            array (
                'id' => 31,
                'name' => 'Test Name',
                'classification' => 'Service Name',
                'type' => 'n/a',
                'color' => 'n/a',
                'others' => 'n/a',
                'is_active' => 1,
            ),
            31 => 
            array (
                'id' => 32,
                'name' => 'Chemical',
                'classification' => 'Inventory',
                'type' => 'Category',
                'color' => 'success',
                'others' => 'n/a',
                'is_active' => 1,
            ),
            32 => 
            array (
                'id' => 33,
                'name' => 'Media',
                'classification' => 'Inventory',
                'type' => 'Category',
                'color' => 'primary',
                'others' => 'n/a',
                'is_active' => 1,
            ),
            33 => 
            array (
                'id' => 34,
                'name' => 'Supplies',
                'classification' => 'Inventory',
                'type' => 'Category',
                'color' => 'warning',
                'others' => 'n/a',
                'is_active' => 1,
            ),
            34 => 
            array (
                'id' => 35,
                'name' => 'Piece',
                'classification' => 'Inventory',
                'type' => 'Unit',
                'color' => 'piece',
                'others' => 'pc',
                'is_active' => 1,
            ),
            35 => 
            array (
                'id' => 36,
                'name' => 'Liter',
                'classification' => 'Inventory',
                'type' => 'Unit',
                'color' => 'volume',
                'others' => 'L',
                'is_active' => 1,
            ),
            36 => 
            array (
                'id' => 37,
                'name' => 'Milliliter',
                'classification' => 'Inventory',
                'type' => 'Unit',
                'color' => 'volume',
                'others' => 'ml',
                'is_active' => 1,
            ),
            37 => 
            array (
                'id' => 38,
                'name' => 'Kilogram',
                'classification' => 'Inventory',
                'type' => 'Unit',
                'color' => 'mass',
                'others' => 'kg',
                'is_active' => 1,
            ),
            38 => 
            array (
                'id' => 39,
                'name' => 'Gram',
                'classification' => 'Inventory',
                'type' => 'Unit',
                'color' => 'mass',
                'others' => 'g',
                'is_active' => 1,
            ),
            39 => 
            array (
                'id' => 40,
                'name' => 'Calibration',
                'classification' => 'Inventory',
                'type' => 'Equipment',
                'color' => 'n/a',
                'others' => 'n/a',
                'is_active' => 1,
            ),
            40 => 
            array (
                'id' => 41,
                'name' => 'Intermediate Check',
                'classification' => 'Inventory',
                'type' => 'Equipment',
                'color' => 'n/a',
                'others' => 'n/a',
                'is_active' => 1,
            ),
            41 => 
            array (
                'id' => 42,
                'name' => 'Characterization',
                'classification' => 'Inventory',
                'type' => 'Equipment',
                'color' => 'n/a',
                'others' => 'n/a',
                'is_active' => 1,
            ),
            42 => 
            array (
                'id' => 43,
                'name' => 'Official Travel',
                'classification' => 'Events',
                'type' => 'n/a',
                'color' => 'text-success',
                'others' => 'bg-success-subtle',
                'is_active' => 1,
            ),
            43 => 
            array (
                'id' => 44,
                'name' => 'Meeting',
                'classification' => 'Events',
                'type' => 'n/a',
                'color' => 'text-info',
                'others' => 'bg-info-subtle',
                'is_active' => 1,
            ),
            44 => 
            array (
                'id' => 45,
                'name' => 'Audit',
                'classification' => 'Events',
                'type' => 'n/a',
                'color' => 'text-warning',
                'others' => 'bg-warning-subtle',
                'is_active' => 1,
            ),
            45 => 
            array (
                'id' => 46,
                'name' => 'Performance Review',
                'classification' => 'Events',
                'type' => 'n/a',
                'color' => 'text-primary',
                'others' => 'bg-primary-subtle',
                'is_active' => 1,
            ),
            46 => 
            array (
                'id' => 47,
                'name' => 'Leave',
                'classification' => 'Events',
                'type' => 'n/a',
                'color' => 'text-danger',
                'others' => 'bg-danger-subtle',
                'is_active' => 1,
            ),
            47 => 
            array (
                'id' => 48,
                'name' => 'Holiday',
                'classification' => 'Events',
                'type' => 'n/a',
                'color' => 'text-dark',
                'others' => 'bg-dark-subtle',
                'is_active' => 1,
            ),
            48 => 
            array (
                'id' => 49,
                'name' => 'Setup Project Payment',
                'classification' => 'Collection Type',
                'type' => 'Non-laboratory',
                'color' => 'n/a',
                'others' => 'n/a',
                'is_active' => 1,
            ),
            49 => 
            array (
                'id' => 50,
                'name' => 'Scholarship Assistance',
                'classification' => 'Collection Type',
                'type' => 'Non-laboratory',
                'color' => 'n/a',
                'others' => 'n/a',
                'is_active' => 1,
            ),
            50 => 
            array (
                'id' => 51,
                'name' => 'Refund of Travel',
                'classification' => 'Collection Type',
                'type' => 'Non-laboratory',
                'color' => 'n/a',
                'others' => 'n/a',
                'is_active' => 1,
            ),
            51 => 
            array (
                'id' => 52,
                'name' => 'Bidding Paper',
                'classification' => 'Collection Type',
                'type' => 'Non-laboratory',
                'color' => 'n/a',
                'others' => 'n/a',
                'is_active' => 1,
            ),
            52 => 
            array (
                'id' => 53,
                'name' => 'Others',
                'classification' => 'Collection Type',
                'type' => 'Non-laboratory',
                'color' => 'n/a',
                'others' => 'n/a',
                'is_active' => 1,
            ),
            53 => 
            array (
                'id' => 54,
                'name' => 'Testing Fee',
                'classification' => 'Collection Type',
                'type' => 'Laboratory',
                'color' => 'n/a',
                'others' => 'n/a',
                'is_active' => 1,
            ),
        ));

        
    }
}