<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('testdb', 'TestDb::index');

// Productos
$routes->get('productos', 'Productos::index');
$routes->get('productos/new', 'Productos::new');
$routes->post('productos/create', 'Productos::create');
$routes->get('productos/edit/(:num)', 'Productos::edit/$1');
$routes->post('productos/update/(:num)', 'Productos::update/$1');
$routes->get('productos/delete/(:num)', 'Productos::delete/$1');


// Clientes
$routes->get('clientes', 'Clientes::index');
$routes->get('clientes/new', 'Clientes::new');
$routes->post('clientes/create', 'Clientes::create');
$routes->get('clientes/edit/(:num)', 'Clientes::edit/$1');
$routes->post('clientes/update/(:num)', 'Clientes::update/$1');
$routes->get('clientes/delete/(:num)', 'Clientes::delete/$1');
$routes->get('clientes/view/(:num)', 'Clientes::view/$1');

// Ventas
$routes->get('ventas', 'Ventas::index');
$routes->get('ventas/new', 'Ventas::new');
$routes->post('ventas/create', 'Ventas::create');
$routes->get('ventas/view/(:num)', 'Ventas::view/$1');
$routes->get('ventas/anular/(:num)', 'Ventas::anular/$1');
$routes->get('ventas/getProducto', 'Ventas::getProducto');
$routes->get('ventas/probarAnulacion/(:num)', 'Ventas::probarAnulacion/$1');
$routes->get('ventas/testUpdateStock/(:num)/(:num)', 'Ventas::testUpdateStock/$1/$2');


// Rutas de autenticación
$routes->get('login', 'Auth::login');
$routes->post('login/auth', 'Auth::attemptLogin');
$routes->get('logout', 'Auth::logout');

// Usuarios
$routes->get('usuarios', 'Usuarios::index');
$routes->get('usuarios/new', 'Usuarios::new');
$routes->post('usuarios/create', 'Usuarios::create');
$routes->get('usuarios/delete/(:num)', 'Usuarios::delete/$1');

// Rutas de perfil
$routes->get('perfil', 'Perfil::index');
$routes->get('perfil/cambiarPassword', 'Perfil::cambiarPassword');
$routes->post('perfil/cambiarPassword', 'Perfil::cambiarPassword');
$routes->get('perfil/actualizar', 'Perfil::actualizar');
$routes->post('perfil/actualizar', 'Perfil::actualizar');

// Reportes
$routes->get('reportes', 'Reportes::index');
$routes->get('reportes/ventas', 'Reportes::ventas');
$routes->get('reportes/productos', 'Reportes::productos');

service('auth')->routes($routes);
