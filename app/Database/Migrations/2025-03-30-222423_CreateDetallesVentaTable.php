<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDetallesVentaTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'venta_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'producto_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'cantidad' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'precio_unitario' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'subtotal' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        // Comenta temporalmente las claves foráneas
        // $this->forge->addForeignKey('venta_id', 'ventas', 'id', 'CASCADE', 'CASCADE');
        // $this->forge->addForeignKey('producto_id', 'productos', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('detalles_venta');
    }

    public function down()
    {
        $this->forge->dropTable('detalles_venta');
    }
}
