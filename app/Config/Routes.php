<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');
//rutas profesor
$routes->get('/profesor', 'Profesor::index');
$routes->get('/profesor/config_horarios', 'Profesor::config_horarios');
$routes->get('/profesor/dashboard', 'Profesor::dashboard'); 
$routes->get('/profesor/citas', 'Profesor::citas');
$routes->post('profesor/citas/procesar', 'Profesor::procesar');

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