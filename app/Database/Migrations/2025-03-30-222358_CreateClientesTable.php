<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateClientesTable extends Migration
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
            'identificacion' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'unique'     => true,
            ],
            'tipo_identificacion' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
                'comment'    => 'CC, NIT, CE, etc.'
            ],
            'nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'apellido' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'empresa' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => true,
            ],
            'direccion' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'ciudad' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'telefono' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
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
        $this->forge->createTable('clientes');
    }

    public function down()
    {
        $this->forge->dropTable('clientes');
    }
}
