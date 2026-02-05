<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');
//rutas profesor
$routes->get('/profesor', 'Profesor::index');
$routes->get('profesor/dashboard', 'Profesor::dashboard');

$routes->get('/profesor/citas', 'Profesor::citas');
$routes->get('opiniones', 'Profesor::opiniones');
$routes->get('profesor/opiniones', 'Profesor::opiniones');
$routes->get('/profesor/calendario_profesor', 'Profesor::calendario_profesor');

$routes->post('profesor/citas/procesar', 'Profesor::procesar');
$routes->get('/profesor/HorarioLeer', 'Profesor::config_horarios'); //crud horarios ver
$routes->get('/profesor/agg_horarios', 'Profesor::agg_horarios');  //crud horarios agregar
$routes->post('/profesor/store_horarios', 'Profesor::store_horarios'); //crud horarios guardar
$routes->get('/profesor/confirmacion_horario', 'Profesor::confirmacion_horario'); //crud horarios guardar confirmar
$routes->get('/profesor/dlt_horario/(:num)', 'Profesor::dlt_horario/$1'); //crud horarios eliminar
$routes->get('/profesor/edit_horario/(:num)', 'Profesor::edit_horario/$1'); //crud horarios editar
$routes->post('/profesor/update_horario/(:num)', 'Profesor::update_horario/$1'); //crud horarios editar confirmar

//rutas Alumno

$routes->get('alumno', 'Alumno::index');
$routes->get('alumno/calendario_alumno', 'Alumno::calendario_alumno'); 
$routes->get('/alumno/factura', 'Alumno::factura');
$routes->get('alumno/feedback', 'Alumno::feedback');
$routes->post('alumno/feedback/guardar', 'Alumno::guardar');


$routes->get('alumno/inicio_alumno', 'Alumno::inicio_alumno'); 
$routes->get('/alumno/pago_estatico/(:num)', 'Alumno::pago_estatico/$1');
$routes->get('/alumno/pago_estatico', 'Alumno::pago_estatico');

$routes->get('/alumno/mis_citas', 'Alumno::mis_citas'); //ver bloques para citas disponibles
$routes->post('/alumno/store_citas', 'Alumno::store_citas'); //guardar la cita
$routes->post('alumno/guardar_pago', 'Alumno::guardar_pago'); // Guardar pago estatico Alumno

// Flujo de Pagos
$routes->get('/alumno/pago_estatico/(:num)', 'Alumno::pago_estatico/$1');
$routes->get('/alumno/pago_estatico', 'Alumno::pago_estatico'); // Fallback
$routes->post('/alumno/guardar_pago', 'Alumno::guardar_pago');

// Ruta de Factura (Captura ID manual o de PayPal)
$routes->get('/alumno/factura/(:any)', 'Alumno::factura/$1');

// Respuesta de PayPal
$routes->get('/alumno/pago_paypal_exito/(:any)', 'Alumno::pago_paypal_exito/$1');


//rutas Auth
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
