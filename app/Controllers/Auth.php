<?php

namespace App\Controllers;

use App\Models\LoginModel;
use App\Models\AlumnoModel;
use App\Models\ProfesorModel;

class Auth extends BaseController
{

    public function procesarlogin()
    {
        $session = session();
        $usuarioModel = new LoginModel();

        // Recibimos los datos del formulario
        $email = $this->request->getVar('correo');
        $password = $this->request->getVar('contraseña');

        // IMPORTANTE: Buscamos por la columna 'correo' según tu BD
        $data = $usuarioModel->where('correo', $email)->first();

        if ($data) {
            // Validamos contra la columna 'contraseña'
            $passBD = $data['contraseña'];

            if (password_verify($password, $passBD)) {
                $session->regenerate();

                $nombreReal = 'Usuario'; // Nombre por defecto por seguridad
                $perfil = null;

                // 1. Buscamos el perfil detallado según el rol
                if ($data['rol'] == 'Profesor') {
                    $modelP = new ProfesorModel();
                    $perfil = $modelP->where('id_auth', $data['id_auth'])->first();
                    
                    if ($perfil) {
                        $nombreReal = $perfil['nombre_profesor'];
                        $session->set('id_profesor', $perfil['id_profesor']);
                    }
                } else {
                    $modelA = new AlumnoModel();
                    $perfil = $modelA->where('id_auth', $data['id_auth'])->first();
                    
                    if ($perfil) {
                        // Verificamos si en AlumnoModel es 'name' o 'nombre_alumno'
                        $nombreReal = $perfil['nombre_estudiante'] ?? 'Estudiante';
                        $session->set('id_alumno', $perfil['id_estudiante']);
                    }
                }

                // 2. Guardamos datos esenciales en la sesión
                $ses_data = [
                    'id_auth'    => $data['id_auth'],
                    'correo'      => $data['correo'],
                    'rol'        => $data['rol'],
                    'nombre'       => $nombreReal,
                    'isLoggedIn' => TRUE
                ];
                $session->set($ses_data);

                // 3. Mensaje de bienvenida
                $session->setFlashdata('bienvenida', '¡Bienvenido(a) de nuevo, ' . $nombreReal . '!');

                // 4. Redirección por rol
                if ($data['rol'] == 'Profesor') {
                    return redirect()->to('/profesor/dashboard');
                } elseif ($data['rol'] == 'Estudiante') {
                    return redirect()->to('/alumno/inicio_alumno');
                } else {
                    return redirect()->to('/');
                }

            } else {
                $session->setFlashdata('msg', 'Contraseña incorrecta');
                return redirect()->back()->withInput();
            }
        } else {
            $session->setFlashdata('msg', 'El correo no está registrado');
            return redirect()->back()->withInput();
        }
    }

    public function registrarUsuario()
    {
        // Ajustamos la regla is_unique para que use la columna 'correo'
        $reglas = [
            'nombre'      => 'required|min_length[2]',
            'apellido'  => 'required',
            'correo'     => 'required|valid_email|is_unique[auth.correo]', 
            'contraseña'  => 'required|min_length[5]',
            'tipo_user' => 'required'
        ];

        if (!$this->validate($reglas)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $loginModel = new LoginModel();
        $db = \Config\Database::connect();

        $email    = $this->request->getPost('correo');
        $password = $this->request->getPost('contraseña');
        $nombre   = $this->request->getPost('nombre');
        $apellido = $this->request->getPost('apellido');
        $rol      = $this->request->getPost('tipo_user');

        $db->transStart();

        // Insertamos usando los nombres de columna de tu BD
        $id_auth = $loginModel->insert([
            'correo'     => $email,
            'contraseña' => password_hash($password, PASSWORD_DEFAULT),
            'rol'        => $rol
        ]);

        if ($rol == 'Profesor') {
            $profesorModel = new ProfesorModel();
            $profesorModel->insert([
                'id_auth'           => $id_auth,
                'nombre_profesor'   => $nombre,
                'apellido_profesor' => $apellido
            ]);
        } else {
            $alumnoModel = new AlumnoModel();
            $alumnoModel->insert([
                'id_auth'  => $id_auth,
                'nombre_estudiante'     => $nombre,
                'apellido_estudiante' => $apellido
            ]);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('msg', 'Error al crear la cuenta.');
        }

        session()->setFlashdata('msg', 'Registro exitoso, ya puedes iniciar sesión');
        return redirect()->to('/');
    }

    public function salir()
    {
        session()->destroy();
        return redirect()->to('/');
    }

    // --- Vistas ---
    public function index()
    {
        $info['footer'] = view('Template/footer');
        $info['header'] = view('Template/header');
        return view('vistas/inicio', $info);
    }

    public function login()
    {
        return view('vistas/auth/login');
    }

    public function registro()
    {
        return view('vistas/auth/registro');
    }

    public function password_olvidada()
    {
        return view('vistas/auth/password_olvidada');
    }

    public function actualizar_password()
    {
        return view('vistas/auth/actualizar_password');
    }

    public function codigo_sesion()
    {
        return view('vistas/auth/codigo_sesion');
    }
}