<?php

namespace App\Controllers;
use App\Models\CitaModel;
use App\Models\HorarioModel;
use App\Models\ProfesorModel;

class Profesor extends BaseController
{
  
    public function index() {
        $info['footer'] = view('Template/footer');
        $info['header'] = view('Template/header');
        $info['menu']=view('Template/menu');
        return view('vistas/inicio', $info);
    }

    public function citas() {
      
    $citasModel = new CitaModel(); 

        $myAdminId = session()->get('id_auth');

        // 1. Consulta a la Base de Datos
        $resultados = $citasModel
            ->select('citas.*, auth.email as estudiante_email')
            ->join('auth', 'auth.id_auth = citas.id_alumno')
            ->where('citas.estado_cita', 'pendiente')
            ->where('citas.id_profesor', $myAdminId)
            ->orderBy('citas.created_at', 'DESC')
            ->findAll();

        // 2. Preparar datos para la vista
        $data = [
            'titulo' => 'Solicitudes Pendientes',
            'citas'  => $resultados 
        ];

        $info['header'] = view('Template/header');
        $info['footer'] = view('Template/footer');
        $info['menu']=view('Template/menu');
        return view('vistas/profesor/citas', array_merge($info, $data));
    }

    public function procesar()
    {
        $citasModel = new CitaModel();

        $citaId = $this->request->getPost('id_cita'); 
        $accionTomada = $this->request->getPost('accion'); 

        $nuevoEstado = '';
        if ($accionTomada === 'aprobar') {
            $nuevoEstado = 'aprobado';
        } elseif ($accionTomada === 'rechazar') {
            $nuevoEstado = 'rechazado';
        } else {
            return redirect()->back()->with('error', 'Acción no válida.');
        }

        $cita = $citasModel->find($citaId);

        if (!$cita) return redirect()->back()->with('error', 'Cita no encontrada.');
        
        if ($cita['id_profesor'] != session()->get('id_auth')) {
             return redirect()->back()->with('error', 'No tienes permisos.');
        }
        
        if($cita['estado_cita'] !== 'pendiente') {
             return redirect()->back()->with('error', 'Esta solicitud ya fue procesada.');
        }

        $citasModel->update($citaId, [
            'estado_cita' => $nuevoEstado
        ]);

        return redirect()->to('/profesor/citas')->with('msg', 'Solicitud procesada correctamente.');
    }

//Horarios------------------------------------------------------------------------------------------------------------------------------------

     //Para ver los horarios
    public function config_horarios() {
        $model = new HorarioModel(); //Beyker
        $data['horarios'] = $model->where('id_profesor', session()->get('id_auth'))->findAll(); 

        $modelHorario = new HorarioModel(); //Fernando
        $data['horarios'] = $modelHorario->orderBy('id_horario','ASC')->findAll();
        $info['footer'] = view('Template/footer');
        $info['header'] = view('Template/header');
        $info['menu']=view('Template/menu');
      return view('vistas/profesor/HorarioLeer', array_merge($info, $data));
    } 

    
    //Para seleccionar un profesor de la lista al añadir un horario
    public function agg_horarios(){
        $modelProfesor = new ProfesorModel();
        $data['profesores'] = $modelProfesor->findAll();
        $info['footer']=view('Template/footer');
        $info['header']=view('Template/header');
        $info['menu']=view('Template/menu');
      return view('vistas/profesor/HorarioAgregar', array_merge($info, $data));
    }

    //Guardar el horario añadido
    public function store_horarios(){
        $modelHorario = new HorarioModel();

        $data = [
            'id_profesor' => $this->request->getPost('id_profesor'),
            'week_day'    => $this->request->getPost('week_day'),
            'hora_inicio' => $this->request->getPost('hora_inicio'),
            'hora_fin'    => $this->request->getPost('hora_fin'),
            'estado'      => $this->request->getPost('estado'),
        ];

        if (!$modelHorario->insert($data)) {
            dd($modelHorario->errors()); // te muestra el error exacto si falla
        }

      return redirect()->to(base_url('profesor/confirmacion_horario'))
                     ->with('msg', 'Horario Añadido Correctamente');
    }



    //Confirmar guardado de horario añadido
    public function confirmacion_horario(){
      $data = [
          'header' => view('Template/header'),
          'footer' => view('Template/footer'),
          'menu'   => view('Template/menu'),

      ];

      return view('vistas/profesor/HorarioConfirmacion', $data);
    }

    //Eliminar un horario
    public function dlt_horario($id_horario = null){
      $modelHorario = new \App\Models\HorarioModel();

        if ($id_horario !== null) {
            $modelHorario->delete($id_horario);
        }

      return redirect()->to(base_url('profesor/HorarioLeer'))
                     ->with('msg', 'Horario Eliminado Correctamente'); //mensaje de confimación de la eliminación
    }

    //Editar un horario
    public function edit_horario($id_horario = null){
      $modelHorario = new HorarioModel();
      $modelProfesor = new ProfesorModel();

      $horario = $modelHorario->find($id_horario);
      $profesores = $modelProfesor->findAll();

      $info['header'] = view('Template/header');
      $info['footer'] = view('Template/footer');
      $info['menu']=view('Template/menu');

      return view('vistas/profesor/HorarioEditar', array_merge($info, [
        'horario' => $horario,
        'profesores' => $profesores
    ]));
    }

    //Confirmar la edición de un horario
    public function update_horario($id_horario = null){
      $modelHorario = new HorarioModel();

      $data = [
        'week_day'    => $this->request->getPost('week_day'),
        'hora_inicio' => $this->request->getPost('hora_inicio'),
        'hora_fin'    => $this->request->getPost('hora_fin'),
        'estado'      => $this->request->getPost('estado'),
      ];

      $modelHorario->update($id_horario, $data);

      return redirect()->to(base_url('profesor/HorarioLeer'))
                     ->with('msg', 'Horario Editado Correctamente');//mensaje de confimación de la edición
    }

//-----------------------------------------------------------------------------------------------------------------------------------------

    public function dashboard() {
        $modelCitas = new CitaModel();
        $data['citas_pendientes'] = $modelCitas
            ->where('estado_cita', 'pendiente')
            ->where('id_profesor', session()->get('id_auth'))
            ->findAll();
            
        $info['footer'] = view('Template/footer');
        $info['header'] = view('Template/header');
        $info['menu']=view('Template/menu');
        return view('vistas/profesor/dashboard', array_merge($info, $data));
    }
}