<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDiskonToTransaction extends Migration
{
    public function up()
    {
        $this->forge->addColumn('transaction', [
            'biaya_jasa' => [
                'type' => 'DOUBLE',
                'null' => TRUE,
                'default' => 0.0,
                'after' => 'ongkir'
            ],
            'voucher_code' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => TRUE,
                'after' => 'biaya_jasa'
            ],
            'diskon_voucher' => [
                'type' => 'DOUBLE',
                'null' => TRUE,
                'default' => 0.0,
                'after' => 'voucher_code'
            ],
            'free_mouse' => [
                'type' => 'DOUBLE',
                'null' => TRUE,
                'default' => 0.0,
                'after' => 'diskon_voucher'
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('transaction', 'free_mouse');
        $this->forge->dropColumn('transaction', 'diskon_voucher');
        $this->forge->dropColumn('transaction', 'voucher_code');
        $this->forge->dropColumn('transaction', 'biaya_jasa');
    }
}
