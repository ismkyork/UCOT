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
        $info = [];
        $info['footer'] = view('Template/footer');
        $info['header'] = view('Template/header');
        $info['menu'] = view('Template/menu');
        return view('vistas/alumno/calendario', $info);
    }

    public function inicio_alumno() {
        $info = [];
        $info['footer'] = view('Template/footer');
        $info['header'] = view('Template/header');
        $info['menu'] = view('Template/menu');
        $info['feedback'] = view('vistas/alumno/feedback');
        return view('vistas/alumno/inicio_alumno', $info);
    }

    public function feedback() {
        $info = [];
        $info['footer'] = view('Template/footer');
        $info['header'] = view('Template/header');
        $info['menu'] = view('Template/menu');
        return view('vistas/alumno/feedback', $info);
    }

    public function factura($id_pago = null) {
        if (!$id_pago) {
            return redirect()->to('/alumno/mis_citas')->with('error', 'No se especificó un comprobante.');
        }

        $db = \Config\Database::connect();
        $builder = $db->table('pago_estatico p');
        
        $builder->select('p.*, c.fecha_hora_inicio, c.materia, prof.nombre_profesor, est.name as nombre_alumno');
        $builder->join('citas c', 'c.id_cita = p.id_cita');
        $builder->join('perfil_profesor prof', 'prof.id_auth = c.id_profesor'); 
        $builder->join('perfiles_estudiantes est', 'est.id_auth = c.id_alumno');   
        $builder->where('p.id_pago', $id_pago);
        
        $data['pago'] = $builder->get()->getRowArray();

        if (!$data['pago']) {
            return redirect()->to('/alumno/mis_citas')->with('error', 'La factura no existe.');
        }

        $info['footer'] = view('Template/footer');
        $info['header'] = view('Template/header');
        $info['menu']   = view('Template/menu');

        return view('vistas/alumno/factura', array_merge($info, $data));
    }

    public function mis_citas() {
        $idAlumno = session()->get('id_auth');
        $modelHorario = new HorarioModel();
        $db = \Config\Database::connect();

        $data['horarios'] = $modelHorario->orderBy("FIELD(week_day, 'LUNES','MARTES','MIERCOLES','JUEVES','VIERNES','SABADO','DOMINGO')", '', false)
                                         ->orderBy('hora_inicio', 'ASC')
                                         ->findAll();

        $builder = $db->table('citas c');
        $builder->select('c.*, p.id_pago, p.estado_pago, prof.nombre_profesor');
        $builder->join('pago_estatico p', 'p.id_cita = c.id_cita', 'left'); 
        $builder->join('perfil_profesor prof', 'prof.id_auth = c.id_profesor', 'left');
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
        $idAlumno = session()->get('id_auth');
        $horarios = $this->request->getPost('horarios');
        $materias = $this->request->getPost('materias');
        $fechas = $this->request->getPost('fecha');

        if ($horarios) {
            $db = \Config\Database::connect();
            $db->transStart(); 
            $ultimaCitaId = null;

            foreach ($horarios as $idHorario) {
                $horario = $horarioModel->find($idHorario);
                if (!$horario) continue;

                $inicio = new \DateTime($horario['hora_inicio']);
                $fin = new \DateTime($horario['hora_fin']);
                $duracion = $inicio->diff($fin);
                $totalMinutos = ($duracion->days * 24 * 60) + ($duracion->h * 60) + $duracion->i;

                $fechaHoraInicio = (new \DateTime($fechas[$idHorario] . ' ' . $horario['hora_inicio']))->format('Y-m-d H:i:s');

                $data = [
                    'id_alumno'         => $idAlumno,
                    'id_profesor'       => $horario['id_profesor'],
                    'id_horario'        => $idHorario,
                    'fecha_hora_inicio' => $fechaHoraInicio,
                    'duracion_min'      => $totalMinutos,
                    'materia'           => $materias[$idHorario],
                    'estado_cita'       => 'pendiente'
                ];

                $citaModel->insert($data);
                $ultimaCitaId = $db->insertID();
            }

            $db->transComplete(); 
            if ($db->transStatus() === false) {
                return redirect()->back()->with('error', 'Error al crear la reserva.');
            }

            return redirect()->to(base_url('alumno/pago_estatico/' . $ultimaCitaId));
        }
        return redirect()->to('alumno/mis_citas');
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