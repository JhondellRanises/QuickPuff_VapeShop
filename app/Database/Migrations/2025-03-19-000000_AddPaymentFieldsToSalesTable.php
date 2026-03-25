<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPaymentFieldsToSalesTable extends Migration
{
    public function up()
    {
        // Add payment fields to sales table
        $this->forge->addColumn('sales', [
            'amount_paid' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => '0.00',
                'after' => 'payment_method'
            ],
            'change_amount' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => '0.00',
                'after' => 'amount_paid'
            ],
        ]);

        // Add flavor fields to products table
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
        // Remove payment fields from sales table
        $this->forge->dropColumn('sales', 'amount_paid');
        $this->forge->dropColumn('sales', 'change_amount');
        
        // Remove flavor fields from products table
        $this->forge->dropColumn('products', ['flavor', 'puffs']);
    }
}
