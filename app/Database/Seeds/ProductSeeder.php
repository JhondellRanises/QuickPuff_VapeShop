<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $imageMap = $this->loadImageMap();

        $data = [
            // Pods Category - 5 products
            [
                'name' => 'BLACK ELITE V2',
                'category' => 'Pods',
                'brand' => 'BLACK',
                'image_url' => null,
                'price' => 350.00,
                'stock_qty' => 25,
                'is_active' => 1,
                'flavor' => 'Mango',
                'flavor_category' => 'Fruit',
                'puffs' => 25000,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'BLACK ELITE V2',
                'category' => 'Pods',
                'brand' => 'BLACK',
                'image_url' => null,
                'price' => 350.00,
                'stock_qty' => 20,
                'is_active' => 1,
                'flavor' => 'Grape',
                'flavor_category' => 'Fruit',
                'puffs' => 8000,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'BLACK ELITE V2',
                'category' => 'Pods',
                'brand' => 'BLACK',
                'image_url' => null,
                'price' => 350.00,
                'stock_qty' => 30,
                'is_active' => 1,
                'flavor' => 'Strawberry',
                'flavor_category' => 'Fruit',
                'puffs' => 12000,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'BLACK ELITE V2',
                'category' => 'Pods',
                'brand' => 'BLACK',
                'image_url' => null,
                'price' => 350.00,
                'stock_qty' => 15,
                'is_active' => 1,
                'flavor' => 'Blueberry',
                'flavor_category' => 'Fruit',
                'puffs' => 8000,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'BLACK ELITE V2',
                'category' => 'Pods',
                'brand' => 'BLACK',
                'image_url' => null,
                'price' => 350.00,
                'stock_qty' => 25,
                'is_active' => 1,
                'flavor' => 'Mint',
                'flavor_category' => 'Menthol',
                'puffs' => 8000,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            
            // Device Category - 5 products (no flavors)
            [
                'name' => 'MINICAN',
                'category' => 'Device',
                'brand' => 'ASPIRE',
                'image_url' => null,
                'price' => 800.00,
                'stock_qty' => 35,
                'is_active' => 1,
                'flavor' => null,
                'flavor_category' => null,
                'puffs' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'BLACK V1 BATTERY',
                'category' => 'Device',
                'brand' => 'BLACK',
                'image_url' => null,
                'price' => 300.00,
                'stock_qty' => 75,
                'is_active' => 1,
                'flavor' => null,
                'flavor_category' => null,
                'puffs' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'X-VAPE SLIMBAR DEVICE',
                'category' => 'Device',
                'brand' => 'X-VAPE',
                'image_url' => null,
                'price' => 395.00,
                'stock_qty' => 60,
                'is_active' => 1,
                'flavor' => null,
                'flavor_category' => null,
                'puffs' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'VOOPOO DRAG KIT',
                'category' => 'Device',
                'brand' => 'VOOPOO',
                'image_url' => null,
                'price' => 1200.00,
                'stock_qty' => 45,
                'is_active' => 1,
                'flavor' => null,
                'flavor_category' => null,
                'puffs' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'SMOK NOVO KIT',
                'category' => 'Device',
                'brand' => 'SMOK',
                'image_url' => null,
                'price' => 950.00,
                'stock_qty' => 50,
                'is_active' => 1,
                'flavor' => null,
                'flavor_category' => null,
                'puffs' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            
            // E-liquid Category - 5 products
            [
                'name' => 'POD FORMULA',
                'category' => 'E-liquid',
                'brand' => 'CODED',
                'image_url' => null,
                'price' => 180.00,
                'stock_qty' => 25,
                'is_active' => 1,
                'flavor' => 'Tobacco',
                'flavor_category' => 'Traditional',
                'puffs' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'POD FORMULA',
                'category' => 'E-liquid',
                'brand' => 'CODED',
                'image_url' => null,
                'price' => 180.00,
                'stock_qty' => 30,
                'is_active' => 1,
                'flavor' => 'Vanilla',
                'flavor_category' => 'Sweet',
                'puffs' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'POD FORMULA',
                'category' => 'E-liquid',
                'brand' => 'CODED',
                'image_url' => null,
                'price' => 180.00,
                'stock_qty' => 20,
                'is_active' => 1,
                'flavor' => 'Menthol',
                'flavor_category' => 'Menthol',
                'puffs' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'POD FORMULA',
                'category' => 'E-liquid',
                'brand' => 'CODED',
                'image_url' => null,
                'price' => 180.00,
                'stock_qty' => 35,
                'is_active' => 1,
                'flavor' => 'Strawberry',
                'flavor_category' => 'Fruit',
                'puffs' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'POD FORMULA',
                'category' => 'E-liquid',
                'brand' => 'CODED',
                'image_url' => null,
                'price' => 180.00,
                'stock_qty' => 28,
                'is_active' => 1,
                'flavor' => 'Coffee',
                'flavor_category' => 'Beverage',
                'puffs' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            
            // Disposable Category - 5 products
            [
                'name' => 'STORM',
                'category' => 'Disposable',
                'brand' => 'STORM',
                'image_url' => null,
                'price' => 450.00,
                'stock_qty' => 10,
                'is_active' => 1,
                'flavor' => 'Mint',
                'flavor_category' => 'Menthol',
                'puffs' => 15000,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'STORM',
                'category' => 'Disposable',
                'brand' => 'STORM',
                'image_url' => null,
                'price' => 450.00,
                'stock_qty' => 15,
                'is_active' => 1,
                'flavor' => 'Blueberry',
                'flavor_category' => 'Fruit',
                'puffs' => 15000,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'STORM',
                'category' => 'Disposable',
                'brand' => 'STORM',
                'image_url' => null,
                'price' => 450.00,
                'stock_qty' => 12,
                'is_active' => 1,
                'flavor' => 'Mango',
                'flavor_category' => 'Fruit',
                'puffs' => 15000,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'STORM',
                'category' => 'Disposable',
                'brand' => 'STORM',
                'image_url' => null,
                'price' => 450.00,
                'stock_qty' => 18,
                'is_active' => 1,
                'flavor' => 'Grape',
                'flavor_category' => 'Fruit',
                'puffs' => 15000,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'STORM',
                'category' => 'Disposable',
                'brand' => 'STORM',
                'image_url' => null,
                'price' => 450.00,
                'stock_qty' => 20,
                'is_active' => 1,
                'flavor' => 'Strawberry',
                'flavor_category' => 'Fruit',
                'puffs' => 15000,
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
