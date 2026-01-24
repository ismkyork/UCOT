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

    public function factura(){
      $info['footer']=view('Template/footer');
      $info['header']=view('Template/header');
      $info['menu']=view('Template/menu');
      return view('vistas/alumno/factura',$info);
    }
        
//Citas----------------------------------------------------------------------------------------------
 
//Para ver los bloques disponibles
      public function mis_citas() {
      $modelHorario = new HorarioModel();
      $data['horarios'] = $modelHorario ->orderBy("FIELD(week_day, 'Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo')", '', false) ->orderBy('hora_inicio', 'ASC') ->findAll();

      $info['footer']=view('Template/footer');
      $info['header']=view('Template/header');
      $info['menu']=view('Template/menu');
      return view('vistas/alumno/mis_citas',array_merge($info, $data));
    }

//Guardar citas

public function store_citas()
{
    $citaModel    = new CitaModel();
    $horarioModel = new HorarioModel();

    // Datos enviados desde el formulario
    $idAlumno   = session()->get('id_auth');             // obtener id_auth relacionado al id_alumno desde el login
    $horarios   = $this->request->getPost('horarios');   // array de id_horario seleccionados
    $materias   = $this->request->getPost('materias');   // array con materia escrita
    $fechas     = $this->request->getPost('fecha');      // array con fecha seleccionada

    if ($horarios) {
        $db = \Config\Database::connect();
        $db->transStart();

        $ultimaCitaId = null; // Variable para capturar el ID y redirigir al pago

        foreach ($horarios as $idHorario) {
            //Traer datos del horario
            $horario = $horarioModel->find($idHorario);
            if (!$horario) continue;

            //Valida que existan los índices del formulario
            if (!isset($materias[$idHorario], $fechas[$idHorario])) {
                continue;
            }

            //Calcular duración en minutos
            $inicio   = new \DateTime($horario['hora_inicio']);
            $fin      = new \DateTime($horario['hora_fin']);
            $duracion = $inicio->diff($fin);
            $totalMinutos = ($duracion->days * 24 * 60) + ($duracion->h * 60) + $duracion->i;

            //Construir fecha completa (fecha seleccionada + hora_inicio)
            $fechaHoraInicio = (new \DateTime($fechas[$idHorario] . ' ' . $horario['hora_inicio']))
                               ->format('Y-m-d H:i:s');

            // Armar registro para la tabla citas
            $data = [
                'id_alumno'         => $idAlumno, //id_alumno relacionado con id_auth del alumno tomado desde el login
                'id_profesor'       => $horario['id_profesor'], // tomado directamente del horario
                'fecha_hora_inicio'=> $fechaHoraInicio,
                'duracion_min'     => $totalMinutos,
                'materia'           => $materias[$idHorario],
                'estado_cita'       => 'pendiente'
            ];

            //Insertar y capturar errores si falla
            if (!$citaModel->insert($data)) {
                return redirect()->back()->withInput()->with('errors', $citaModel->errors());
            }

            // Capturamos el ID de la cita recién creada para el pago
            $ultimaCitaId = $db->insertID();
        }

        $db->transComplete();

        // Redirigir al flujo de pago estático con el ID de la cita creada
        if ($db->transStatus() !== false && $ultimaCitaId) {
            return redirect()->to(base_url('alumno/pago_estatico/' . $ultimaCitaId));
        }
    }

    //Redirigir con mensaje de éxito (fallback si algo falla en el flujo)
    return redirect()->to('alumno/mis_citas')->with('success', 'Citas reservadas correctamente');
}

//----------------------------------------------------------------------------------------------------

    public function pago_estatico($id_cita = null) {
        $info['footer'] = view('Template/footer');
        $info['header'] = view('Template/header');
        $info['menu']   = view('Template/menu');

        // Pasamos el ID de la cita recibido por la URL para el campo hidden del form
        $info['id_cita'] = $id_cita;

        return view('vistas/alumno/pago_estatico', $info);
    }

    // Funcion para guardar los datos del pago
    public function guardar_pago() {
        $modelPago = new PagoEstaticoModel();

        // 1. Validar que la imagen y los campos estén correctos
        $reglas = [
            'monto' => 'required|decimal',
            'fecha_pago' => 'required',
            'imagen_pago' => 'uploaded[imagen_pago]|max_size[imagen_pago,2048]|is_image[imagen_pago]'
        ];

        if (!$this->validate($reglas)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Subir el comprobante a la carpeta public/uploads/
        $img = $this->request->getFile('imagen_pago');
        $nombreImg = $img->getRandomName();
        $img->move(ROOTPATH . 'public/uploads/comprobantes', $nombreImg);

        // Insertar en la base de datos
        $data = [
            'id_cita'     => $this->request->getPost('id_cita'), // Debes enviarlo oculto en el form
            'monto'       => $this->request->getPost('monto'),
            'fecha_pago'  => $this->request->getPost('fecha_pago'),
            'screenshot'  => $nombreImg,
            'estado_pago' => 'pendiente' // Estado inicial
        ];

        $modelPago->insert($data);

        return redirect()->to('/alumno/mis_citas')->with('msg', 'Pago enviado exitosamente.');
    }
}