<?php

namespace App\Controllers;

use App\Models\CitaModel;
use App\Models\HorarioModel;
use App\Models\ProfesorModel;
use App\Models\LoginModel;
use App\Models\FeedbackModel;
use App\Models\NotificacionModel;


class Profesor extends BaseController
{
    protected $horarioModel;
    protected $profesorModel;

    public function __construct()
    {
        $this->horarioModel = new HorarioModel();
        $this->profesorModel = new ProfesorModel();
    }

    public function index()
    {
        $info['footer'] = view('Template/footer');
        $info['header'] = view('Template/header');
        $info['menu'] = view('Template/menu');
        return view('vistas/inicio', $info);
    }

    public function citas() {
        $citasModel = new CitaModel();
        $db = \Config\Database::connect();

        $perfil = $db->table('perfil_profesor')
                    ->where('id_auth', session()->get('id_auth'))
                    ->get()->getRowArray();

        if (!$perfil) {
            return redirect()->to('/')->with('error', 'No se encontró perfil.');
        }

        $idProfesorReal = $perfil['id_profesor'];

        $resultados = $citasModel
            ->select('citas.*, est.nombre_estudiante, est.apellido_estudiante, auth.correo as estudiante_email')
            ->join('perfiles_estudiantes est', 'est.id_estudiante = citas.id_alumno')
            ->join('auth', 'auth.id_auth = est.id_auth')
            ->where('citas.id_profesor', $idProfesorReal)
            ->orderBy('citas.fecha_hora_inicio', 'DESC')
            ->findAll();

        $data = [
            'titulo' => 'Mi Agenda de Clases',
            'citas'  => $resultados
        ];

        return view('vistas/profesor/citas', array_merge($data));
    }

   public function calendario_profesor()
    {
        // Cargar listas para que el JS las pueda usar en el formulario emergente
        $db = \Config\Database::connect();
        $perfil = $db->table('perfil_profesor')->where('id_auth', session()->get('id_auth'))->get()->getRowArray();
        
        $sistemas = $db->table('profesor_sistemas_vinculo psv')
            ->select('sc.id, sc.nombre')
            ->join('sistemas_clase sc', 'sc.id = psv.id_sistema')
            ->where('psv.id_profesor', $perfil['id_profesor'])
            ->get()->getResultArray();

        $materias = $db->table('materias_profesor mp')
            ->select('m.id_materia, m.nombre_materia')
            ->join('materias m', 'm.id_materia = mp.id_materia')
            ->where('mp.id_profesor', $perfil['id_profesor'])
            ->get()->getResultArray();

        return view('vistas/profesor/calendario_profesor', [
            'sistemas' => $sistemas,
            'materias' => $materias
        ]);
    }
   public function opiniones()
    {
        $feedbackModel = new \App\Models\FeedbackModel();
        
        $id_profesor_sesion = session()->get('id_profesor');
        $datos = $feedbackModel->obtenerComentariosPorProfesor($id_profesor_sesion);

        $data = [
            'comentarios' => $datos
        ];

        return view('vistas/profesor/opiniones', array_merge($data));
    }

    public function config_horarios()
    {
        $db = \Config\Database::connect();
        $perfil = $db->table('perfil_profesor')->where('id_auth', session()->get('id_auth'))->get()->getRowArray();
        
        $id_real = $perfil ? $perfil['id_profesor'] : 0;

        // ACTUALIZACIÓN: Hacemos JOIN para traer nombre de materia y sistema
        $data['horarios'] = $this->horarioModel
            ->select('horarios.*, m.nombre_materia, sc.nombre as nombre_sistema')
            ->join('materias m', 'm.id_materia = horarios.id_materia', 'left')
            ->join('sistemas_clase sc', 'sc.id = horarios.id_sistema', 'left')
            ->where('horarios.id_profesor', $id_real)
            ->orderBy('horarios.fecha', 'ASC')
            ->orderBy('horarios.hora_inicio', 'ASC')
            ->findAll();

        return view('vistas/profesor/HorarioLeer', array_merge($data));
    }
    public function agg_horarios() {
        $db = \Config\Database::connect();
        $perfil = $db->table('perfil_profesor')->where('id_auth', session()->get('id_auth'))->get()->getRowArray();
        
        // Obtener Sistemas
        $sistemas = $db->table('profesor_sistemas_vinculo psv')
            ->select('sc.id, sc.nombre')
            ->join('sistemas_clase sc', 'sc.id = psv.id_sistema')
            ->where('psv.id_profesor', $perfil['id_profesor'])
            ->get()->getResultArray();

        // NUEVO: Obtener Materias
        $materias = $db->table('materias_profesor mp')
            ->select('m.id_materia, m.nombre_materia')
            ->join('materias m', 'm.id_materia = mp.id_materia')
            ->where('mp.id_profesor', $perfil['id_profesor'])
            ->get()->getResultArray();

        return view('vistas/profesor/HorarioAgregar', ['sistemas' => $sistemas, 'materias' => $materias]);
    }

    public function store_horarios()
    {
        try {
            // 1. Recoger datos del formulario
            $fechaSeleccionada = $this->request->getPost('fecha_cita');
            $bloques = $this->request->getPost('bloque_horario');
            $cuposTotales = $this->request->getPost('cupos_totales');
            
            // NUEVOS CAMPOS
            $idSistema = $this->request->getPost('id_sistema'); // Zoom, Meet, Presencial...
            $idMateria = $this->request->getPost('id_materia'); // Matématicas, Física... (Opcional)

            // 2. Validaciones Básicas
            if (empty($cuposTotales) || $cuposTotales < 1) {
                $cuposTotales = 1;
            }

            if (empty($bloques)) {
                return redirect()->back()->with('error', 'Debes seleccionar al menos un horario.');
            }

            if (strtotime($fechaSeleccionada) < strtotime(date('Y-m-d'))) {
                return redirect()->back()->with('error', 'No puedes asignar horarios para fechas pasadas.');
            }

            // 3. VALIDACIÓN DE LÓGICA DE GRUPOS
            // Si es una clase grupal (>1 persona), el profesor DEBE decir dónde es (Sistema).
            if ($cuposTotales > 1 && empty($idSistema)) {
                return redirect()->back()->withInput()->with('error', 'Para clases grupales (más de 1 cupo), es obligatorio definir la Modalidad/Plataforma.');
            }

            // 4. Obtener perfil del profesor
            $db = \Config\Database::connect();
            $perfil = $db->table('perfil_profesor')
                          ->where('id_auth', session()->get('id_auth'))
                          ->get()
                          ->getRowArray();

            if (!$perfil) {
                return redirect()->back()->with('error', 'No se encontró tu perfil de profesor.');
            }

            $idProfesorReal = $perfil['id_profesor'];
            
            $guardados = 0;
            $bloquesExistentes = [];

            // 5. Procesar Bloques
            foreach ($bloques as $bloque) {
                $partes = explode('-', $bloque);
                $inicio = trim($partes[0]); 
                $fin = trim($partes[1]);

                // Verificar si ya existe ese bloque para ese profesor y fecha
                $existe = $this->horarioModel
                    ->where('id_profesor', $idProfesorReal)
                    ->where('fecha', $fechaSeleccionada)
                    ->where('hora_inicio', $inicio . ':00')
                    ->first();

                if (!$existe) {
                    $data = [
                        'id_profesor'       => $idProfesorReal,
                        'fecha'             => $fechaSeleccionada,
                        'hora_inicio'       => $inicio,
                        'hora_fin'          => $fin,
                        'estado'            => 'Disponible',
                        'cupos_totales'     => $cuposTotales,
                        'cupos_disponibles' => $cuposTotales,
                        // Guardamos los nuevos datos (si vienen vacíos se guarda NULL)
                        'id_sistema'        => !empty($idSistema) ? $idSistema : null,
                        'id_materia'        => !empty($idMateria) ? $idMateria : null
                    ];
                    
                    $this->horarioModel->insert($data);
                    $guardados++;
                } else {
                    $bloquesExistentes[] = $bloque;
                }
            }

            // 6. Retorno de mensajes
            if ($guardados === 0) {
                return redirect()->back()
                    ->with('error', 'No se realizaron cambios. Todos los horarios seleccionados ya existían.');
            }

            if (empty($bloquesExistentes)) {
                return redirect()->to(base_url('profesor/HorarioLeer'))
                    ->with('mensaje', '¡Éxito! Se han agregado ' . $guardados . ' nuevos bloques correctamente.');
            }

            $mensaje = 'Se guardaron ' . $guardados . ' horarios nuevos. ';
            $mensaje .= 'Nota: Los siguientes bloques ya existían: ' . implode(', ', $bloquesExistentes);

            return redirect()->to(base_url('profesor/HorarioLeer'))
                ->with('mensaje', $mensaje);

        } catch (\Exception $e) {
            log_message('error', $e->getMessage());
            return "Error de sistema: " . $e->getMessage();
        }
    }

    public function dlt_horario($id_horario = null)
    {
        if ($id_horario !== null) {
            $citaModel = new \App\Models\CitaModel();
            
            // VERIFICAR si existen citas asociadas a este horario
            $citasAsociadas = $citaModel->where('id_horario', $id_horario)->countAllResults();
            
            if ($citasAsociadas > 0) {
                // NO se puede eliminar porque ya tiene reservas
                return redirect()->to(base_url('profesor/HorarioLeer'))
                    ->with('error', 'No puedes eliminar este horario porque ya tiene ' . $citasAsociadas . ' cita(s) agendada(s).');
            }
            
            // Si no tiene citas, proceder con la eliminación
            $this->horarioModel->delete($id_horario);
            return redirect()->to(base_url('profesor/HorarioLeer'))
                ->with('msg', 'Horario Eliminado Correctamente');
        }
        return redirect()->to(base_url('profesor/HorarioLeer'));
    }

    public function edit_horario($id_horario = null)
    {
        $horario = $this->horarioModel->find($id_horario);
        
        // Calcular ocupados para enviarlo a la vista
        $cuposOcupados = ($horario['cupos_totales'] ?? 0) - ($horario['cupos_disponibles'] ?? 0);

        $db = \Config\Database::connect();
        $perfil = $db->table('perfil_profesor')->where('id_auth', session()->get('id_auth'))->get()->getRowArray();
        
        // Cargar auxiliares
        $sistemas = $db->table('profesor_sistemas_vinculo psv')
            ->select('sc.id, sc.nombre')
            ->join('sistemas_clase sc', 'sc.id = psv.id_sistema')
            ->where('psv.id_profesor', $perfil['id_profesor'])
            ->get()->getResultArray();

        $materias = $db->table('materias_profesor mp')
            ->select('m.id_materia, m.nombre_materia')
            ->join('materias m', 'm.id_materia = mp.id_materia')
            ->where('mp.id_profesor', $perfil['id_profesor'])
            ->get()->getResultArray();

        return view('vistas/profesor/HorarioEditar', [
            'horario' => $horario,
            'sistemas' => $sistemas,
            'materias' => $materias,
            'cuposOcupados' => $cuposOcupados
        ]);
    }

    public function update_horario($id_horario = null){
        $horarioActual = $this->horarioModel->find($id_horario);
        if (!$horarioActual) return redirect()->to(base_url('profesor/HorarioLeer'));

        // 1. Calcular ocupación real
        $cuposOcupados = $horarioActual['cupos_totales'] - $horarioActual['cupos_disponibles'];
        $hayReservas = ($cuposOcupados > 0);

        // 2. Recoger Cupos Totales Nuevos
        $nuevosCuposTotales = $this->request->getPost('cupos_totales');
        if (empty($nuevosCuposTotales) || $nuevosCuposTotales < 1) $nuevosCuposTotales = $horarioActual['cupos_totales'];

        if ($nuevosCuposTotales < $cuposOcupados) {
            return redirect()->back()->with('error', 'No puedes reducir cupos por debajo de los ya reservados.');
        }

        // --- LÓGICA DE RESTRICCIÓN (CANDADO) ---
        if ($hayReservas) {
            // SI HAY RESERVAS: Mantenemos los datos originales
            $fecha = $horarioActual['fecha'];
            $hora_inicio = $horarioActual['hora_inicio'];
            $hora_fin = $horarioActual['hora_fin'];
            $id_sistema = $horarioActual['id_sistema'];
            $id_materia = $horarioActual['id_materia'];
        } else {
            // SI ESTÁ VACÍO: Permitimos editar
            $fecha = $this->request->getPost('fecha');
            
            // Validar fecha solo si cambia
            if ($fecha != $horarioActual['fecha'] && strtotime($fecha) < strtotime(date('Y-m-d'))) {
                return redirect()->back()->with('error', 'No puedes asignar fechas pasadas.');
            }

            // Determinar horas
            $bloque_horario = $this->request->getPost('bloque_horario');
            if ($bloque_horario) {
                $partes = explode('-', $bloque_horario);
                $hora_inicio = trim($partes[0]) . ':00';
                $hora_fin = trim($partes[1]) . ':00';
            } else {
                // Si no mandó bloque, usamos los hidden o los originales
                $hora_inicio = $this->request->getPost('hora_inicio');
                $hora_fin = $this->request->getPost('hora_fin');
                
                // Si están vacíos los hidden, mantenemos los originales
                if(empty($hora_inicio)) $hora_inicio = $horarioActual['hora_inicio'];
                if(empty($hora_fin)) $hora_fin = $horarioActual['hora_fin'];
            }

            // VALIDACIÓN DE DUPLICADOS (CORREGIDA)
            // Verificamos si ya existe OTRO horario con la misma fecha y hora para este profesor
            $db = \Config\Database::connect();
            $perfil = $db->table('perfil_profesor')->where('id_auth', session()->get('id_auth'))->get()->getRowArray();
            
            $duplicado = $this->horarioModel
                ->where('id_profesor', $perfil['id_profesor'])
                ->where('fecha', $fecha)
                ->where('hora_inicio', $hora_inicio)
                ->where('id_horario !=', $id_horario)
                ->first();

            if ($duplicado) {
                return redirect()->back()->with('error', 'Ya tienes otro horario asignado en ese bloque.');
            }

            $id_sistema = $this->request->getPost('id_sistema');
            $id_materia = $this->request->getPost('id_materia');
            
            if ($nuevosCuposTotales > 1 && empty($id_sistema)) {
                return redirect()->back()->with('error', 'Para clases grupales debes definir la Plataforma.');
            }
        }

        // 3. Calcular nuevos disponibles
        $nuevosCuposDisponibles = $nuevosCuposTotales - $cuposOcupados;

        $data = [
            'fecha'             => $fecha,
            'hora_inicio'       => $hora_inicio,
            'hora_fin'          => $hora_fin,
            'estado'            => ($nuevosCuposDisponibles > 0) ? 'Disponible' : 'Reservado',
            'cupos_totales'     => $nuevosCuposTotales,
            'cupos_disponibles' => $nuevosCuposDisponibles,
            'id_sistema'        => !empty($id_sistema) ? $id_sistema : null,
            'id_materia'        => !empty($id_materia) ? $id_materia : null
        ];

        $this->horarioModel->update($id_horario, $data);
        
        return redirect()->to(base_url('profesor/HorarioLeer'))->with('mensaje', 'Horario actualizado correctamente.');
    }

    public function dashboard() {

    // 1. CARGAR NOTIFICACIONES
    $notifModel = new \App\Models\NotificacionModel();
    $id_auth = session()->get('id_auth'); 

    $data['notificaciones'] = $notifModel->misNotificaciones($id_auth);
    $data['no_leidas']      = $notifModel->contarNoLeidas($id_auth);
        
    // 2. OBTENER PERFIL PROFESOR
    $db = \Config\Database::connect();
    $perfil = $db->table('perfil_profesor')
                 ->where('id_auth', $id_auth)
                 ->get()->getRowArray();

    if (!$perfil) return redirect()->to('/')->with('error', 'Perfil no encontrado');

    $id_p = $perfil['id_profesor'];
    $citasModel    = new \App\Models\CitaModel();
    $feedbackModel = new \App\Models\FeedbackModel();
    
    // --- MÉTRICAS ---  Fernando, quien te enseño esa palabra?
    
    // Citas de HOY 
    $data['total_hoy_confirmadas'] = $citasModel
        ->where('id_profesor', $id_p)
        ->where('DATE(fecha_hora_inicio)', date('Y-m-d'))
        ->where('estado_cita', 'confirmado') 
        ->countAllResults();
    
    // Pendientes
    $data['total_pendientes'] = $citasModel
        ->where('id_profesor', $id_p)
        ->where('estado_cita', 'pendiente')
        ->countAllResults();
    
    // Completadas (Todas las confirmadas del mes actual)
    $data['total_completadas_mes'] = $citasModel
        ->where('id_profesor', $id_p)
        ->where('estado_cita', 'confirmado')
        ->where('MONTH(fecha_hora_inicio)', date('m'))
        ->where('YEAR(fecha_hora_inicio)', date('Y'))
        ->countAllResults();
    
    // Próximas Clases
    $data['proximas_citas'] = $citasModel
        ->select('citas.*, est.nombre_estudiante, est.apellido_estudiante')
        ->join('perfiles_estudiantes est', 'est.id_estudiante = citas.id_alumno')
        ->where('citas.id_profesor', $id_p)
        ->where('citas.estado_cita', 'confirmado')
        ->where('citas.fecha_hora_inicio >=', date('Y-m-d H:i:s'))
        ->orderBy('citas.fecha_hora_inicio', 'ASC')
        ->limit(5)
        ->findAll();
    
    // Feedback
    $data['feedback_reciente'] = $feedbackModel
        ->select('feedback.*, est.nombre_estudiante, est.apellido_estudiante')
        ->join('perfiles_estudiantes est', 'est.id_estudiante = feedback.id_estudiante')
        ->where('feedback.id_profesor', $id_p)
        ->orderBy('feedback.fecha_evaluacion', 'DESC')
        ->limit(3)
        ->findAll();
    
    // 3. CÁLCULO DE INGRESOS TOTALES (SIN FILTRO DE FECHA)
    $tasa_bcv = 390.29; 
    try {
        $client = \Config\Services::curlrequest();
        $response = $client->get('https://open.er-api.com/v6/latest/USD', ['timeout' => 3]);
        $resultado = json_decode($response->getBody());
        if (isset($resultado->rates->VES)) $tasa_bcv = $resultado->rates->VES;
    } catch (\Exception $e) { }
    
    // Sumamos todas las citas confirmadas del profesor, sin importar el día
    $todas_las_citas = $citasModel
        ->select('citas.*, prof.precio_clase')
        ->join('perfil_profesor prof', 'prof.id_profesor = citas.id_profesor')
        ->where('citas.id_profesor', $id_p)
        ->where('citas.estado_cita', 'confirmado')
        ->findAll();
    
    $total_usd = 0;
    foreach ($todas_las_citas as $cita) {
        $precio = $cita['precio_clase'] ?? 0; 
        $total_usd += (float)$precio;
    }
    
    $data['ingresos_hoy_usd'] = $total_usd;
    $data['ingresos_hoy_bs']  = $total_usd * $tasa_bcv;
    $data['tasa_bcv']         = $tasa_bcv;
    $data['profesor_actual']  = $perfil;

    return view('vistas/profesor/dashboard', $data);
}




    
    public function finalizar_cita($id) {
        $model = new \App\Models\CitaModel();
        $model->update($id, ['estado_cita' => 'finalizada']);
        return redirect()->back()->with('msg', '¡Clase marcada como finalizada!');
    }

    // --- API PARA EL CALENDARIO INTERACTIVO (AJAX) ---
    public function obtener_horarios_api() {
        $db = \Config\Database::connect();
        $perfil = $db->table('perfil_profesor')->where('id_auth', session()->get('id_auth'))->get()->getRowArray();
        
        if (!$perfil) return $this->response->setJSON([]);

        $builder = $db->table('horarios h');
        $builder->select('
            h.id_horario, 
            h.fecha, 
            h.hora_inicio, 
            h.hora_fin, 
            h.estado,
            h.cupos_totales,
            h.cupos_disponibles,
            m.nombre_materia,
            s.nombre as nombre_sistema,
            
            -- Concatenar nombres de alumnos
            GROUP_CONCAT(CONCAT(pe.nombre_estudiante, " ", pe.apellido_estudiante) SEPARATOR ", ") as alumnos_inscritos,
            
            -- Contar cuántos están pendientes de pago en este bloque
            SUM(CASE WHEN c.estado_cita = "pendiente" THEN 1 ELSE 0 END) as conteo_pendientes
        ');
        
        $builder->join('materias m', 'm.id_materia = h.id_materia', 'left');
        $builder->join('sistemas_clase s', 's.id = h.id_sistema', 'left');
        
        // Unir con citas activas (no canceladas)
        $builder->join('citas c', 'c.id_horario = h.id_horario AND c.estado_cita != "cancelado"', 'left');
        $builder->join('perfiles_estudiantes pe', 'pe.id_estudiante = c.id_alumno', 'left');
        
        $builder->where('h.id_profesor', $perfil['id_profesor']);
        $builder->groupBy('h.id_horario'); 
        
        $resultados = $builder->get()->getResultArray();

        // Procesar lógica de estado visual para el JS
        foreach ($resultados as &$fila) {
            $fila['ocupados'] = (int)$fila['cupos_totales'] - (int)$fila['cupos_disponibles'];
            
            // Determinar etiqueta visual
            if ((int)$fila['cupos_disponibles'] == 0) {
                // Si hay pendientes, es Warning. Si no, es Cyan.
                if ((int)$fila['conteo_pendientes'] > 0) {
                    $fila['tipo_visual'] = 'lleno_pendiente'; // Amarillo
                } else {
                    $fila['tipo_visual'] = 'lleno_confirmado'; // Cyan
                }
            } elseif ((int)$fila['ocupados'] > 0) {
                $fila['tipo_visual'] = 'parcial'; // Naranja
            } else {
                $fila['tipo_visual'] = 'libre'; // Verde
            }
        }
        
        return $this->response->setJSON($resultados);
    }
    public function guardar_horarios_api()
    {
        $request = \Config\Services::request();
        $json = $request->getJSON();
        
        $nuevos = $json->nuevos ?? [];
        $eliminar = $json->eliminar ?? [];

        $db = \Config\Database::connect();
        $perfil = $db->table('perfil_profesor')->where('id_auth', session()->get('id_auth'))->get()->getRowArray();

        if (!$perfil) return $this->response->setJSON(['status' => 'error', 'msg' => 'Perfil no encontrado']);

        $id_profesor = $perfil['id_profesor'];
        $db->transStart();

        if (!empty($eliminar)) {
            $this->horarioModel
                ->whereIn('id_horario', $eliminar)
                ->where('id_profesor', $id_profesor)
                ->delete();
        }

        $insertados = 0;
        if (!empty($nuevos)) {
            $dataInsertar = [];
            foreach ($nuevos as $h) {
                // Verificar duplicados
                $existe = $this->horarioModel
                    ->where('id_profesor', $id_profesor)
                    ->where('fecha', $h->fecha)
                    ->where('hora_inicio', $h->inicio . ':00')
                    ->countAllResults();

                if ($existe == 0) {
                    $cupos = isset($h->cupos_totales) ? (int)$h->cupos_totales : 1;
                    $dataInsertar[] = [
                        'id_profesor'       => $id_profesor,
                        'fecha'             => $h->fecha,
                        'hora_inicio'       => $h->inicio,
                        'hora_fin'          => $h->fin,
                        'estado'            => 'Disponible',
                        'cupos_totales'     => $cupos,
                        'cupos_disponibles' => $cupos,
                        // Guardar datos extra
                        'id_materia'        => !empty($h->id_materia) ? $h->id_materia : null,
                        'id_sistema'        => !empty($h->id_sistema) ? $h->id_sistema : null
                    ];
                }
            }
            if (!empty($dataInsertar)) {
                $this->horarioModel->insertBatch($dataInsertar);
                $insertados = count($dataInsertar);
            }
        }

        $db->transComplete();

        return $this->response->setJSON([
            'status' => 'success', 
            'msg' => "Se guardaron $insertados horarios y se eliminaron " . count($eliminar) . "."
        ]);
    }

    public function eliminar_horario_api()
    {
        $request = \Config\Services::request();
        $json = $request->getJSON();
        $id_horario = $json->id_horario;

        $db = \Config\Database::connect();
        $perfil = $db->table('perfil_profesor')->where('id_auth', session()->get('id_auth'))->get()->getRowArray();
        
        if ($this->horarioModel->where('id_horario', $id_horario)->where('id_profesor', $perfil['id_profesor'])->delete()) {
            return $this->response->setJSON(['status' => 'success', 'msg' => 'Horario eliminado.']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'msg' => 'No se pudo eliminar.']);
        }
    }

// Procesa la solicitud de retiro enviada desde el modal vía AJAX

public function enviar_solicitud_retiro()
{
    // Verificar sesión activa (solo profesores)
    $session = session();
    if (!$session->get('isLoggedIn') || $session->get('rol') !== 'Profesor') {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'No tienes permisos para realizar esta acción.'
        ]);
    }

    // Validar campos requeridos
    $rules = [
        'nombre'       => 'required',
        'apellido'     => 'required',
        'tipo_cedula'  => 'required',
        'cedula'       => 'required|numeric',
        'telefono'     => 'required|numeric|min_length[11]',
        'banco'        => 'required'
    ];

    if (!$this->validate($rules)) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Por favor completa todos los campos obligatorios.'
        ]);
    }

    // Recoger datos del POST
    $data = [
        'nombre'       => $this->request->getPost('nombre'),
        'apellido'     => $this->request->getPost('apellido'),
        'tipo_cedula'  => $this->request->getPost('tipo_cedula'),
        'cedula'       => $this->request->getPost('cedula'),
        'telefono'     => $this->request->getPost('telefono'),
        'banco'        => $this->request->getPost('banco'),
        'cuenta'       => $this->request->getPost('cuenta') ?: 'No especificada',
        'comentarios'  => $this->request->getPost('comentarios') ?: 'Sin comentarios',
        'id_profesor'  => $this->request->getPost('id_profesor'),
        'correo'       => $this->request->getPost('correo_profesor')
    ];

    // Enviar el correo con remitente fijo uc0t2025@gmail.com
    $email = \Config\Services::email();
    
    // FORZAR remitente desde la configuración
    $email->setFrom('ucot2025@gmail.com', 'UCOT - Sistema de Retiros');
    $email->setTo('ucot2025@gmail.com');
    $email->setSubject('🧾 NUEVA SOLICITUD DE RETIRO DE FONDOS - UCOT');
    
    // Construir mensaje HTML
    $mensaje = $this->construirEmailRetiro($data);
    $email->setMessage($mensaje);

    if ($email->send()) {
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Solicitud enviada correctamente. Te contactaremos pronto.'
        ]);
    } else {
        // Obtener el error real para depuración
        $error = $email->printDebugger(['headers']);
        log_message('error', 'Error al enviar correo de retiro: ' . $error);
        
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Error al enviar el correo. Por favor intenta de nuevo o contacta a soporte.'
        ]);
    }
}
private function construirEmailRetiro($data)
{
    $data['fecha'] = date('d/m/Y H:i:s');
    
    return view('/vistas/correos/retiro_fondos', $data);
}

    
}