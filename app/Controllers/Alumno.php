<?php

namespace App\Controllers;

use App\Models\CitaModel;
use App\Models\HorarioModel;
use App\Models\PagoEstaticoModel;
use App\Models\FeedbackModel;
use App\Models\NotificacionModel;
use App\Models\ProfesorModel;

class Alumno extends BaseController
{
    private function getIdEstudianteReal() {
        $idAuth = session()->get('id_auth');
        $db = \Config\Database::connect();
        $perfil = $db->table('perfiles_estudiantes')
                     ->select('id_estudiante') 
                     ->where('id_auth', $idAuth)
                     ->get()->getRowArray();

        return $perfil ? $perfil['id_estudiante'] : null;
    }

    public function index() {   
        $info['footer'] = view('Template/footer');
        $info['header'] = view('Template/header');
        $info['menu'] = view('Template/menu');
        return view('vistas/inicio', $info);
    }

 
    public function inicio_alumno() {
        
        // 1. Validar Perfil de Alumno
        $idAlumnoReal = $this->getIdEstudianteReal();
        if (!$idAlumnoReal) {
            return redirect()->to('/')->with('error', 'Perfil de estudiante no encontrado.');
        }
        // ======================================================
        // CARGAR NOTIFICACIONES PARA EL HEADER
        // ======================================================
        $notifModel = new NotificacionModel();
        
        // Obtenemos el ID de autenticación (usuario logueado)
        $id_auth = session()->get('id_auth'); 

        // 2. Lógica de Próxima Cita (Tu código original)
        $db = \Config\Database::connect();
        $builder = $db->table('citas c');
        
        $builder->select('c.*, prof.nombre_profesor, prof.apellido_profesor');
        $builder->join('perfil_profesor prof', 'prof.id_profesor = c.id_profesor', 'left');
        
        $builder->where('c.id_alumno', $idAlumnoReal);
        $builder->where('c.fecha_hora_inicio >=', date('Y-m-d H:i:s')); 
        $builder->orderBy('c.fecha_hora_inicio', 'ASC');
        $builder->limit(1);

        $data['proxima_cita'] = $builder->get()->getRowArray();
          // Cargamos los datos en el array $data
        $data['notificaciones'] = $notifModel->misNotificaciones($id_auth);
        $data['no_leidas']      = $notifModel->contarNoLeidas($id_auth);
        // ======================================================

        // 3. Enviamos $data a la vista (contiene cita + notificaciones)
        return view('vistas/alumno/inicio_alumno', $data);
    }



     // 1. VER OPINIONES Y FORMULARIO
    public function feedback()
    {
        $feedbackModel = new FeedbackModel();
        $profesorModel = new ProfesorModel();

        // A. Obtenemos la lista de TODOS los profesores para el menú desplegable
        $listaProfesores = $profesorModel->findAll();

        // B. Verificamos si el alumno seleccionó un profesor (viene por la URL ?id_profesor=5)
        $id_profe_seleccionado = $this->request->getVar('id_profesor');
        
        $comentarios_publicos = [];
        $profe_info = null;

        if ($id_profe_seleccionado) {
            // Usamos la función del modelo para traer comentarios + nombres de alumnos
            $comentarios_publicos = $feedbackModel->obtenerComentariosPorProfesor($id_profe_seleccionado);
            $profe_info = $profesorModel->where('id_profesor', $id_profe_seleccionado)->first();
        }

        $data = [
            'profesores'           => $listaProfesores,
            'comentarios_publicos' => $comentarios_publicos,
            'profe_actual'         => $profe_info,
            'id_seleccionado'      => $id_profe_seleccionado
        ];

        $info = [];
        $info['header'] = view('Template/header');
        $info['menu']   = view('Template/menu');
        $info['footer'] = view('Template/footer');

        return view('vistas/alumno/feedback', array_merge($info, $data));
    }

    // 2. GUARDAR LA OPINIÓN Y NOTIFICAR AL PROFESOR
    public function guardar()
    {
        $feedbackModel = new FeedbackModel();

        // 1. Validamos que haya seleccionado al profesor
        $reglas = [
            'id_profesor' => 'required|numeric', 
            'puntuacion'  => 'required|numeric',
            'comentario'  => 'required|min_length[5]'
        ];

        if (!$this->validate($reglas)) {
            return redirect()->back()->withInput()->with('error', 'Faltan datos. Selecciona un profesor y califica.');
        }

        // 2. Preparamos los datos del Feedback
        $datos = [
            'id_profesor'      => $this->request->getPost('id_profesor'),
            'id_estudiante'    => session()->get('id_alumno'),
            'puntuacion'       => $this->request->getPost('puntuacion'),
            'comentario'       => $this->request->getPost('comentario'),
            'fecha_evaluacion' => date('Y-m-d H:i:s')
        ];

        // 3. Guardamos el Feedback
        $feedbackModel->save($datos);

        //  NOTIFICAR AL PROFESOR
        
        $profesorModel = new ProfesorModel();
        $notifModel    = new NotificacionModel();

        // Buscamos los datos del profesor para obtener su 'id_auth'
        // (Porque la tabla de notificaciones usa id_auth, no id_profesor)
        $profe = $profesorModel->find($datos['id_profesor']);

        if ($profe) {
            $notifModel->save([
                'id_destinatario' => $profe['id_auth'], // NOTA: Aquí es donde se conecta el id_profesor con el id_auth del profesor
                'titulo'          => 'Nueva Opinión Recibida',
                'mensaje'         => 'Un estudiante ha calificado tu clase con ' . $datos['puntuacion'] . ' estrellas.',
                'tipo'            => 'feedback',
                'leido'           => 0,
                'created_at'      => date('Y-m-d H:i:s')
            ]);
        }
        // ======================================================

        // 4. Redirigimos
        return redirect()->to('/alumno/feedback?id_profesor=' . $datos['id_profesor'])
                         ->with('msg', '¡Tu opinión ha sido publicada y notificada!');
    }


    public function factura($id_pago = null) {
        if (!$id_pago) {
            return redirect()->to('/alumno/mis_citas')->with('error', 'No se especificó un comprobante.');
        }

        $db = \Config\Database::connect();
        $builder = $db->table('pagos p');
        
        $builder->select('
            p.*, 
            c.fecha_hora_inicio, 
            c.materia, 
            prof.nombre_profesor, 
            prof.apellido_profesor, 
            est.nombre_estudiante as nombre_alumno,
            est.apellido_estudiante as apellido_alumno
        ');
        
        $builder->join('citas c', 'c.id_cita = p.id_cita');
        $builder->join('perfil_profesor prof', 'prof.id_profesor = c.id_profesor'); 
        $builder->join('perfiles_estudiantes est', 'est.id_estudiante = c.id_alumno');   
        
        $builder->where('p.id_pago', $id_pago);
        $data['pago'] = $builder->get()->getRowArray();

        if (!$data['pago']) {
            return redirect()->to('/alumno/mis_citas')->with('error', 'La factura no existe.');
        }
        $tasa_bcv = 390.29;

        try {
            $client = \Config\Services::curlrequest();
            $response = $client->get('https://open.er-api.com/v6/latest/USD', ['timeout' => 3]);
            $resultado = json_decode($response->getBody());
            if (isset($resultado->rates->VES)) { $tasa_bcv = $resultado->rates->VES; }
        } catch (\Exception $e) { log_message('error', 'Fallo Tasa: ' . $e->getMessage()); }
        
        $data['tasa_bcv'] = $tasa_bcv;
        $info['footer'] = view('Template/footer');
        $info['header'] = view('Template/header');
        $info['menu']   = view('Template/menu');

        return view('vistas/alumno/factura', array_merge($info, $data));
    }

    public function elegir_profesor()
    {
        $db = \Config\Database::connect();
        
        $profesores = $db->table('perfil_profesor')
                        ->orderBy('nombre_profesor', 'ASC')
                        ->get()->getResultArray();

        foreach ($profesores as &$profe) {
            $id = $profe['id_profesor'];

            $profe['materias'] = $db->table('materias_profesor mp')
                ->join('materias m', 'm.id_materia = mp.id_materia')
                ->where('mp.id_profesor', $id)
                ->select('m.nombre_materia')
                ->get()->getResultArray();

            $profe['sistemas'] = $db->table('profesor_sistemas_vinculo psv')
                ->join('sistemas_clase sc', 'sc.id = psv.id_sistema')
                ->where('psv.id_profesor', $id)
                ->select('sc.nombre')
                ->get()->getResultArray();
        }
        unset($profe);

        $data = [
            'profesores' => $profesores,
            'header' => view('Template/header'),
            'footer' => view('Template/footer'),
            'menu'   => view('Template/menu')
        ];

        return view('vistas/alumno/seleccionar_profesor', $data);
    }

    public function establecer_profesor($idProfesor) {
        session()->set('id_profesor_seleccionado', $idProfesor);
        return redirect()->to('alumno/mis_citas');
    }

    public function mis_citas() {
        $idAlumnoReal = $this->getIdEstudianteReal();
        if (!$idAlumnoReal) { return redirect()->to('/')->with('error', 'Error de perfil'); }

        $idProfesorSeleccionado = session()->get('id_profesor_seleccionado');
        if (!$idProfesorSeleccionado) { return redirect()->to('alumno/elegir_profesor'); }

        $modelHorario = new HorarioModel();
        $db = \Config\Database::connect();

        // 1. Datos del Profesor
        $data['profesor_actual'] = $db->table('perfil_profesor')
                                      ->where('id_profesor', $idProfesorSeleccionado)
                                      ->get()->getRowArray();

        $fechaHoy = date('Y-m-d');
        $horaActual = date('H:i:s');

        // 2. Horarios Disponibles
        $data['horarios'] = $modelHorario
            ->select('horarios.*, m.nombre_materia, sc.nombre as nombre_sistema') 
            ->join('materias m', 'm.id_materia = horarios.id_materia', 'left')    
            ->join('sistemas_clase sc', 'sc.id = horarios.id_sistema', 'left')    
            ->where('horarios.id_profesor', $idProfesorSeleccionado) 
            ->where('horarios.estado', 'Disponible')
            ->where('horarios.cupos_disponibles >', 0)
            ->groupStart()
                ->where('horarios.fecha >', $fechaHoy)
                ->orGroupStart()
                    ->where('horarios.fecha', $fechaHoy)
                    ->where('horarios.hora_inicio >', $horaActual)
                ->groupEnd()
            ->groupEnd()
            ->orderBy('horarios.fecha', 'ASC')
            ->orderBy('horarios.hora_inicio', 'ASC')
            ->findAll();

        // 3. Listas para selects
        $data['materias_profe'] = $db->table('materias_profesor m_v')
                                     ->select('m.nombre_materia')
                                     ->join('materias m', 'm.id_materia = m_v.id_materia')
                                     ->where('m_v.id_profesor', $idProfesorSeleccionado)
                                     ->get()->getResultArray();

        $data['sistemas_profe'] = $db->table('profesor_sistemas_vinculo s_p')
                                     ->select('sc.nombre')
                                     ->join('sistemas_clase sc', 'sc.id = s_p.id_sistema')
                                     ->where('s_p.id_profesor', $idProfesorSeleccionado)
                                     ->get()->getResultArray();

        // 4. CITAS YA RESERVADAS (Para bloquear botones)
        $data['citas_reservadas'] = $db->table('citas')
            ->where('id_alumno', $idAlumnoReal)
            ->where('id_profesor', $idProfesorSeleccionado)
            ->where('estado_cita !=', 'cancelado') // Solo activas
            ->get()->getResultArray();

        $data['tasa_bcv'] = 390.29; 

        return view('vistas/alumno/mis_citas', array_merge($data));
    }

    public function comprobantes_pagos()
    {
        $idAlumnoReal = $this->getIdEstudianteReal();
        if (!$idAlumnoReal) { return redirect()->to('/')->with('error', 'Error de perfil'); }

        $db = \Config\Database::connect();
        
        $builder = $db->table('citas c');
        $builder->select('c.*, p.id_pago, p.estado_pago, prof.nombre_profesor, prof.apellido_profesor');
        $builder->join('pagos p', 'p.id_cita = c.id_cita', 'left'); 
        $builder->join('perfil_profesor prof', 'prof.id_profesor = c.id_profesor', 'left');
        
        $builder->where('c.id_alumno', $idAlumnoReal);
        $builder->orderBy('c.fecha_hora_inicio', 'DESC');
        
        $data['citas_reservadas'] = $builder->get()->getResultArray();

        return view('vistas/alumno/comprobantes_pagos', array_merge($data));
    }

    public function store_citas() {
    $citaModel     = new \App\Models\CitaModel();
    $horarioModel  = new \App\Models\HorarioModel();
    $notifModel    = new \App\Models\NotificacionModel(); 
    $profesorModel = new \App\Models\ProfesorModel();     
    
    $idAlumnoReal = $this->getIdEstudianteReal();
    if (!$idAlumnoReal) {
        return redirect()->back()->with('error', 'Error: Perfil de estudiante no encontrado.');
    }

    $idHorario = $this->request->getVar('id_horario'); 
    $materia   = $this->request->getVar('materia_nombre');
    $fecha     = $this->request->getVar('fecha_seleccionada');
    $sistema   = $this->request->getPost('sistema_nombre'); 

    $horarioData = $horarioModel->find($idHorario);
    if (!$horarioData) {
        return redirect()->back()->with('error', 'El horario seleccionado ya no existe.');
    }

    $db = \Config\Database::connect();
    $db->transStart();

    try {
        // 1. Actualizar cupos y estado del horario
        $horarioModel->where('id_horario', $idHorario)
                     ->set('cupos_disponibles', 'cupos_disponibles - 1', false)
                     ->update();

        $horarioActualizado = $horarioModel->find($idHorario);
        if ($horarioActualizado['cupos_disponibles'] <= 0) {
            $horarioModel->update($idHorario, ['estado' => 'Reservado']);
        }

        // 2. Calcular duración e insertar cita
        $inicio = new \DateTime($horarioData['hora_inicio']);
        $fin    = new \DateTime($horarioData['hora_fin']);
        $totalMinutos = ($inicio->diff($fin)->h * 60) + $inicio->diff($fin)->i;

        $dataInsert = [
            'id_alumno'         => $idAlumnoReal,
            'id_profesor'       => $horarioData['id_profesor'],
            'id_horario'        => (int)$idHorario,
            'fecha_hora_inicio' => $fecha . ' ' . $horarioData['hora_inicio'],
            'duracion_min'      => $totalMinutos,
            'materia'           => $materia,
            'sistema'           => $sistema,
            'estado_cita'       => 'pendiente'
        ];

        $citaModel->insert($dataInsert);
        $ultimaCitaId = $citaModel->getInsertID();

        $db->transComplete();

        if ($db->transStatus() === TRUE) {
            
            // --- BLOQUE DE NOTIFICACIONES CORREGIDO ---

            // A. Notificar al PROFESOR (Buscamos el id_auth en la tabla perfil_profesor)
            $profe = $profesorModel->where('id_profesor', $horarioData['id_profesor'])->first();
            if ($profe && isset($profe['id_auth'])) {
                $notifModel->insert([
                    'id_destinatario' => $profe['id_auth'], // Usamos el id_auth vinculado
                    'titulo'          => 'Nueva Solicitud de Cita',
                    'mensaje'         => 'Tienes una nueva cita pendiente para la materia: ' . $materia,
                    'tipo'            => 'cita',
                    'leido'           => 0
                ]);
            }

            // B. Notificar al ADMIN (Asegúrate que el id_auth 1 sea el admin en tu tabla 'auth')
            $notifModel->insert([
                'id_destinatario' => 1, 
                'titulo'          => 'Nuevo Pago Pendiente',
                'mensaje'         => 'Se ha generado una cita que requiere verificación (Cita #' . $ultimaCitaId . ')',
                'tipo'            => 'sistema',
                'leido'           => 0
            ]);

            // C. Notificar al ESTUDIANTE (Usamos el ID de la sesión actual)
            $idAuthEstudiante = session()->get('id_auth');
            if ($idAuthEstudiante) {
                $notifModel->insert([
                    'id_destinatario' => $idAuthEstudiante, 
                    'titulo'          => 'Solicitud Enviada',
                    'mensaje'         => 'Tu solicitud para ' . $materia . ' ha sido creada exitosamente.',
                    'tipo'            => 'cita',
                    'leido'           => 0
                ]);
            }

            return redirect()->to(base_url('alumno/pago_estatico/' . $ultimaCitaId));
        } else {
            return redirect()->back()->with('error', 'Error en la transacción de base de datos.');
        }

    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Error crítico: ' . $e->getMessage());
    }
}


    public function pago_estatico($id_cita = null) {
        if (!$id_cita) {
            return redirect()->to('alumno/mis_citas')->with('error', 'No se especificó una cita.');
        }

        $db = \Config\Database::connect();
        
        $cita = $db->table('citas')->where('id_cita', $id_cita)->get()->getRowArray();
        
        $profesor = $db->table('perfil_profesor')
                    ->where('id_profesor', $cita['id_profesor'])
                    ->get()->getRowArray();

        $precio_usd = $profesor['precio_clase'] ?? 0;
        $tasa_bcv = 390.29;
        try {
            $client = \Config\Services::curlrequest();
            $response = $client->get('https://open.er-api.com/v6/latest/USD', ['timeout' => 3]);
            $resultado = json_decode($response->getBody());
            if (isset($resultado->rates->VES)) {
                $tasa_bcv = $resultado->rates->VES;
            }
        } catch (\Exception $e) {
            log_message('error', 'Fallo de API Tasa: ' . $e->getMessage());
        }

        session()->set('id_cita_pendiente', $id_cita);

        $info['id_cita'] = $id_cita;
        $info['precio_paypal'] = number_format($precio_usd, 2, '.', '');
        $info['precio_bs'] = $precio_usd * $tasa_bcv;
        $info['tasa_bcv'] = $tasa_bcv;

        return view('vistas/alumno/pago_estatico', $info);
    }

    public function guardar_pago() {
        $modelPago = new PagoEstaticoModel();

        $id_cita = $this->request->getPost('id_cita');
        $id_pago = $this->request->getPost('id_pago');
        
        $montoRaw = $this->request->getPost('monto');
        $montoLimpio = str_replace(['.', ','], ['', '.'], $montoRaw);

        if (empty($id_cita)) {
            return redirect()->back()->with('error', 'No se recibió el ID de la cita.');
        }

        $reglas = [
            'id_pago'     => 'required|is_unique[pagos.id_pago]',
            'monto'       => 'required',
            'fecha_pago'  => 'required',
            'imagen_pago' => 'uploaded[imagen_pago]|max_size[imagen_pago,2048]|is_image[imagen_pago]'
        ];

        if (!$this->validate($reglas)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $img = $this->request->getFile('imagen_pago');
        $nombreImg = $img->getRandomName();
        
        $data = [
            'id_cita'     => $id_cita,
            'id_pago'     => $id_pago, 
            'monto'       => $montoLimpio,
            'fecha_pago'  => $this->request->getPost('fecha_pago'),
            'screenshot'  => $nombreImg,
            'estado_pago' => 'pendiente'
        ];

        $db = \Config\Database::connect();
        $db->transStart();

        $modelPago->insert($data);

        $db->transComplete();

        if ($db->transStatus() === TRUE) {
            $img->move(ROOTPATH . 'public/uploads/comprobantes', $nombreImg);
            
            session()->remove('id_cita_pendiente'); 
            
            return redirect()->to(base_url('alumno/factura/' . $id_pago))
                             ->with('success', 'Pago enviado. Esperando confirmación del docente.');
        } else {
            return redirect()->back()->with('error', 'Error al procesar el pago en base de datos.');
        }
    }

    public function calendario_alumno() {
        $db = \Config\Database::connect();
        
        $profesores = $db->table('perfil_profesor')
                        ->select('id_profesor, nombre_profesor as nombre, apellido_profesor as apellido')
                        ->orderBy('nombre_profesor', 'ASC')
                        ->get()->getResultArray();

        $idPreseleccionado = session()->get('id_profesor_seleccionado');
        
        $materias = [];
        $sistemas = [];
        
        if ($idPreseleccionado) {
            $materias = $db->table('materias_profesor mp')
                        ->join('materias m', 'm.id_materia = mp.id_materia')
                        ->where('mp.id_profesor', $idPreseleccionado)
                        ->select('m.nombre_materia')
                        ->get()->getResultArray();

            $sistemas = $db->table('profesor_sistemas_vinculo psv')
                        ->join('sistemas_clase sc', 'sc.id = psv.id_sistema')
                        ->where('psv.id_profesor', $idPreseleccionado)
                        ->select('sc.nombre')
                        ->get()->getResultArray();
        }

        $data = [
            'profesores' => $profesores,
            'id_preseleccionado' => $idPreseleccionado,
            'materias_json' => json_encode($materias), 
            'sistemas_json' => json_encode($sistemas),
            'header' => view('Template/header'),
            'footer' => view('Template/footer'),
            'menu'   => view('Template/menu')
        ];

        return view('vistas/alumno/calendario_alumno', $data);
    }

   public function obtener_horarios_profesor_api() {
        $id_profesor = $this->request->getGet('id_profesor');
        if(!$id_profesor) return $this->response->setJSON([]);

        $idAuth = session()->get('id_auth');
        $db = \Config\Database::connect();
        
        // 1. Obtener ID Alumno
        $perfilAlumno = $db->table('perfiles_estudiantes')->where('id_auth', $idAuth)->get()->getRowArray();
        $idAlumnoActual = $perfilAlumno ? $perfilAlumno['id_estudiante'] : 0;

        $fechaHoy = date('Y-m-d');
        $horaActual = date('H:i:s');

        // 2. Consulta
        $builder = $db->table('horarios h');
        $builder->select('
            h.id_horario, h.fecha, h.hora_inicio, h.hora_fin, 
            h.cupos_totales, h.cupos_disponibles, h.estado,
            m.nombre_materia, 
            sc.nombre as nombre_sistema,
            c.id_alumno as alumno_reserva,
            c.estado_cita
        ');
        $builder->join('materias m', 'm.id_materia = h.id_materia', 'left');
        $builder->join('sistemas_clase sc', 'sc.id = h.id_sistema', 'left');
        // Join específico para saber si ESTE alumno tiene cita
        $builder->join('citas c', 'c.id_horario = h.id_horario AND c.id_alumno = '.$idAlumnoActual, 'left');
        
        $builder->where('h.id_profesor', $id_profesor);
        
        // FILTRO DE FECHA (Futuros u Hoy despues de la hora)
        $builder->groupStart()
            ->where('h.fecha >', $fechaHoy)
            ->orGroupStart()
                ->where('h.fecha', $fechaHoy)
                ->where('h.hora_inicio >', $horaActual)
            ->groupEnd()
        ->groupEnd();

        $horarios = $builder->get()->getResultArray();

        // 3. Procesar Estados Visuales
        foreach ($horarios as &$h) {
            $esMio = ($h['alumno_reserva'] == $idAlumnoActual);
            $h['es_mio'] = $esMio;

            // Lógica de Semáforo
            if ($esMio) {
                // Si participo yo:
                if ($h['estado_cita'] == 'confirmado') {
                    $h['tipo_visual'] = 'mi_confirmada'; // AZUL CYAN UCOT
                } else {
                    $h['tipo_visual'] = 'mi_pendiente';  // AMARILLO (Pago manual)
                }
            } else {
                // Si NO participo yo:
                if ($h['cupos_disponibles'] > 0 && $h['estado'] == 'Disponible') {
                    $h['tipo_visual'] = 'disponible'; // VERDE SUCCESS
                } else {
                    $h['tipo_visual'] = 'no_disponible'; // GRIS (Lleno o Reservado por otros)
                }
            }
        }

        return $this->response->setJSON($horarios);
    }

    public function reservar_cita_api() {
        $request = \Config\Services::request();
        $json = $request->getJSON();
        
        $idAlumnoReal = $this->getIdEstudianteReal(); 

        $db = \Config\Database::connect();
        $horarioModel = new \App\Models\HorarioModel();
        $citaModel = new \App\Models\CitaModel();

        $id_horario = $json->id_horario;
        $materia = $json->materia;
        $sistema = $json->sistema;

        $db->transStart();

        // 1. Validar si aún hay cupo
        $horario = $horarioModel->where('id_horario', $id_horario)->first();
        
        if (!$horario || $horario['cupos_disponibles'] <= 0 || $horario['estado'] != 'Disponible') {
            return $this->response->setJSON(['status'=>'error', 'msg'=>'Lo sentimos, este horario ya no está disponible.']);
        }

        // 2. Verificar duplicado
        $yaReservado = $citaModel->where('id_alumno', $idAlumnoReal)->where('id_horario', $id_horario)->first();
        if ($yaReservado) {
            return $this->response->setJSON(['status'=>'error', 'msg'=>'Ya tienes una reserva en este horario.']);
        }

        // 3. Decrementar cupo
        $nuevoCupo = $horario['cupos_disponibles'] - 1;
        $datosHorario = ['cupos_disponibles' => $nuevoCupo];
        
        // Si llega a 0, el horario se cierra globalmente
        if ($nuevoCupo == 0) {
            $datosHorario['estado'] = 'Reservado';
        }
        $horarioModel->update($id_horario, $datosHorario);

        // 4. Crear Cita (Nace Pendiente)
        $citaData = [
            'id_alumno'         => $idAlumnoReal,
            'id_profesor'       => $horario['id_profesor'],
            'id_horario'        => $id_horario,
            'fecha_hora_inicio' => $horario['fecha'] . ' ' . $horario['hora_inicio'],
            'duracion_min'      => 45, 
            'materia'           => $materia,
            'sistema'           => $sistema,
            'estado_cita'       => 'pendiente' // Amarillo hasta que pague
        ];
        
        $citaModel->insert($citaData);
        $idCita = $citaModel->getInsertID();

        $db->transComplete();

        if ($db->transStatus() === FALSE) {
            return $this->response->setJSON(['status'=>'error', 'msg'=>'Error en la base de datos.']);
        }

        return $this->response->setJSON(['status'=>'success', 'id_cita' => $idCita]);
    }

    public function pago_paypal_exito($orderID = null) {
        $id_cita = session()->get('id_cita_pendiente');
        if (!$orderID || !$id_cita) return redirect()->to('/alumno/mis_citas')->with('error', 'Sesión expirada.');

        $db = \Config\Database::connect();
        
        // Obtener monto para el registro
        $cita = $db->table('citas')->where('id_cita', $id_cita)->get()->getRowArray();
        $profesor = $db->table('perfil_profesor')->where('id_profesor', $cita['id_profesor'])->get()->getRowArray();
        $monto_real = $profesor['precio_clase'] ?? 0;

        $db->transStart();
        
        $modelPago = new PagoEstaticoModel();
        $modelCita = new CitaModel();

        $id_pago_paypal = 'PAYPAL-' . $orderID;

        // 1. Registrar Pago
        $modelPago->insert([
            'id_cita'     => $id_cita,
            'id_pago'     => $id_pago_paypal,
            'monto'       => $monto_real,
            'fecha_pago'  => date('Y-m-d'),
            'screenshot'  => 'CONFIRMADO_PAYPAL',
            'estado_pago' => 'confirmado' 
        ]);
        
        // 2. Actualizar Cita a CONFIRMADO (Esto la pintará AZUL CYAN)
        $modelCita->update($id_cita, ['estado_cita' => 'confirmado']);
        
        // NOTA: No tocamos el HorarioModel aquí. El cupo ya se bajó al reservar.
        // Si era el último cupo, ya se puso en 'Reservado' en el paso anterior.
        
        $db->transComplete();

        if ($db->transStatus() === false) return redirect()->to('/alumno/mis_citas')->with('error', 'Error en base de datos.');
        
        session()->remove('id_cita_pendiente');
        return redirect()->to(base_url('alumno/factura/' . $id_pago_paypal));
    }
}