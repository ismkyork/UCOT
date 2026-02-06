<?php

namespace App\Controllers;

use App\Models\CitaModel;
use App\Models\HorarioModel;
use App\Models\PagoEstaticoModel;
use App\Models\FeedbackModel;

class Alumno extends BaseController
{
    // Esta función busca el ID real del estudiante basado en quién está logueado
    private function getIdEstudianteReal() {
        $idAuth = session()->get('id_auth');
        $db = \Config\Database::connect();
        
        // Buscamos en la tabla de perfiles quién tiene este id_auth
        $perfil = $db->table('perfiles_estudiantes')
                     ->select('id_estudiante') 
                     ->where('id_auth', $idAuth)
                     ->get()->getRowArray();

        // Si no existe perfil, retornamos null o manejamos error
        return $perfil ? $perfil['id_estudiante'] : null;
    }

    public function index() {   
        $info['footer'] = view('Template/footer');
        $info['header'] = view('Template/header');
        $info['menu'] = view('Template/menu');
        return view('vistas/inicio', $info);
    }

    public function calendario_alumno() {
        $info = [];
        return view('vistas/alumno/calendario_alumno');
    }

    /**
     * DASHBOARD DEL ALUMNO
     */
    public function inicio_alumno() {
        
        $idAlumnoReal = $this->getIdEstudianteReal();
        
        if (!$idAlumnoReal) {
            return redirect()->to('/')->with('error', 'Perfil de estudiante no encontrado.');
        }

        $db = \Config\Database::connect();

        $builder = $db->table('citas c');
        $builder->select('c.*, prof.nombre_profesor, prof.apellido_profesor');
        $builder->join('perfil_profesor prof', 'prof.id_auth = c.id_profesor', 'left');
        //Buscamos por el ID real de la tabla citas
        $builder->where('c.id_alumno', $idAlumnoReal);
        $builder->where('c.fecha_hora_inicio >=', date('Y-m-d H:i:s')); 
        $builder->orderBy('c.fecha_hora_inicio', 'ASC');
        $builder->limit(1);

        $data['proxima_cita'] = $builder->get()->getRowArray();

        $info['footer'] = view('Template/footer');
        $info['header'] = view('Template/header');
        $info['menu'] = view('Template/menu');

        return view('vistas/alumno/inicio_alumno', array_merge($info, $data));
    }

    public function feedback()
    {
        $feedbackModel = new FeedbackModel();
        $comentarios = $feedbackModel->orderBy('fecha_evaluacion', 'DESC')->findAll();
        $data = [
            'historial' => $comentarios
        ];

        $info = [];
        $info['header'] = view('Template/header');
        $info['menu']   = view('Template/menu');
        $info['footer'] = view('Template/footer');

        return view('vistas/alumno/feedback', array_merge($info, $data));
    }
    
    public function guardar()
    {
        $feedbackModel = new FeedbackModel();
        $reglas = [
            'puntuacion' => 'required|numeric',
            'comentario' => 'required|min_length[5]'
        ];

        if (!$this->validate($reglas)) {
            return redirect()->back()->withInput()->with('error', 'Por favor selecciona una calificación y escribe un comentario.');
        }

        $datos = [
            'puntuacion' => $this->request->getPost('puntuacion'),
            'comentario' => $this->request->getPost('comentario')
        ];
        $feedbackModel->save($datos);

        return redirect()->to('/alumno/feedback')->with('msg', '¡Gracias! Tu opinión ha sido enviada correctamente.');
    }

    public function factura($id_pago = null) {
    if (!$id_pago) {
        return redirect()->to('/alumno/mis_citas')->with('error', 'No se especificó un comprobante.');
    }

    $db = \Config\Database::connect();
    $builder = $db->table('pagos p');
    
    // 1. Seleccionamos los campos
    $builder->select('p.*, c.fecha_hora_inicio, c.materia, prof.nombre_profesor, est.nombre_estudiante as nombre_alumno');
    
    // 2. Unimos con citas
    $builder->join('citas c', 'c.id_cita = p.id_cita');
    
    // 3. CORRECCIÓN: Unir por el ID real del profesor
    // Antes tenías prof.id_auth, pero debe ser prof.id_profesor
    $builder->join('perfil_profesor prof', 'prof.id_profesor = c.id_profesor'); 
    
    // 4. Unimos con el perfil del estudiante (ID Real)
    $builder->join('perfiles_estudiantes est', 'est.id_estudiante = c.id_alumno');   
    
    $builder->where('p.id_pago', $id_pago);
    
    $data['pago'] = $builder->get()->getRowArray();

    if (!$data['pago']) {
        // Si entra aquí, es porque los Joins fallaron o el ID no existe
        return redirect()->to('/alumno/mis_citas')->with('error', 'La factura no existe o los datos de perfil están incompletos.');
    }

    // --- Lógica de Tasa BCV (Tu código está bien aquí) ---
    $tasa_bcv = 45.00;
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
    
    $data['tasa_bcv'] = $tasa_bcv;

    $info['footer'] = view('Template/footer');
    $info['header'] = view('Template/header');
    $info['menu']   = view('Template/menu');

    return view('vistas/alumno/factura', array_merge($info, $data));
}

    public function mis_citas() {
        // Obtenemos ID Real
        $idAlumnoReal = $this->getIdEstudianteReal();
        
        if (!$idAlumnoReal) {
             // Manejo de error si el usuario no tiene perfil creado
             return redirect()->to('/')->with('error', 'Error de perfil');
        }

        $modelHorario = new HorarioModel();
        $db = \Config\Database::connect();

        $data['horarios'] = $modelHorario->orderBy("FIELD(fecha, 'LUNES','MARTES','MIERCOLES','JUEVES','VIERNES','SABADO','DOMINGO')", '', false)
                                         ->orderBy('hora_inicio', 'ASC')
                                         ->findAll();

        $builder = $db->table('citas c');
        $builder->select('c.*, p.id_pago, p.estado_pago, prof.nombre_profesor');
        $builder->join('pagos p', 'p.id_cita = c.id_cita', 'left'); 
        $builder->join('perfil_profesor prof', 'prof.id_auth = c.id_profesor', 'left');
        //Filtramos por el ID real
        $builder->where('c.id_alumno', $idAlumnoReal);
        $builder->orderBy('c.fecha_hora_inicio', 'DESC');
        $data['citas_reservadas'] = $builder->get()->getResultArray();

        $info['footer'] = view('Template/footer');
        $info['header'] = view('Template/header');
        $info['menu']   = view('Template/menu');
        
        return view('vistas/alumno/mis_citas', array_merge($info, $data));
    }

    public function store_citas() {
    $citaModel = new CitaModel();
    $horarioModel = new HorarioModel();
    
    // 1. Obtenemos el ID real del estudiante
    $idAlumnoReal = $this->getIdEstudianteReal();

    if (!$idAlumnoReal) {
        return redirect()->back()->with('error', 'Error: No se encontró tu perfil de estudiante.');
    }

    // 2. Captura de datos con limpieza (usamos getVar para mayor compatibilidad)
    $idHorario = $this->request->getVar('id_horario'); 
    $materia   = $this->request->getVar('materia_nombre');
    $fecha     = $this->request->getVar('fecha_seleccionada');

    // DEBUG OPCIONAL: Descomenta la siguiente línea si quieres ver qué llega antes de insertar
    // dd($this->request->getPost()); 

    if (!empty($idHorario)) {
        // 3. Buscamos el bloque horario
        $horarioData = $horarioModel->find($idHorario);
        
        if (!$horarioData) {
            return redirect()->back()->with('error', 'El horario seleccionado ya no existe.');
        }

        // 4. Cálculo de duración
        $inicio = new \DateTime($horarioData['hora_inicio']);
        $fin = new \DateTime($horarioData['hora_fin']);
        $duracion = $inicio->diff($fin);
        $totalMinutos = ($duracion->days * 24 * 60) + ($duracion->h * 60) + $duracion->i;

        // 5. Mapeo de datos para la tabla 'citas'
        $dataInsert = [
            'id_alumno'         => $idAlumnoReal,
            'id_profesor'       => $horarioData['id_profesor'],
            'id_horario'        => (int)$idHorario, // Forzamos entero para evitar el NULL
            'fecha_hora_inicio' => $fecha . ' ' . $horarioData['hora_inicio'],
            'duracion_min'      => $totalMinutos,
            'materia'           => $materia,
            'estado_cita'       => 'pendiente'
        ];

        // 6. Inserción
        if ($citaModel->insert($dataInsert)) {
            $ultimaCitaId = $citaModel->getInsertID();
            return redirect()->to(base_url('alumno/pago_estatico/' . $ultimaCitaId));
        } else {
            return redirect()->back()->with('error', 'No se pudo guardar la cita en la base de datos.');
        }
    }

    return redirect()->to('alumno/mis_citas')->with('error', 'No se recibió la información del horario.');
}

    public function pago_estatico($id_cita = null) {
        if (!$id_cita) {
            return redirect()->to('alumno/mis_citas')->with('error', 'No se especificó una cita.');
        }

        session()->set('id_cita_pendiente', $id_cita);

        $info['footer'] = view('Template/footer');
        $info['header'] = view('Template/header');
        $info['menu']   = view('Template/menu');
        $info['id_cita'] = $id_cita;
        $info['precio_paypal'] = "10.00"; 

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

        if ($modelPago->insert($data)) {
            $img->move(ROOTPATH . 'public/uploads/comprobantes', $nombreImg);
            session()->remove('id_cita_pendiente'); 
            return redirect()->to(base_url('alumno/factura/' . $id_pago))->with('success', 'Pago enviado.');
        } else {
            return redirect()->back()->with('error', 'Error al guardar el pago.');
        }
    }
    
    public function pago_paypal_exito($orderID = null) {
        $id_cita = session()->get('id_cita_pendiente');

        if (!$orderID || !$id_cita) {
            return redirect()->to('/alumno/mis_citas')->with('error', 'Sesión expirada.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $modelPago = new PagoEstaticoModel();
        $modelCita = new CitaModel();
        $id_pago_paypal = 'PAYPAL-' . $orderID;

        $dataPago = [
            'id_cita'     => $id_cita,
            'id_pago'     => $id_pago_paypal,
            'monto'       => 10.00,
            'fecha_pago'  => date('Y-m-d'),
            'screenshot'  => 'CONFIRMADO_PAYPAL',
            'estado_pago' => 'confirmado' 
        ];
        
        $modelPago->insert($dataPago);
        $modelCita->update($id_cita, ['estado_cita' => 'confirmado']);
        
        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->to('/alumno/mis_citas')->with('error', 'Error en base de datos.');
        }
        
        session()->remove('id_cita_pendiente');
        return redirect()->to(base_url('alumno/factura/' . $id_pago_paypal));
    }
}