<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name' => 'BLACK ELITE V1 (8k puffs)',
                'category' => 'Pod',
                'brand' => 'BLACK',
                'price' => 300.00,
                'stock_qty' => 50,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Mango Tango  (150ml)',
                'category' => 'e-liquid',
                'brand' => 'XBLACK',
                'price' => 22.99,
                'stock_qty' => 35,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'BLACK ELITE V2 (10k puffs)',
                'category' => 'POD',
                'brand' => 'BLACK',
                'price' => 39.99,
                'stock_qty' => 15,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'XBLACK SLIMBAR (15k puffs)',
                'category' => 'POD',
                'brand' => 'XBLACK',
                'price' => 395.00,
                'stock_qty' => 20,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'BLACK ELITE V3 (12k puffs)',
                'category' => 'POD',
                'brand' => 'BLACK',
                'price' => 400.00,
                'stock_qty' => 100,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'BLACK BATTERY (2000mAh)',
                'category' => 'Device',
                'brand' => 'BLACK',
                'price' => 300.00,
                'stock_qty' => 75,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'XBLACK SLIMEBAR BATTERY (2500mAh)',
                'category' => 'Device',
                'brand' => 'XBLACK',
                'price' => 395.00,
                'stock_qty' => 60,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Strawberry Banana - 60ml',
                'category' => 'e-liquid',
                'brand' => 'XBLACK',
                'price' => 150.00,
                'stock_qty' => 0,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'STORM (15k puffs)',
                'category' => 'Disposable',
                'brand' => 'STORM',
                'price' => 450.00,
                'stock_qty' => 25,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],

        ];

        $this->db->table('products')->insertBatch($data);
    }
}
