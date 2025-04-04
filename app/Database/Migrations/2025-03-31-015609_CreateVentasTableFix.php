<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateVentasTableFix extends Migration
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
            'codigo' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'unique'     => true,
            ],
            'cliente_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'fecha' => [
                'type'       => 'DATE',
            ],
            'total' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
            ],
            'estado' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'completada',
                'comment'    => 'completada, anulada, pendiente',
            ],
            'notas' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'usuario_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
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
        // Omitimos temporalmente la clave foránea para evitar problemas
        //$this->forge->addForeignKey('cliente_id', 'clientes', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('ventas');
    }

    public function down()
    {
        $this->forge->dropTable('ventas');
    }
}
