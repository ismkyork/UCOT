<?php

namespace App\Controllers;
use App\Models\CitaModel;
use App\Models\AlumnoModel;
use App\Models\LoginModel;

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
        
    public function mis_citas() {
      $model = new CitaModel();
      $data['citas'] = $model->where('id_alumno', session()->get('id_estudiante'))->findAll();
      $info['footer']=view('Template/footer');
      $info['header']=view('Template/header');
      $info['menu']=view('Template/menu');
      return view('vistas/alumno/mis_citas',array_merge($info, $data));
    }


     public function pago_estatico() {
      
      $info['footer']=view('Template/footer');
      $info['header']=view('Template/header');
      $info['menu']=view('Template/menu');
      return view('vistas/alumno/pago_estatico',$info);
    }

    /**
     * Procesa el formulario enviado por el estudiante.
     * Ruta: POST /alumno/citas/guardar
     */
    public function guardar()
    {
             // 1. Forzamos la zona horaria definida en tu Config/App.php
        date_default_timezone_set('America/Caracas'); 

        $GuardarCita = new CitaModel(); 
        $BuscarAdmin = new LoginModel(); 

        // 2. Captura de datos
        $fechaPost = $this->request->getPost('fecha_hora_inicio');
        $studentId = session()->get('id_auth'); // Usamos el ID de la sesión corregida
    
        $timestampPost = strtotime($fechaPost);
        $timestampAhora = time(); 

        // 3. VALIDACIÓN A: No permitir fechas pasadas
        // Usamos un margen de 60 segundos para evitar errores si el usuario tarda en dar clic
        if ($timestampPost < ($timestampAhora - 60)) {
            return redirect()->back()
                ->withInput()
                ->with('msg_error', 'No puedes agendar en el pasado. La hora del sistema es: ' . date('h:i a', $timestampAhora));
        }

          // 4. VALIDACIÓN B: Intervalos de 30 minutos (:00 o :30)
            $minutos = date('i', $timestampPost);
            if ($minutos !== '00' && $minutos !== '30') {
                return redirect()->back()
                    ->withInput()
                    ->with('msg_error', 'Solo se permiten citas en la hora punto (:00) o media hora (:30).'); 
            }


        // 5. ENCONTRAR AL PROFESOR (El paso clave de tu requerimiento)
        $profesorAdmin = $BuscarAdmin->where('rol', 'Profesor')->first();

        if (!$profesorAdmin) {
            return redirect()->back()->with('error', 'Error crítico: No se encontró un profesor asignado en el sistema. Contacte soporte.');
        }
    
        $professorId = $profesorAdmin['id_auth'];


        // 6. Preparar los datos para insertar en la tabla citas
        $datosCita = [
            'id_alumno'       => $studentId,
            'id_profesor'     => $professorId,
            'fecha_hora_inicio' => $this->request->getPost('fecha_hora_inicio'), 
            'materia'           => $this->request->getPost('materia'),     
            'duracion_min'      => $this->request->getPost('duracion_min'),
            'estado_cita'           => 'pendiente'
        ];

        // 7. Intentar guardar en la base de datos
        if ($GuardarCita->save($datosCita)) {
            return redirect()->to('/alumno/mis_citas')->with('msg', 'Tu solicitud de cita ha sido enviada y está pendiente de aprobación.');
        } else {
          echo "<h1>¡Error al Guardar!</h1>";
        echo "<pre>";
        print_r($GuardarCita->errors()); 
        echo "</pre>";
        die(); 
            return redirect()->back()->withInput()->with('errors', $GuardarCita->errors());
        }
    }




}
