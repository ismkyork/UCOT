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

    public function config_horarios() {
        $model = new HorarioModel();
        $data['horarios'] = $model->where('id_profesor', session()->get('id_auth'))->findAll(); 
        
        $info['footer'] = view('Template/footer');
        $info['header'] = view('Template/header');
        $info['menu']=view('Template/menu');
        return view('vistas/profesor/config_horarios', array_merge($info, $data));
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
        return view('vistas/profesor/dashboard', array_merge($info, $data));
    }
}