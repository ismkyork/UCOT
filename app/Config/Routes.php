<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');

// --- RUTAS DE AUTENTICACIÓN (Auth) ---
$routes->get('/auth', 'Auth::index');
$routes->get('/auth/actualizar_password', 'Auth::actualizar_password'); 
$routes->get('/auth/codigo_sesion', 'Auth::codigo_sesion'); 
$routes->get('/auth/login', 'Auth::login'); 
$routes->get('/auth/password_olvidada', 'Auth::password_olvidada'); 
$routes->get('/auth/registro', 'Auth::registro');
$routes->post('/auth/procesarlogin', 'Auth::procesarlogin');
$routes->post('/auth/registrarUsuario', 'Auth::registrarUsuario');
$routes->get('/salir', 'Auth::salir');

// --- RUTA DE CONFIGURACIÓN AGREGADA ---
$routes->get('configuracion', 'Configuracion::index');
$routes->post('configuracion/actualizar', 'Configuracion::actualizar');

// --- RUTAS DEL PROFESOR ---
$routes->group('profesor', function($routes) {
    $routes->get('/', 'Profesor::index');
    $routes->get('dashboard', 'Profesor::dashboard'); 
    $routes->get('citas', 'Profesor::citas');
    $routes->post('citas/procesar', 'Profesor::procesar');
    $routes->get('opiniones', 'Profesor::opiniones');
    $routes->get('profesor/opiniones', 'Profesor::opiniones');
   
    // Gestión de Horarios (CRUD)
    $routes->get('HorarioLeer', 'Profesor::config_horarios');
    $routes->get('agg_horarios', 'Profesor::agg_horarios');
    $routes->post('store_horarios', 'Profesor::store_horarios');
    $routes->get('confirmacion_horario', 'Profesor::confirmacion_horario');
    $routes->get('dlt_horario/(:num)', 'Profesor::dlt_horario/$1');
    $routes->get('edit_horario/(:num)', 'Profesor::edit_horario/$1');
    $routes->post('update_horario/(:num)', 'Profesor::update_horario/$1');
});

// --- RUTAS DEL ALUMNO ---
    $routes->group('alumno', function($routes) {
    $routes->get('/', 'Alumno::index');
    $routes->get('inicio_alumno', 'Alumno::inicio_alumno'); 
    $routes->get('calendario', 'Alumno::calendario'); 
    $routes->get('feedback', 'Alumno::feedback');
    $routes->post('feedback/guardar', 'Alumno::guardar');
    $routes->get('mis_citas', 'Alumno::mis_citas'); 
    $routes->post('store_citas', 'Alumno::store_citas'); 

    // Flujo de Pagos
    $routes->get('pago_estatico/(:num)', 'Alumno::pago_estatico/$1');
    $routes->get('pago_estatico', 'Alumno::pago_estatico'); // Fallback
    $routes->post('guardar_pago', 'Alumno::guardar_pago');
    
    // Ruta de Factura (Captura ID manual o de PayPal)
    $routes->get('factura/(:any)', 'Alumno::factura/$1');

    // Respuesta de PayPal
    $routes->get('pago_paypal_exito/(:any)', 'Alumno::pago_paypal_exito/$1');
});