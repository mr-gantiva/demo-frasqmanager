<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class TestDb extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        if ($db->connect()) {
            echo '<h1>Conexión exitosa a la base de datos</h1>';
            echo '<p>Las siguientes tablas han sido creadas:</p>';
            echo '<ul>';

            $tables = $db->listTables();
            foreach ($tables as $table) {
                echo "<li>{$table}</li>";
            }

            echo '</ul>';
        } else {
            echo '<h1>Error al conectar a la base de datos</h1>';
            echo $db->error();
        }
    }
}
