<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPaymentFieldsToSalesTable extends Migration
{
    public function up()
    {
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
    }

    public function down()
    {
        $this->forge->dropColumn('sales', 'amount_paid');
        $this->forge->dropColumn('sales', 'change_amount');
    }
}
