<?php

namespace App\Controllers;

use App\Models\LoginModel;
use App\Models\ProfesorModel;
use App\Models\AlumnoModel;
use App\Models\CitaModel;
use App\Models\NotificacionModel;
use App\Models\PagoEstaticoModel;

class Admin extends BaseController
{
  public function dashboard() {
    $notifModel = new \App\Models\NotificacionModel();
    $loginModel = new \App\Models\LoginModel();
    $alumnoModel = new \App\Models\AlumnoModel();
    $citaModel = new \App\Models\CitaModel();
    $pagoModel = new \App\Models\PagoEstaticoModel();
        
    $id_auth = session()->get('id_auth'); 

    // 1. CARGA DE NOTIFICACIONES (Se mantienen en el arreglo $data)
    $data['notificaciones'] = $notifModel->misNotificaciones($id_auth);
    $data['no_leidas']      = $notifModel->contarNoLeidas($id_auth);

    // 2. LÓGICA DE TASA BCV
    $tasa_bcv = 390.29;
    try {
        $client = \Config\Services::curlrequest();
        $response = $client->get('https://open.er-api.com/v6/latest/USD', ['timeout' => 3]);
        $resultado = json_decode($response->getBody());
        
        if (isset($resultado->rates->VES)) { 
            $tasa_bcv = $resultado->rates->VES; 
        }
    } catch (\Exception $e) { 
        log_message('error', 'Fallo Tasa UCOT: ' . $e->getMessage()); 
    }

    $porcentaje_comision = 0.15;
    
    // Obtenemos la suma de los pagos confirmados
    $ingresos = $pagoModel->where('estado_pago', 'confirmado')->selectSum('monto')->get()->getRow();
    
    $total_bruto_bs = (float)($ingresos->monto ?? 0); 

    // Calculamos la contraparte en Bolívares multiplicando por la tasa del día
    $total_bruto_usd = $total_bruto_bs / $tasa_bcv;

    // Calculamos la ganancia neta (15%) sobre ambos montos
    $ganancia_ucot_bs = $total_bruto_bs * $porcentaje_comision;
    $ganancia_ucot_usd  = $total_bruto_usd * $porcentaje_comision;

    // 4. ASIGNACIÓN DE ESTADÍSTICAS (Sin sobreescribir $data)
    $data['total_profesores']  = $loginModel->where('rol', 'Profesor')->countAllResults();
    $data['total_estudiantes'] = $alumnoModel->countAllResults();
    $data['total_citas']       = $citaModel->countAllResults();
    $data['total_bruto_bs']    = $total_bruto_bs;
    $data['total_bruto_usd']   = $total_bruto_usd;
    $data['ganancia_ucot_bs']  = $ganancia_ucot_bs;
    $data['ganancia_ucot_usd'] = $ganancia_ucot_usd;
    $data['tasa_dia']          = $tasa_bcv;

    return view('vistas/admin/dashboard', $data);
}

    public function profesores()
{
    $db = \Config\Database::connect();
    
    $busqueda = $this->request->getGet('buscar');
    $filtroStatus = $this->request->getGet('status');

    $builder = $db->table('perfil_profesor p');
    $builder->select('p.*, a.correo, a.status, a.id_auth');
    $builder->join('auth a', 'a.id_auth = p.id_auth');

    if (!empty($busqueda)) {
        $builder->groupStart()
                ->like('p.nombre_profesor', $busqueda)
                ->orLike('p.apellido_profesor', $busqueda)
                ->orLike('a.correo', $busqueda)
                ->groupEnd();
    }

    if (!empty($filtroStatus)) {
        $builder->where('a.status', $filtroStatus);
    }

    $data['profesores'] = $builder->get()->getResultArray();
    
    $data['busqueda'] = $busqueda;
    $data['status_actual'] = $filtroStatus;

    $data['header'] = view('Template/header');
    $data['footer'] = view('Template/footer');
    $data['menu']   = view('Template/menu');

    return view('vistas/admin/lista_profesores', $data);
}

    public function nuevo_profesor()
    {
        $data = [
            'header' => view('Template/header'),
            'footer' => view('Template/footer'),
            'menu'   => view('Template/menu'),
        ];

        return view('vistas/admin/nuevo_profesor', $data);
    }

    public function guardar_profesor()
    {
        $loginModel = new LoginModel();
        $profesorModel = new ProfesorModel();

        $email = $this->request->getPost('correo');
        $password = $this->request->getPost('password');

        $userData = [
            'correo'     => $email,
            'contraseña' => password_hash($password, PASSWORD_DEFAULT),
            'rol'        => 'Profesor',
            'status'     => 'activo'
        ];

        $id_auth = $loginModel->insert($userData);

        if ($id_auth) {
            $profesorModel->insert([
                'id_auth'           => $id_auth,
                'nombre_profesor'   => $this->request->getPost('nombre'),
                'apellido_profesor' => $this->request->getPost('apellido')
            ]);

            return redirect()->to(base_url('admin/profesores'))->with('msg', 'Profesor creado con éxito.');
        }

        return redirect()->back()->with('error', 'No se pudo crear el profesor.');
    }

    public function eliminar_profesor($id_auth)
    {
        $loginModel = new LoginModel();
        
        if ($loginModel->delete($id_auth)) {
            return redirect()->to(base_url('admin/profesores'))->with('msg', 'Profesor eliminado correctamente.');
        }

        return redirect()->to(base_url('admin/profesores'))->with('error', 'Error al eliminar.');
    }

    public function editar_profesor($id_auth)
    {
        $db = \Config\Database::connect();
        
        $profesor = $db->table('auth a')
            ->select('a.id_auth, a.correo, a.status, p.nombre_profesor, p.apellido_profesor')
            ->join('perfil_profesor p', 'p.id_auth = a.id_auth')
            ->where('a.id_auth', $id_auth)
            ->get()->getRowArray();

        if (!$profesor) {
            return redirect()->to(base_url('admin/profesores'))->with('error', 'Profesor no encontrado.');
        }

        $data = [
            'profesor' => $profesor,
            'header'   => view('Template/header'),
            'footer'   => view('Template/footer'),
            'menu'     => view('Template/menu'),
        ];

        return view('vistas/admin/editar_profesor', $data);
    }

    public function actualizar_profesor($id_auth)
    {
        $loginModel = new \App\Models\LoginModel();
        $profesorModel = new \App\Models\ProfesorModel();

        $dataAuth = [
            'correo' => $this->request->getPost('correo'),
            'status' => $this->request->getPost('status')
        ];

        $nueva_pass = $this->request->getPost('password');
        if (!empty($nueva_pass)) {
            $dataAuth['contraseña'] = password_hash($nueva_pass, PASSWORD_DEFAULT);
        }

        $loginModel->update($id_auth, $dataAuth);

        $profesorModel->where('id_auth', $id_auth)->set([
            'nombre_profesor'   => $this->request->getPost('nombre'),
            'apellido_profesor' => $this->request->getPost('apellido')
        ])->update();

        return redirect()->to(base_url('admin/profesores'))->with('msg', 'Profesor actualizado correctamente.');
    }

public function citas() 
{
    $busqueda = $this->request->getGet('buscar');
    $filtroEstado = $this->request->getGet('estado');

    $db = \Config\Database::connect();
    $builder = $db->table('citas c');
    
    //JOIN CORREGIDO: id_profesor de citas = id_profesor de perfil_profesor
    $builder->select('
        c.*,
        p.nombre_profesor,
        p.apellido_profesor,
        e.nombre_estudiante,
        e.apellido_estudiante
    ');
    $builder->join('perfil_profesor p', 'p.id_profesor = c.id_profesor', 'left');
    $builder->join('perfiles_estudiantes e', 'e.id_estudiante = c.id_alumno', 'left');

    // Filtro de búsqueda (materia, nombre profesor, nombre estudiante)
    if (!empty($busqueda)) {
        $builder->groupStart()
                ->like('c.materia', $busqueda)
                ->orLike('p.nombre_profesor', $busqueda)
                ->orLike('p.apellido_profesor', $busqueda)
                ->orLike('e.nombre_estudiante', $busqueda)
                ->orLike('e.apellido_estudiante', $busqueda)
                ->groupEnd();
    }

    // Filtro por estado de la cita
    if (!empty($filtroEstado)) {
        $builder->where('c.estado_cita', $filtroEstado);
    }

    // Orden: más reciente primero
    $builder->orderBy('c.fecha_hora_inicio', 'DESC');

    $data['citas'] = $builder->get()->getResultArray();
    
    $data['busqueda'] = $busqueda;
    $data['estado_actual'] = $filtroEstado;

    $data['header'] = view('Template/header');
    $data['footer'] = view('Template/footer');
    $data['menu']   = view('Template/menu');

    return view('vistas/admin/lista_citas', $data);
}

    public function cambiar_estado_cita() {
    $id_cita = $this->request->getPost('id_cita');
    $estado  = $this->request->getPost('nuevo_estado'); // Se espera 'confirmado' o 'cancelado'

    if (empty($id_cita) || empty($estado)) {
        return redirect()->back()->with('error', 'Datos incompletos para actualizar la cita.');
    }

    $citaModel = new \App\Models\CitaModel();
    $citaData = $citaModel->find($id_cita);

    if (!$citaData) {
        return redirect()->back()->with('error', 'La cita no existe en el sistema.');
    }

    $estado_normalizado = strtolower($estado);
    $db = \Config\Database::connect();
    $db->transStart(); 

    try {
        // 1. Actualizamos la cita con el estado (confirmado/cancelado)
        $citaModel->update($id_cita, ['estado_cita' => $estado_normalizado]);

        $notifModel    = new \App\Models\NotificacionModel();
        $alumnoModel   = new \App\Models\AlumnoModel();
        $profesorModel = new \App\Models\ProfesorModel();

        // 2. BUSQUEDA MANUAL DE ID_AUTH (Para asegurar que llegue la notificación)
        // Buscamos directamente en las tablas de perfil usando los IDs de la cita
        $perfilAlumno = $db->table('perfiles_estudiantes')
                           ->where('id_estudiante', $citaData['id_alumno'])
                           ->get()->getRowArray();

        $perfilProfe  = $db->table('perfil_profesor')
                           ->where('id_profesor', $citaData['id_profesor'])
                           ->get()->getRowArray();

        $titulo = 'Actualización de Cita';
        $mensajeAlumno = '';
        $mensajeProfe  = '';

        //Lógica de mensajes según el nuevo estado de la cita
        if ($estado_normalizado == 'confirmado') {
            $titulo = '¡Cita Confirmada! ✅';
            $mensajeAlumno = "Tu pago para la clase de '{$citaData['materia']}' ha sido verificado. ¡Nos vemos en clase!";
            $mensajeProfe  = "Pago confirmado para la cita de '{$citaData['materia']}'. Revisa tu agenda.";
        } 
        elseif ($estado_normalizado == 'cancelado') {
            $titulo = 'Cita Cancelada ❌';
            $mensajeAlumno = "Tu solicitud para '{$citaData['materia']}' no pudo ser procesada y ha sido cancelada.";
            $mensajeProfe  = "La cita de '{$citaData['materia']}' ha sido cancelada por el administrador.";

            // Devolver cupo al horario
            $db->table('horarios')
               ->where('id_horario', $citaData['id_horario'])
               ->set('cupos_disponibles', 'cupos_disponibles + 1', false)
               ->set('estado', 'Disponible')
               ->update();
        }

        // --- ENVIAR AL ESTUDIANTE ---
        if ($perfilAlumno && isset($perfilAlumno['id_auth'])) {
            $notifModel->insert([
                'id_destinatario' => $perfilAlumno['id_auth'],
                'titulo'          => $titulo,
                'mensaje'         => $mensajeAlumno,
                'tipo'            => 'cita',
                'leido'           => 0
            ]);
        }

        // --- ENVIAR AL PROFESOR ---
        if ($perfilProfe && isset($perfilProfe['id_auth'])) {
            $notifModel->insert([
                'id_destinatario' => $perfilProfe['id_auth'],
                'titulo'          => $titulo,
                'mensaje'         => $mensajeProfe,
                'tipo'            => 'cita',
                'leido'           => 0
            ]);
        }

        $db->transComplete();

        if ($db->transStatus() === FALSE) {
            return redirect()->back()->with('error', 'Error en la base de datos al procesar la confirmación.');
        }

        return redirect()->to(base_url('admin/dashboard'))->with('msg', 'Cita procesada y notificaciones enviadas.');

    } catch (\Exception $e) {
        $db->transRollback();
        return redirect()->back()->with('error', 'Ocurrió un error inesperado: ' . $e->getMessage());
    }
}



    public function pagos() 
    {
        $db = \Config\Database::connect();
        
        $data['pagos'] = $db->table('pagos p')
            ->select('p.*, c.materia, e.nombre_estudiante, e.apellido_estudiante')
            ->join('citas c', 'c.id_cita = p.id_cita')
            ->join('perfiles_estudiantes e', 'e.id_estudiante = c.id_alumno')
            ->orderBy('p.estado_pago', 'ASC')
            ->get()->getResultArray();

        $data['header'] = view('Template/header');
        $data['footer'] = view('Template/footer');
        $data['menu']   = view('Template/menu');

        return view('vistas/admin/lista_pagos', $data);
    }

    public function confirmar_pago($id_pago) 
{
    $pagoModel = new PagoEstaticoModel();
    $citaModel = new CitaModel();
    $notifModel = new \App\Models\NotificacionModel();
    
    $pago = $pagoModel->find($id_pago);

    if ($pago) {
        $db = \Config\Database::connect();
        $db->transStart();

        // 1. Actualizar el Pago a confirmado
        $pagoModel->update($id_pago, [
            'estado_pago' => 'confirmado',
            'fecha_confirmacion' => date('Y-m-d H:i:s')
        ]);

        // 2. Actualizar la Cita a confirmada
        $citaModel->update($pago['id_cita'], [
            'estado_cita' => 'confirmado' 
        ]);

        // 3. OBTENER DATOS PARA NOTIFICACIONES
        // Necesitamos la materia de la cita y los id_auth de los perfiles
        $builderCita = $db->table('citas c')
            ->select('c.materia, p.id_auth as auth_profe, e.id_auth as auth_estudiante')
            ->join('perfil_profesor p', 'p.id_profesor = c.id_profesor')
            ->join('perfiles_estudiantes e', 'e.id_estudiante = c.id_alumno')
            ->where('c.id_cita', $pago['id_cita'])
            ->get()->getRowArray();

        if ($builderCita) {
            $materia = $builderCita['materia'];

            // Notificación para el ESTUDIANTE
            $notifModel->insert([
                'id_destinatario' => $builderCita['auth_estudiante'],
                'titulo'          => '¡Pago Verificado! ✅',
                'mensaje'         => "Tu pago para la clase de '$materia' ha sido aprobado. Ya puedes asistir a tu tutoría.",
                'tipo'            => 'pago',
                'leido'           => 0
            ]);

            // Notificación para el PROFESOR
            $notifModel->insert([
                'id_destinatario' => $builderCita['auth_profe'],
                'titulo'          => 'Nuevo Pago Confirmado 💰',
                'mensaje'         => "Se ha verificado el pago para tu clase de '$materia'. Revisa tu agenda.",
                'tipo'            => 'pago',
                'leido'           => 0
            ]);
        }

        $db->transComplete();

        if ($db->transStatus() === FALSE) {
            return redirect()->to(base_url('admin/pagos'))->with('error', 'Error en la base de datos al procesar.');
        }

        return redirect()->to(base_url('admin/pagos'))->with('msg', 'Pago verificado y notificaciones enviadas.');
    }

    return redirect()->to(base_url('admin/pagos'))->with('error', 'No se encontró el registro de pago.');
}

    public function perfil_profesor($id_auth)
{
    $db = \Config\Database::connect();
    
    // 1. Obtener el perfil completo y el ID real del profesor
    $profesor = $db->table('auth a')
        ->select('a.id_auth, a.correo, a.status, p.id_profesor, p.nombre_profesor, p.apellido_profesor')
        ->join('perfil_profesor p', 'p.id_auth = a.id_auth')
        ->where('a.id_auth', $id_auth)
        ->get()->getRowArray();

    if (!$profesor) return redirect()->to(base_url('admin/profesores'))->with('error', 'Profesor no encontrado.');

    $id_real_profesor = $profesor['id_profesor'];

    // 2. Obtener Tasa BCV actualizada
    $tasa_bcv = 390.29; // Valor por defecto actualizado
    try {
        $client = \Config\Services::curlrequest();
        $response = $client->get('https://open.er-api.com/v6/latest/USD', ['timeout' => 3]);
        $resultado = json_decode($response->getBody());
        if (isset($resultado->rates->VES)) $tasa_bcv = $resultado->rates->VES;
    } catch (\Exception $e) { }

    // 3. Obtener pagos filtrando por el ID REAL del profesor
    $pagos = $db->table('pagos p')
        ->select('p.monto, p.id_pago')
        ->join('citas c', 'c.id_cita = p.id_cita')
        ->where('c.id_profesor', $id_real_profesor)
        ->where('p.estado_pago', 'confirmado')
        ->get()->getResultArray();

    $total_acumulado_bs = 0;

    foreach ($pagos as $pago) {
        // Si el ID de pago contiene PAYPAL, asumimos que el monto está en USD y convertimos a BS
        $es_paypal = (strpos(strtoupper($pago['id_pago']), 'PAYPAL') !== false);
        
        if ($es_paypal) {
            $total_acumulado_bs += ((float)$pago['monto'] * $tasa_bcv);
        } else {
            $total_acumulado_bs += (float)$pago['monto'];
        }
    }

    // 4. Estructura de Finanzas
    $finanzas = [
        'total_bruto'    => $total_acumulado_bs,
        'ganancia_profe' => $total_acumulado_bs * 0.85,
        'comision_ucot'  => $total_acumulado_bs * 0.15,
        'total_citas'    => count($pagos)
    ];

    // 5. Obtener últimas citas con el ID correcto
    $citaModel = new \App\Models\CitaModel();
    $ultimas_citas = $citaModel->where('id_profesor', $id_real_profesor)
                               ->orderBy('fecha_hora_inicio', 'DESC')
                               ->limit(5)
                               ->findAll();

    $data = [
        'profesor'      => $profesor,
        'finanzas'      => $finanzas,
        'ultimas_citas' => $ultimas_citas,
        'tasa_bcv'      => $tasa_bcv,
        'header'        => view('Template/header'),
        'footer'        => view('Template/footer'),
        'menu'          => view('Template/menu'),
    ];

    return view('vistas/admin/perfil_profesor_detalle', $data);
}
}