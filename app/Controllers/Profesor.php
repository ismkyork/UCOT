<?php

namespace App\Controllers;

use App\Models\CitaModel;
use App\Models\HorarioModel;
use App\Models\ProfesorModel;
use App\Models\LoginModel;
use App\Models\FeedbackModel;

class Profesor extends BaseController
{
    protected $horarioModel;
    protected $profesorModel;

    public function __construct()
    {
        // Inicializamos los modelos para evitar errores de "Undefined property"
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

    public function citas()
    {
        $citasModel = new CitaModel();
        $myAdminId = session()->get('id_auth');

        $resultados = $citasModel
            ->select('citas.*, auth.email as estudiante_email')
            ->join('auth', 'auth.id_auth = citas.id_alumno')
            ->where('citas.estado_cita', 'pendiente')
            ->where('citas.id_profesor', $myAdminId)
            ->orderBy('citas.created_at', 'DESC')
            ->findAll();

        $data = [
            'titulo' => 'Solicitudes Pendientes',
            'citas'  => $resultados
        ];

        $info['header'] = view('Template/header');
        $info['footer'] = view('Template/footer');
        $info['menu'] = view('Template/menu');
        return view('vistas/profesor/citas', array_merge($info, $data));
    }

    


    public function calendario_profesor()
    {
        return view('vistas/profesor/calendario_profesor');
    }

    public function opiniones()
    {
   
        $feedbackModel = new FeedbackModel();
        $datos = $feedbackModel->orderBy('fecha_evaluacion', 'DESC')->findAll();
        $data = [
            'comentarios' => $datos
        ];

        $info = [];
        $info['header'] = view('Template/header');
        $info['menu']   = view('Template/menu'); 
        $info['footer'] = view('Template/footer');

        return view('vistas/profesor/opiniones', array_merge($info, $data));
    }


    public function procesar()
    {
        $citasModel = new CitaModel();
        $citaId = $this->request->getPost('id_cita');
        $accionTomada = $this->request->getPost('accion');

        $nuevoEstado = ($accionTomada === 'aprobar') ? 'confirmado' : (($accionTomada === 'rechazar') ? 'rechazado' : '');

        if (empty($nuevoEstado)) {
            return redirect()->back()->with('error', 'Acción no válida.');
        }

        $cita = $citasModel->find($citaId);
        if (!$cita) return redirect()->back()->with('error', 'Cita no encontrada.');

        if ($cita['id_profesor'] != session()->get('id_auth')) {
            return redirect()->back()->with('error', 'No tienes permisos.');
        }

        $citasModel->update($citaId, ['estado_cita' => $nuevoEstado]);
        return redirect()->to('/profesor/citas')->with('msg', 'Solicitud procesada correctamente.');
    }

    public function config_horarios()
    {
        $data['horarios'] = $this->horarioModel
            ->where('id_profesor', session()->get('id_auth'))
            ->orderBy("FIELD(week_day, 'Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo')", '', false)
            ->orderBy('hora_inicio', 'ASC')
            ->findAll();

        $info['footer'] = view('Template/footer');
        $info['header'] = view('Template/header');
        $info['menu'] = view('Template/menu');
        return view('vistas/profesor/HorarioLeer', array_merge($info, $data));
    }

    public function agg_horarios()
    {
        $info['footer'] = view('Template/footer');
        $info['header'] = view('Template/header');
        $info['menu'] = view('Template/menu');
        return view('vistas/profesor/HorarioAgregar', $info);
    }

    public function store_horarios()
    {
        try {
            $fechaSeleccionada = $this->request->getPost('fecha_cita');
            $bloques = $this->request->getPost('bloque_horario');

            if (empty($bloques)) {
                return redirect()->back()->with('error', 'Debes seleccionar al menos un horario.');
            }

            $diasSemana = [
                1 => 'LUNES', 2 => 'MARTES', 3 => 'MIÉRCOLES',
                4 => 'JUEVES', 5 => 'VIERNES', 6 => 'SÁBADO', 7 => 'DOMINGO'
            ];
            $numDia = date('N', strtotime($fechaSeleccionada));
            $nombreDia = $diasSemana[$numDia];

            // Obtener ID del profesor de la sesión
            $idProfesor = session()->get('id_auth');

            foreach ($bloques as $bloque) {
                $partes = explode('-', $bloque);
                $data = [
                    'id_profesor' => $idProfesor,
                    'week_day'    => $nombreDia,
                    'hora_inicio' => $partes[0],
                    'hora_fin'    => $partes[1],
                    'estado'      => 'Disponible'
                ];
                $this->horarioModel->insert($data);
            }

            return redirect()->to(base_url('profesor/HorarioLeer'))
                ->with('mensaje', 'Horarios guardados correctamente para el día ' . $nombreDia);

        } catch (\Exception $e) {
            return "Error de sistema: " . $e->getMessage();
        }
    }

    public function confirmacion_horario()
    {
        $data = [
            'header' => view('Template/header'),
            'footer' => view('Template/footer'),
            'menu'   => view('Template/menu'),
        ];
        return view('vistas/profesor/HorarioConfirmacion', $data);
    }

    public function dlt_horario($id_horario = null)
    {
        if ($id_horario !== null) {
            $this->horarioModel->delete($id_horario);
        }
        return redirect()->to(base_url('profesor/HorarioLeer'))->with('msg', 'Horario Eliminado Correctamente');
    }

    public function edit_horario($id_horario = null)
    {
        $horario = $this->horarioModel->find($id_horario);
        $profesores = $this->profesorModel->findAll();

        $info['header'] = view('Template/header');
        $info['footer'] = view('Template/footer');
        $info['menu'] = view('Template/menu');

        return view('vistas/profesor/HorarioEditar', array_merge($info, [
            'horario' => $horario,
            'profesores' => $profesores
        ]));
    }

    public function update_horario($id_horario = null)
    {
        $data = [
            'week_day'    => $this->request->getPost('week_day'),
            'hora_inicio' => $this->request->getPost('hora_inicio'),
            'hora_fin'    => $this->request->getPost('hora_fin'),
            'estado'      => $this->request->getPost('estado'),
        ];

        $this->horarioModel->update($id_horario, $data);
        return redirect()->to(base_url('profesor/HorarioLeer'))->with('msg', 'Horario Editado Correctamente');
    }

    public function dashboard() {
        $modelCitas = new CitaModel();
        $data['citas_pendientes'] = $modelCitas
            ->where('estado_cita', 'pendiente')
            ->where('id_profesor', session()->get('id_auth'))
            ->findAll();
            
        $info['footer'] = view('Template/footer');
        $info['header'] = view('Template/header');
        $info['menu']=view('Template/menu');
        $info['dashboard']=view('vistas/profesor/dashboard');

        return view('vistas/profesor/dashboard', array_merge($info, $data));
    }

}
