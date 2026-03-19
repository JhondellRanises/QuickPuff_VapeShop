<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFlavorFieldsToProducts extends Migration
{
    public function up()
    {
        $this->forge->addColumn('products', [
            'flavor' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'after'      => 'brand',
            ],
            'flavor_category' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
                'after'      => 'flavor',
            ],
            'puffs' => [
                'type'       => 'INT',
                'null'       => true,
                'after'      => 'flavor_category',
            ],
        ]);

        // Add indexes for better performance (check if they don't exist first)
        $db = \Config\Database::connect();
        
        // Check and add flavor index
        $result = $db->query("SHOW INDEX FROM products WHERE Key_name = 'idx_products_flavor'");
        if ($result->getNumRows() == 0) {
            $db->query('CREATE INDEX idx_products_flavor ON products(flavor)');
        }
        
        // Check and add flavor_category index
        $result = $db->query("SHOW INDEX FROM products WHERE Key_name = 'idx_products_flavor_category'");
        if ($result->getNumRows() == 0) {
            $db->query('CREATE INDEX idx_products_flavor_category ON products(flavor_category)');
        }
        
        // Check and add puffs index
        $result = $db->query("SHOW INDEX FROM products WHERE Key_name = 'idx_products_puffs'");
        if ($result->getNumRows() == 0) {
            $db->query('CREATE INDEX idx_products_puffs ON products(puffs)');
        }
    }

    public function down()
    {
        $this->forge->dropColumn('products', ['flavor', 'flavor_category', 'puffs']);
    }
}
