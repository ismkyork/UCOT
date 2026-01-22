<?php

namespace App\Controllers;
use App\Models\LoginModel; 
use App\Models\AlumnoModel;
use App\Models\ProfesorModel; 

class Auth extends BaseController {

    public function procesarlogin() {
        $session = session();
        $usuario = new LoginModel();

        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');

        $data = $usuario->where('email', $email)->first();

        if ($data) {
            $pass = $data['password'];
            
            if (password_verify($password, $pass)) {
                
                $session->regenerate();

                $session->regenerate();

                    // 1. Busca el nombre real según el rol
                    $nombreReal = '';
                    if ($data['rol'] == 'Profesor') {
                    $modelP = new \App\Models\ProfesorModel();
                    $perfil = $modelP->where('id_auth', $data['id_auth'])->first();
                    $nombreReal = $perfil['nombre_profesor'];

                    //Guardar el id_profesor en sesión
                    if ($perfil) {
                        $session->set('id_profesor', $perfil['id_profesor']);
                        }
                    } else {
                        $modelA = new \App\Models\AlumnoModel();
                        $perfil = $modelA->where('id_auth', $data['id_auth'])->first();
                        $nombreReal = $perfil['name'];

                    //Guardar el id_alumno en sesión
                    if ($perfil) {
                        $session->set('id_alumno', $perfil['id_estudiante']);
                    }
                    }



                    // 2. Guarda datos en sesión
                    $ses_data = [
                        'id_auth'    => $data['id_auth'],
                        'email'      => $data['email'],
                        'rol'        => $data['rol'],
                        'name'       => $nombreReal, 
                        'isLoggedIn' => TRUE
                    ];
                    $session->set($ses_data);

                    // 3. MENSAJE DE BIENVENIDA 
                    $session->setFlashdata('bienvenida', '¡Bienvenido(a) de nuevo, ' . $nombreReal . '!');

                if ($data['rol'] == 'Profesor') {
                    return redirect()->to('/profesor/dashboard'); 
                } elseif ($data['rol'] == 'Estudiante') {
                    return redirect()->to('/alumno/factura');
                } else {
                    return redirect()->to('/auth/login');
                }

            } else {
                $session->setFlashdata('msg', 'Contraseña incorrecta');
                return redirect()->to('/auth/login');
            }
        } else {
            $session->setFlashdata('msg', 'Correo no encontrado');
            return redirect()->to('/auth/login');
        }
    }

    public function salir() {
        $session = session();
        $session->destroy();
        return redirect()->to('./');
    }

    public function index(){
      $info['footer']=view('Template/footer');
      $info['header']=view('Template/header');
      $info['menu']=view('Template/menu');
      return view('vistas/inicio',$info);
    }

    public function login()
    {
      $info['footer']=view('Template/footer');
      $info['header']=view('Template/header');
      $info['menu']=view('Template/menu');
      return view('vistas/auth/login',$info);
    }

    public function registro()
    {
      $info['footer']=view('Template/footer');
      $info['header']=view('Template/header');
      $info['menu']=view('Template/menu');
      return view('vistas/auth/registro',$info);
    }

  public function registrarUsuario() {
  
    $reglas = [
        'name'      => 'required|min_length[2]',
        'apellido'  => 'required',
        'email'     => 'required|valid_email|is_unique[auth.email]', 
        'password'  => 'required|min_length[5]',
        'tipo_user' => 'required'
    ];

    // Ejecutar validación
    if (!$this->validate($reglas)) {
        // Si falla, regresa al formulario con los errores y los datos escritos (withInput)
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    $loginModel = new LoginModel();
    $db = \Config\Database::connect(); // Conexión para usar transacciones

    $email     = $this->request->getPost('email');
    $password  = $this->request->getPost('password');
    $nombre    = $this->request->getPost('name');      
    $apellido  = $this->request->getPost('apellido');
    $rol       = $this->request->getPost('tipo_user'); 

    // Iniciamos una transacción para que no se cree el login si falla el perfil
    $db->transStart();

    // 3. Insertar en tabla 'auth'
    $id_auth = $loginModel->insert([
        'email'    => $email,
        'password' => password_hash($password, PASSWORD_DEFAULT),
        'rol'      => $rol
    ]);

    // 4. Insertar en la tabla de perfil según el rol
    if ($rol == 'Profesor') { 
        $profesorModel = new ProfesorModel();
        $profesorModel->insert([
            'id_auth'           => $id_auth,
            'nombre_profesor'   => $nombre,
            'apellido_profesor' => $apellido
        ]);
    } else {
        $AlumnoModel = new AlumnoModel();
        $AlumnoModel->insert([
            'id_auth'  => $id_auth,
            'name'     => $nombre,
            'apellido' => $apellido
        ]);
    }

    $db->transComplete(); // Finaliza la transacción

    if ($db->transStatus() === false) {
        return redirect()->back()->withInput()->with('msg', 'Hubo un error al guardar los datos.');
    }

    session()->setFlashdata('msg', 'Registro exitoso, ya puedes iniciar sesión');
    return redirect()->to('/auth/login');
}
}