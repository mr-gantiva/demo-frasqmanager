<?php

declare(strict_types=1);

/**
 * This file is part of CodeIgniter Shield.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Config;

use CodeIgniter\Shield\Config\AuthGroups as ShieldAuthGroups;

class AuthGroups extends ShieldAuthGroups
{
    /**
     * --------------------------------------------------------------------
     * Default Group
     * --------------------------------------------------------------------
     * The group that a newly registered user is added to.
     */
    public string $defaultGroup = 'user';

    /**
     * --------------------------------------------------------------------
     * Groups
     * --------------------------------------------------------------------
     * An associative array of the available groups in the system, where the keys
     * are the group names and the values are arrays of the group info.
     *
     * Whatever value you assign as the key will be used to refer to the group
     * when using functions such as:
     *      $user->addGroup('superadmin');
     *
     * @var array<string, array<string, string>>
     *
     * @see https://codeigniter4.github.io/shield/quick_start_guide/using_authorization/#change-available-groups for more info
     */
    public array $groups = [

        'admin' => [
            'title'       => 'Administradores',
            'description' => 'Administradores del sistema con acceso completo.',
        ],
        'vendedor' => [
            'title'       => 'Vendedores',
            'description' => 'Personal de ventas con acceso limitado.',
        ],

    ];

    /**
     * --------------------------------------------------------------------
     * Permissions
     * --------------------------------------------------------------------
     * The available permissions in the system.
     *
     * If a permission is not listed here it cannot be used.
     */
    public array $permissions = [
        'admin.access'     => 'Acceso al área de administración',
        'admin.settings'   => 'Gestionar configuración del sistema',
        'admin.users'      => 'Gestionar usuarios',
        'products.create'  => 'Crear nuevos productos',
        'products.edit'    => 'Editar productos existentes',
        'products.delete'  => 'Eliminar productos',
        'customers.manage' => 'Gestionar clientes',
        'sales.create'     => 'Crear ventas',
        'sales.view'       => 'Ver ventas',
        'sales.cancel'     => 'Anular ventas',
        'reports.view'     => 'Ver reportes',
    ];

    /**
     * --------------------------------------------------------------------
     * Permissions Matrix
     * --------------------------------------------------------------------
     * Maps permissions to groups.
     *
     * This defines group-level permissions.
     */
    public array $matrix = [
        'admin' => [
            'admin.*',
            'products.*',
            'customers.*',
            'sales.*',
            'reports.*',
        ],
        'vendedor' => [
            'products.create',
            'products.edit',
            'customers.manage',
            'sales.create',
            'sales.view',
        ],
    ];
}
