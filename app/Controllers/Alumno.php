<?php

namespace App\Controllers;
use App\Models\CitaModel;
use App\Models\HorarioModel;
use App\Models\PagoEstaticoModel;

class Alumno extends BaseController
{
    public function index() {   
      $info['footer']=view('Template/footer');
      $info['header']=view('Template/header');
      $info['menu']=view('Template/menu');
      return view('vistas/inicio',$info);
    }

    public function calendario() {
      $info=[];
      $info['footer']=view('Template/footer');
      $info['header']=view('Template/header');
      $info['menu']=view('Template/menu');
      return view('vistas/alumno/calendario',$info);
    }

    public function inicio_alumno() {
      $info=[];
      $info['footer']=view('Template/footer');
      $info['header']=view('Template/header');
      $info['menu']=view('Template/menu');
      $info['feedback']=view('vistas/alumno/feedback');
      return view('vistas/alumno/inicio_alumno',$info);
    }

    public function feedback() {
      $info=[];
      $info['footer']=view('Template/footer');
      $info['header']=view('Template/header');
      $info['menu']=view('Template/menu');
      return view('vistas/alumno/feedback',$info);
    }

    public function factura(){
      $info['footer']=view('Template/footer');
      $info['header']=view('Template/header');
      $info['menu']=view('Template/menu');
      return view('vistas/alumno/factura',$info);
    }
        
    //Citas----------------------------------------------------------------------------------------------
 
    public function mis_citas() {
        $modelHorario = new HorarioModel();
        $data['horarios'] = $modelHorario->orderBy("FIELD(week_day, 'Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo')", '', false)
                                         ->orderBy('hora_inicio', 'ASC')
                                         ->findAll();

        $info['footer'] = view('Template/footer');
        $info['header'] = view('Template/header');
        $info['menu']   = view('Template/menu');
        return view('vistas/alumno/mis_citas', array_merge($info, $data));
    }

    public function store_citas()
    {
        $citaModel    = new CitaModel();
        $horarioModel = new HorarioModel();

        $idAlumno   = session()->get('id_auth');
        $horarios   = $this->request->getPost('horarios');
        $materias   = $this->request->getPost('materias');
        $fechas     = $this->request->getPost('fecha');

        if ($horarios) {
            $db = \Config\Database::connect();
            $db->transStart(); // Iniciamos transacción
            
            $ultimaCitaId = null;

            foreach ($horarios as $idHorario) {
                $horario = $horarioModel->find($idHorario);
                if (!$horario) continue;
                if (!isset($materias[$idHorario], $fechas[$idHorario])) continue;

                $inicio   = new \DateTime($horario['hora_inicio']);
                $fin      = new \DateTime($horario['hora_fin']);
                $duracion = $inicio->diff($fin);
                $totalMinutos = ($duracion->days * 24 * 60) + ($duracion->h * 60) + $duracion->i;

                $fechaHoraInicio = (new \DateTime($fechas[$idHorario] . ' ' . $horario['hora_inicio']))->format('Y-m-d H:i:s');

                $data = [
                    'id_alumno'         => $idAlumno,
                    'id_profesor'       => $horario['id_profesor'],
                    'fecha_hora_inicio' => $fechaHoraInicio,
                    'duracion_min'      => $totalMinutos,
                    'materia'           => $materias[$idHorario],
                    'estado_cita'       => 'pendiente'
                ];

                $citaModel->insert($data);
                $ultimaCitaId = $db->insertID();
            }

            $db->transComplete(); // Terminamos transacción

            if ($db->transStatus() === false) {
                return redirect()->back()->with('error', 'Error al crear la reserva. Intente de nuevo.');
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
    $db = \Config\Database::connect();

    $montoRaw = $this->request->getPost('monto');
    $montoLimpio = str_replace(['.', ','], ['', '.'], $montoRaw);
    $id_cita = $this->request->getPost('id_cita');

    // 1. Verificación rápida: ¿Llega el ID de la cita?
    if (empty($id_cita)) {
        return redirect()->back()->with('error', 'Error: No se recibió el ID de la cita en el formulario.');
    }

    $reglas = [
        'id_pago'     => 'required',
        'monto'       => 'required',
        'fecha_pago'  => 'required',
        'imagen_pago' => 'uploaded[imagen_pago]|max_size[imagen_pago,2048]|is_image[imagen_pago]'
    ];

    if (!$this->validate($reglas)) {
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    // 2. Manejo del archivo
    $img = $this->request->getFile('imagen_pago');
    $nombreImg = $img->getRandomName();
    
    // 3. Intentar insertar
    $data = [
        'id_cita'     => $id_cita,
        'id_pago'     => $this->request->getPost('id_pago'), 
        'monto'       => $montoLimpio,
        'fecha_pago'  => $this->request->getPost('fecha_pago'),
        'screenshot'  => $nombreImg,
        'estado_pago' => 'pendiente'
    ];

    if ($modelPago->insert($data)) {
        // Solo si se guarda en BDD, movemos la imagen
        $img->move(ROOTPATH . 'public/uploads/comprobantes', $nombreImg);
        session()->remove('id_cita_pendiente'); 
        return redirect()->to('/alumno/mis_citas')->with('success', 'Pago enviado con éxito.');
    } else {
        // ¡ESTO ES LO IMPORTANTE! Si no guarda, nos dirá por qué.
        dd($modelPago->errors()); 
    }
}
    
    public function pago_paypal_exito($orderID = null) {
        $id_cita = session()->get('id_cita_pendiente');

        if (!$orderID || !$id_cita) {
            return redirect()->to('/alumno/mis_citas')->with('error', 'Error de sesión: No se encontró la cita.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $modelPago = new PagoEstaticoModel();
        $modelCita = new CitaModel();

        $dataPago = [
            'id_cita'     => $id_cita,
            'id_pago'     => 'PAYPAL-' . $orderID,
            'monto'       => 10.00,
            'fecha_pago'  => date('Y-m-d'),
            'screenshot'  => 'CONFIRMADO_PAYPAL',
            'estado_pago' => 'completado'
        ];
        
        $modelPago->insert($dataPago);
        $modelCita->update($id_cita, ['estado_cita' => 'confirmada']);
        
        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->to('/alumno/mis_citas')->with('error', 'El pago se procesó pero no se pudo actualizar la cita. Contacte soporte.');
        }
        
        session()->remove('id_cita_pendiente');
        return redirect()->to('/alumno/mis_citas')->with('success', '¡Pago exitoso! Tu cita ha sido confirmada automáticamente.');
    }
}