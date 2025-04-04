<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCanalVentaToVentas extends Migration
{
    public function up()
    {
        $this->forge->addColumn('ventas', [
            'canal_venta' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
                'default'    => 'Sitio web',
                'after'      => 'estado'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('ventas', 'canal_venta');
    }
}
