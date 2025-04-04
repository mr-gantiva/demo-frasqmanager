<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOnlyVentasTable extends Migration
{
    public function up()
    {
        // Verificar si la tabla ya existe
        $db = \Config\Database::connect();
        $prefix = $db->getPrefix();
        if ($db->tableExists($prefix . 'ventas')) {
            // La tabla ya existe, no hacer nada
            return;
        }

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
        $this->forge->createTable('ventas');
    }

    public function down()
    {
        $this->forge->dropTable('ventas');
    }
}
