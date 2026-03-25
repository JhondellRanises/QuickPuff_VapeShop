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
            'puffs' => [
                'type'       => 'INT',
                'null'       => true,
                'after'      => 'flavor',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('products', ['flavor', 'puffs']);
    }
}
