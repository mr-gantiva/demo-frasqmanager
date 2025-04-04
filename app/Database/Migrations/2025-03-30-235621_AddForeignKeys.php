<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddForeignKeys extends Migration
{
    public function up()
    {
        // Agregar clave foránea en ventas
        $this->forge->processIndexes('ventas');
        $db = \Config\Database::connect();
        $db->query('ALTER TABLE `ventas` ADD CONSTRAINT `ventas_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE');

        // Agregar claves foráneas en detalles_venta
        $this->forge->processIndexes('detalles_venta');
        $db->query('ALTER TABLE `detalles_venta` ADD CONSTRAINT `detalles_venta_venta_id_foreign` FOREIGN KEY (`venta_id`) REFERENCES `ventas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE');
        $db->query('ALTER TABLE `detalles_venta` ADD CONSTRAINT `detalles_venta_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE');
    }

    public function down()
    {
        $db = \Config\Database::connect();

        // Eliminar las claves foráneas
        $db->query('ALTER TABLE `detalles_venta` DROP FOREIGN KEY `detalles_venta_producto_id_foreign`');
        $db->query('ALTER TABLE `detalles_venta` DROP FOREIGN KEY `detalles_venta_venta_id_foreign`');
        $db->query('ALTER TABLE `ventas` DROP FOREIGN KEY `ventas_cliente_id_foreign`');
    }
}
