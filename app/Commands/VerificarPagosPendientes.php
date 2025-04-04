<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class VerificarPagosPendientes extends BaseCommand
{
    protected $group       = 'Ventas';
    protected $name        = 'ventas:verificar-pagos-pendientes';
    protected $description = 'Verifica pagos pendientes y envía notificaciones';

    public function run(array $params)
    {
        $db = db_connect();

        // Buscar ventas con banco "Pendiente consignar" de hace más de 1 día
        $fechaLimite = date('Y-m-d', strtotime('-1 day'));

        $ventasPendientes = $db->table('ventas')
            ->where('banco', 'Pendiente consignar')
            ->where('fecha_actualizacion_banco <=', $fechaLimite)
            ->where('estado', 'completada')
            ->get()->getResultArray();

        if (empty($ventasPendientes)) {
            CLI::write('No hay pagos pendientes que requieran atención', 'green');
            return;
        }

        CLI::write('Se encontraron ' . count($ventasPendientes) . ' pagos pendientes:', 'yellow');

        foreach ($ventasPendientes as $venta) {
            $diasPendiente = floor((strtotime(date('Y-m-d')) - strtotime($venta['fecha_actualizacion_banco'])) / (60 * 60 * 24));

            CLI::write("Venta ID: {$venta['id']} - Código: {$venta['codigo']} - Pendiente desde hace {$diasPendiente} día(s)", 'yellow');

            // Aquí puedes implementar el envío de notificaciones por email
            // o crear alertas en el sistema
        }
    }
}
