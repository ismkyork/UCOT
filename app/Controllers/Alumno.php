<?php

namespace App\Controllers;

use App\Models\CitaModel;
use App\Models\HorarioModel;
use App\Models\PagoEstaticoModel;

class Alumno extends BaseController
{
    public function index() {   
        $info['footer'] = view('Template/footer');
        $info['header'] = view('Template/header');
        $info['menu'] = view('Template/menu');
        return view('vistas/inicio', $info);
    }

    public function calendario() {
        $info['footer'] = view('Template/footer');
        $info['header'] = view('Template/header');
        $info['menu'] = view('Template/menu');
        return view('vistas/alumno/calendario', $info);
    }

    /**
     * DASHBOARD DINÁMICO DEL ALUMNO
     */
   public function inicio_alumno() {
    // Obtenemos los IDs de la sesión
    $idAuth = session()->get('id_auth');
    $idAlumno = session()->get('id_alumno'); 

    $db = \Config\Database::connect();
    $builder = $db->table('citas c');
    
    $builder->select('c.*, p.nombre_profesor, p.apellido_profesor, a.email as email_profesor');
    
    $builder->join('perfil_profesor p', 'p.id_auth = c.id_profesor', 'left');
    $builder->join('auth a', 'a.id_auth = c.id_profesor', 'left');
    
    $builder->groupStart()
                ->where('c.id_alumno', $idAlumno)
                ->orWhere('c.id_alumno', $idAuth)
            ->groupEnd();

    $builder->orderBy('c.id_cita', 'DESC'); 
    $builder->limit(1);
    
    $data['proxima_cita'] = $builder->get()->getRowArray();

    $info['footer'] = view('Template/footer');
    $info['header'] = view('Template/header');
    $info['menu'] = view('Template/menu');
    
    return view('vistas/alumno/inicio_alumno', array_merge($info, $data));
}
    public function feedback() {
        $info['footer'] = view('Template/footer');
        $info['header'] = view('Template/header');
        $info['menu'] = view('Template/menu');
        return view('vistas/alumno/feedback', $info);
    }

    /**
     * FACTURA DIGITAL CORREGIDA
     */
    public function factura($id_pago = null) {
        if (!$id_pago) {
            return redirect()->to('/alumno/mis_citas')->with('error', 'No se especificó un comprobante.');
        }

        $db = \Config\Database::connect();
        $builder = $db->table('pago_estatico p');
        
        // CORRECCIÓN: Agregados prof.apellido_profesor y est.apellido
        $builder->select('p.*, c.fecha_hora_inicio, c.materia, 
                          prof.nombre_profesor, prof.apellido_profesor, 
                          est.name as nombre_alumno, est.apellido as apellido_alumno');
        
        $builder->join('citas c', 'c.id_cita = p.id_cita', 'left');
        $builder->join('perfil_profesor prof', 'prof.id_auth = c.id_profesor', 'left'); 
        $builder->join('perfiles_estudiantes est', 'est.id_estudiante = c.id_alumno', 'left');   
        
        $builder->where('p.id_pago', $id_pago);
        $data['pago'] = $builder->get()->getRowArray();

        if (!$data['pago']) {
            return redirect()->to('/alumno/mis_citas')->with('error', 'La factura no existe.');
        }

        // Tasa BCV Dinámica
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

    /**
     * MIS CITAS CORREGIDA
     */
    public function mis_citas() {
        $idAlumno = session()->get('id_alumno');
        $modelHorario = new HorarioModel();
        $db = \Config\Database::connect();

        $data['horarios'] = $modelHorario->orderBy("FIELD(week_day, 'LUNES','MARTES','MIERCOLES','JUEVES','VIERNES','SABADO','DOMINGO')", '', false)
                                         ->orderBy('hora_inicio', 'ASC')
                                         ->findAll();

        $builder = $db->table('citas c');
        
        // CORRECCIÓN: Nombres de columnas según tu BDD (apellido y apellido_profesor)
        $builder->select('c.*, p.id_pago, p.estado_pago, 
                          prof.nombre_profesor, prof.apellido_profesor, 
                          est.name as nombre_estudiante, est.apellido as apellido_estudiante');
        
        $builder->join('pago_estatico p', 'p.id_cita = c.id_cita', 'left'); 
        $builder->join('perfil_profesor prof', 'prof.id_auth = c.id_profesor', 'left');
        $builder->join('perfiles_estudiantes est', 'est.id_estudiante = c.id_alumno', 'left');

        $builder->where('c.id_alumno', $idAlumno);
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
        $idAlumno = session()->get('id_alumno');
        
        $horarios = $this->request->getPost('horarios');
        $materias = $this->request->getPost('materias');
        $fechas = $this->request->getPost('fecha');

        if (!$horarios) {
            return redirect()->back()->with('error', 'No seleccionaste horarios.');
        }

        $db = \Config\Database::connect();
        
        foreach ($horarios as $idHorario) {
            $horario = $horarioModel->find($idHorario);
            if (!$horario) continue;

            $inicio = new \DateTime($horario['hora_inicio']);
            $fin = new \DateTime($horario['hora_fin']);
            $totalMinutos = ($inicio->diff($fin)->h * 60) + $inicio->diff($fin)->i;

            $fechaHoraInicio = $fechas[$idHorario] . ' ' . $horario['hora_inicio'];

            $data = [
                'id_alumno'         => $idAlumno,
                'id_profesor'       => $horario['id_profesor'],
                'id_horario'        => $idHorario,
                'fecha_hora_inicio' => $fechaHoraInicio,
                'duracion_min'      => $totalMinutos,
                'materia'           => $materias[$idHorario] ?? 'Clase Particular',
                'estado_cita'       => 'pendiente'
            ];

            if (!$citaModel->insert($data)) {
                dd($citaModel->errors(), $db->error(), "ID Alumno enviado: " . $idAlumno);
            }
            
            $ultimaCitaId = $db->insertID();
        }

        return redirect()->to(base_url('alumno/pago_estatico/' . $ultimaCitaId));
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
        $modelCita = new CitaModel(); // Necesario para actualizar el estado
        $id_cita = $this->request->getPost('id_cita');
        $id_pago = $this->request->getPost('id_pago');
        $montoRaw = $this->request->getPost('monto');
        $montoLimpio = str_replace(['.', ','], ['', '.'], $montoRaw);

        if (empty($id_cita)) {
            return redirect()->back()->with('error', 'No se recibió el ID de la cita.');
        }

        $reglas = [
            'id_pago'     => 'required|is_unique[pago_estatico.id_pago]',
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
            'estado_pago' => 'confirmado'
        ];

        $db = \Config\Database::connect();
        $db->transStart();

        $modelPago->insert($data);
        // CORRECCIÓN: Actualizar estado de la cita a confirmada tras el pago
        $modelCita->update($id_cita, ['estado_cita' => 'confirmado']);

        $db->transComplete();

        if ($db->transStatus() === true) {
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