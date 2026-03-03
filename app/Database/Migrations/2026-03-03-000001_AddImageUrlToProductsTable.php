<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddImageUrlToProductsTable extends Migration
{
    public function up()
    {
        if (!$this->db->fieldExists('image_url', 'products')) {
            $this->forge->addColumn('products', [
                'image_url' => [
                    'type' => 'VARCHAR',
                    'constraint' => 2048,
                    'null' => true,
                    'after' => 'brand',
                ],
            ]);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('image_url', 'products')) {
            $this->forge->dropColumn('products', 'image_url');
        }
    }
}
