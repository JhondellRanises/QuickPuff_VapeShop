<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFlavorFieldsSimple extends Migration
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
    }

    public function down()
    {
        $this->forge->dropColumn('products', ['flavor', 'flavor_category', 'puffs']);
    }
}
