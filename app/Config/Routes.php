<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');
//rutas profesor
$routes->get('/profesor', 'Profesor::index');
$routes->get('/profesor/dashboard', 'Profesor::dashboard'); 
$routes->get('/profesor/pagos', 'Profesor::pagos');
$routes->post('profesor/pagos/procesar', 'Profesor::procesar');
$routes->get('/profesor/config_horarios', 'Profesor::config_horarios'); //crud horarios ver
$routes->get('/profesor/agg_horarios', 'Profesor::agg_horarios');  //crud horarios agregar
$routes->post('/profesor/store_horarios', 'Profesor::store_horarios'); //crud horarios guardar
$routes->get('/profesor/confirmacion_horario', 'Profesor::confirmacion_horario'); //crud horarios guardar confirmar
$routes->get('/profesor/dlt_horario/(:num)', 'Profesor::dlt_horario/$1'); //crud horarios eliminar
$routes->get('/profesor/edit_horario/(:num)', 'Profesor::edit_horario/$1'); //crud horarios editar
$routes->post('/profesor/update_horario/(:num)', 'Profesor::update_horario/$1'); //crud horarios editar confirmar

//rutas Alumno

$routes->get('alumno', 'Alumno::index');
$routes->get('alumno/calendario', 'Alumno::calendario'); 
$routes->get('/alumno/factura', 'Alumno::factura');
$routes->get('/alumno/mis_citas', 'Alumno::mis_citas');
$routes->post('/alumno/citas/guardar', 'Alumno::guardar');

//rutas Auth

$routes->get('/auth', 'Auth::index');
$routes->get('/auth/login', 'Auth::login'); 
$routes->get('/auth/registro', 'Auth::registro');
$routes->post('/auth/procesarlogin', 'Auth::procesarlogin');
$routes->post('/auth/registrarUsuario', 'Auth::registrarUsuario');
$routes->get('/salir', 'Auth::salir');