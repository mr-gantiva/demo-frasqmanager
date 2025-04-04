<?php

namespace App\Helpers;

class CanalesVenta
{
    /**
     * Lista de canales de venta disponibles
     * 
     * @return array
     */
    public static function getCanales()
    {
        return [
            'Sitio web'     => 'Sitio web',
            'Mercadolibre'  => 'Mercadolibre',
            'Rappi'         => 'Rappi',
            'Falabella'     => 'Falabella',
            'WhatsApp'      => 'WhatsApp'
        ];
    }

    /**
     * Obtiene el nombre formateado del canal
     * 
     * @param string $canal
     * @return string
     */
    public static function getNombre($canal)
    {
        $canales = self::getCanales();
        return isset($canales[$canal]) ? $canales[$canal] : $canal;
    }

    /**
     * Obtiene las clases CSS para el badge/etiqueta del canal
     * 
     * @param string $canal
     * @return string
     */
    public static function getClaseCSS($canal)
    {
        switch ($canal) {
            case 'Sitio web':
                return 'bg-primary';
            case 'Mercadolibre':
                return 'bg-warning text-dark';
            case 'Rappi':
                return 'bg-danger';
            case 'Falabella':
                return 'bg-info';
            case 'WhatsApp':
                return 'bg-success';
            default:
                return 'bg-secondary';
        }
    }
}
