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

$routes->get('/profesor/HorarioLeer', 'Profesor::config_horarios'); //crud horarios ver
$routes->get('/profesor/agg_horarios', 'Profesor::agg_horarios');  //crud horarios agregar
$routes->post('/profesor/store_horarios', 'Profesor::store_horarios'); //crud horarios guardar
$routes->get('/profesor/confirmacion_horario', 'Profesor::confirmacion_horario'); //crud horarios guardar confirmar
$routes->get('/profesor/dlt_horario/(:num)', 'Profesor::dlt_horario/$1'); //crud horarios eliminar
$routes->get('/profesor/edit_horario/(:num)', 'Profesor::edit_horario/$1'); //crud horarios editar
$routes->post('/profesor/update_horario/(:num)', 'Profesor::update_horario/$1'); //crud horarios editar confirmar

$routes->get('profesor/configurar_sistemas', 'Profesor::configurar_sistemas');
$routes->post('profesor/guardar_sistemas', 'Profesor::guardar_sistemas');

// --- RUTAS API PARA EL CALENDARIO ---
$routes->get('profesor/obtener_horarios_api', 'Profesor::obtener_horarios_api');
$routes->post('profesor/guardar_horarios_api', 'Profesor::guardar_horarios_api');
$routes->post('profesor/eliminar_horario_api', 'Profesor::eliminar_horario_api');

//rutas Alumno

$routes->get('alumno', 'Alumno::index');
$routes->get('/alumno/factura', 'Alumno::factura');
$routes->get('alumno/feedback', 'Alumno::feedback');
$routes->post('alumno/feedback/guardar', 'Alumno::guardar');
$routes->post('alumno/guardar', 'Alumno::guardar');
$routes->get('alumno/calendario_alumno', 'Alumno::calendario_alumno');
$routes->get('alumno/comprobantes_pagos', 'Alumno::comprobantes_pagos');
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
//Seleccionar profesor para cita
// 1. Mostrar la pantalla de tarjetas de profesores
$routes->get('alumno/elegir_profesor', 'Alumno::elegir_profesor');
// 2. Procesar la elección (El (:num) es el ID del profe)
$routes->get('alumno/establecer_profesor/(:num)', 'Alumno::establecer_profesor/$1');
// Se usa GET porque en el controlador usas $this->request->getGet(...)
$routes->get('/alumno/obtener_horarios_profesor_api', 'Alumno::obtener_horarios_profesor_api');
// Se usa POST porque envías datos JSON para guardar en la base de datos
 $routes->post('/alumno/reservar_cita_api', 'Alumno::reservar_cita_api');


//rutas Auth
$routes->get('/auth', 'Auth::index');
$routes->get('/auth/actualizar_password', 'Auth::actualizar_password'); 
$routes->get('/auth/login', 'Auth::login'); 
$routes->get('/auth/password_olvidada', 'Auth::password_olvidada'); 
$routes->get('/auth/registro', 'Auth::registro');
$routes->post('/auth/procesarlogin', 'Auth::procesarlogin');
$routes->post('/auth/registrarUsuario', 'Auth::registrarUsuario');
$routes->get('/salir', 'Auth::salir');

//Token
$routes->get('auth/activar/(:any)', 'Auth::activar/$1');           // Link del correo de bienvenida
$routes->post('auth/enviar_recovery', 'Auth::enviar_recovery');    // Formulario "Olvidé contraseña"
$routes->get('auth/reset/(:any)', 'Auth::vista_reset/$1');         // Link del correo de recuperación
$routes->post('auth/guardar_clave', 'Auth::guardar_clave');        // Formulario final de cambio de clave

$routes->post('profesor/enviar_solicitud_retiro', 'Profesor::enviar_solicitud_retiro'); //Ruta para correos de solicitud de pagos


//notificaciones
$routes->get('ver-notificacion/(:num)', 'Notificacion::leer/$1');



// --- RUTA DE CONFIGURACIÓN ---

// 1. La página principal del panel (apunta a index)
$routes->get('configuracion', 'Configuracion::index'); 

// 2. Vistas de edición
$routes->get('configuracion/editar_foto', 'Configuracion::editar_foto');
$routes->get('configuracion/editar_nombre', 'Configuracion::editar_nombre');
$routes->post('configuracion/guardar_sistemas', 'Configuracion::guardar_sistemas');
$routes->post('configuracion/actualizar_materias_vinculo', 'Configuracion::actualizar_materias_vinculo');
$routes->post('configuracion/actualizar_precio', 'Configuracion::actualizar_precio');

// 3. Procesos (POST)
$routes->post('configuracion/actualizar', 'Configuracion::actualizar'); // Nombres
$routes->post('configuracion/actualizar_foto', 'Configuracion::actualizar_foto');
$routes->post('configuracion/cambiar_password', 'Configuracion::cambiar_password');

// --- RUTAS DE ADMINISTRADOR (UCOT) ---
$routes->group('admin', function($routes) {
    // Dashboard principal
    $routes->get('dashboard', 'Admin::dashboard');
    
    //Gestion de Citas y Pagos
    $routes->get('citas', 'Admin::citas');
    $routes->get('pagos', 'Admin::pagos');
    $routes->get('confirmar_pago/(:any)', 'Admin::confirmar_pago/$1');
    $routes->post('cambiar_estado_cita', 'Admin::cambiar_estado_cita');
    
    // Gestión de Profesores
    $routes->get('profesores', 'Admin::profesores');
    $routes->get('nuevo_profesor', 'Admin::nuevo_profesor');
    $routes->post('guardar_profesor', 'Admin::guardar_profesor');
    $routes->get('perfil_profesor/(:num)', 'Admin::perfil_profesor/$1');    
    // Acciones (Editar/Eliminar)
    $routes->get('eliminar_profesor/(:num)', 'Admin::eliminar_profesor/$1');
    $routes->get('editar_profesor/(:num)', 'Admin::editar_profesor/$1');
    $routes->post('actualizar_profesor/(:num)', 'Admin::actualizar_profesor/$1');
});

$routes->set404Override(function() {
    return view('errors/html/error_404');
});
