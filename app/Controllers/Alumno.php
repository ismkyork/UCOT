<?php

namespace App\Controllers;
use App\Models\CitaModel;
use App\Models\HorarioModel;

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
                'id_alumno'        => $idAlumno, //id_alumno relacionado con id_auth del alumno tomado desde el login
                'id_profesor'      => $horario['id_profesor'], // tomado directamente del horario
                'fecha_hora_inicio'=> $fechaHoraInicio,
                'duracion_min'     => $totalMinutos,
                'materia'          => $materias[$idHorario],
                'estado_cita'      => 'pendiente'
            ];

            //Insertar y capturar errores si falla
            if (!$citaModel->insert($data)) {
                return redirect()->back()->withInput()->with('errors', $citaModel->errors());
            }
        }

        $db->transComplete();
    }

    //Redirigir con mensaje de éxito
    return redirect()->to('alumno/mis_citas')->with('success', 'Citas reservadas correctamente');
}

//----------------------------------------------------------------------------------------------------

     public function pago_estatico() {
      
      $info['footer']=view('Template/footer');
      $info['header']=view('Template/header');
      $info['menu']=view('Template/menu');
      return view('vistas/alumno/pago_estatico',$info);
    }


}
