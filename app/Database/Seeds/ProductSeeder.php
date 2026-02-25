<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name' => 'Blue Razz Ice - 100ml',
                'category' => 'e-liquid',
                'brand' => 'VapeWild',
                'price' => 24.99,
                'stock_qty' => 50,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Mango Tango - 100ml',
                'category' => 'e-liquid',
                'brand' => 'VapeWild',
                'price' => 22.99,
                'stock_qty' => 35,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'SMOK Nord 4 Kit',
                'category' => 'device',
                'brand' => 'SMOK',
                'price' => 39.99,
                'stock_qty' => 15,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Vaporesso XROS 3',
                'category' => 'device',
                'brand' => 'Vaporesso',
                'price' => 34.99,
                'stock_qty' => 20,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'SMOK RPM 4 Coils (5-pack)',
                'category' => 'accessory',
                'brand' => 'SMOK',
                'price' => 15.99,
                'stock_qty' => 100,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Vaporesso XROS Pods (2-pack)',
                'category' => 'accessory',
                'brand' => 'Vaporesso',
                'price' => 12.99,
                'stock_qty' => 75,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Geek Bar Pulse Disposable',
                'category' => 'device',
                'brand' => 'Geek Bar',
                'price' => 18.99,
                'stock_qty' => 60,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Strawberry Banana - 60ml',
                'category' => 'e-liquid',
                'brand' => 'Naked 100',
                'price' => 19.99,
                'stock_qty' => 0,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('products')->insertBatch($data);
    }
}
