<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $imageMap = $this->loadImageMap();

        $data = [
            [
                'name' => 'BLACK ELITE V1 (8k puffs)',
                'category' => 'Pod',
                'brand' => 'BLACK',
                'image_url' => null,
                'price' => 300.00,
                'stock_qty' => 50,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'MINICAN',
                'category' => 'Pod kit',
                'brand' => 'ASPIRE',
                'image_url' => null,
                'price' => 800.00,
                'stock_qty' => 35,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'BLACK ELITE V2 (12k puffs)',
                'category' => 'POD',
                'brand' => 'BLACK',
                'image_url' => null,
                'price' => 39.99,
                'stock_qty' => 15,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'X-VAPE SLIMBAR (15k puffs)',
                'category' => 'POD',
                'brand' => 'X-VAPE',
                'image_url' => null,
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
                'image_url' => null,
                'price' => 400.00,
                'stock_qty' => 100,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'BLACK V1 BATTERY (2000mAh)',
                'category' => 'Device',
                'brand' => 'BLACK',
                'image_url' => null,
                'price' => 300.00,
                'stock_qty' => 75,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'X-VAPE SLIMEBAR DEVICE (2500mAh)',
                'category' => 'Device',
                'brand' => 'X-VAPE',
                'image_url' => null,
                'price' => 395.00,
                'stock_qty' => 60,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'POD FORMULA (30ml)',
                'category' => 'e-liquid',
                'brand' => 'CODED',
                'image_url' => null,
                'price' => 180.00,
                'stock_qty' => 0,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'STORM (15k puffs)',
                'category' => 'Disposable',
                'brand' => 'STORM',
                'image_url' => null,
                'price' => 450.00,
                'stock_qty' => 25,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],

        ];

        foreach ($data as &$product) {
            $seedName = trim((string) ($product['name'] ?? ''));
            if ($seedName !== '' && isset($imageMap[$seedName]) && trim((string) $imageMap[$seedName]) !== '') {
                $product['image_url'] = trim((string) $imageMap[$seedName]);
            }
        }
        unset($product);

        $this->db->table('products')->insertBatch($data);
    }

    private function loadImageMap(): array
    {
        $mapPath = WRITEPATH . 'product_image_seed_map.json';
        if (!is_file($mapPath)) {
            return [];
        }

        $raw = @file_get_contents($mapPath);
        if ($raw === false || trim($raw) === '') {
            return [];
        }

        $decoded = json_decode($raw, true);
        return is_array($decoded) ? $decoded : [];
    }
}
