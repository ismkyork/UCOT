<?php

namespace App\Controllers;

use App\Models\LoginModel;
use App\Models\AlumnoModel;
use App\Models\ProfesorModel;

class Auth extends BaseController
{

    public function procesarlogin() {
        $session = session();
        $usuarioModel = new \App\Models\LoginModel();

        $email = $this->request->getVar('correo');
        $password = $this->request->getVar('contraseña');

        $data = $usuarioModel->where('correo', $email)->first();

        if ($data) {
            
            if ($data['status'] === 'pendiente') {
                $session->setFlashdata('msg', 'Debes activar tu cuenta. Revisa tu correo electrónico.');
                return redirect()->back()->withInput();
            }

            if (password_verify($password, $data['contraseña'])) {
                $session->regenerate();

                // Variables por defecto
                $nombreReal = 'Usuario';
                $apellidoReal = '';
                $fotoPerfil = 'default.png'; // Valor inicial
                $perfil = null;

                // 1. Lógica para PROFESOR
                if ($data['rol'] == 'Profesor') {
                    $modelP = new \App\Models\ProfesorModel();
                    $perfil = $modelP->where('id_auth', $data['id_auth'])->first();
                    
                    if ($perfil) {
                        $nombreReal   = $perfil['nombre_profesor'];
                        $apellidoReal = $perfil['apellido_profesor'];
                        // AQUI ESTÁ LA CLAVE: Si tiene foto, úsala. Si no, default.
                        $fotoPerfil   = !empty($perfil['foto']) ? $perfil['foto'] : 'default.png';
                        $session->set('id_profesor', $perfil['id_profesor']);
                    }

                // 2. Lógica para ESTUDIANTE (Aquí es donde fallaba)
                } else {
                    $modelA = new \App\Models\AlumnoModel();
                    $perfil = $modelA->where('id_auth', $data['id_auth'])->first();
                    
                    if ($perfil) {
                        $nombreReal   = $perfil['nombre_estudiante'];
                        $apellidoReal = $perfil['apellido_estudiante']; 
                        // AQUI FALTABA ESTA LÍNEA PARA EL ESTUDIANTE:
                        $fotoPerfil   = !empty($perfil['foto']) ? $perfil['foto'] : 'default.png'; 
                        $session->set('id_alumno', $perfil['id_estudiante']);
                    }
                }

                // 3. Guardar en Sesión (Ahora $fotoPerfil ya tiene el valor correcto sea quien sea)
                $ses_data = [
                    'id_auth'    => $data['id_auth'],
                    'correo'     => $data['correo'],
                    'rol'        => $data['rol'],
                    'nombre'     => $nombreReal,
                    'apellido'   => $apellidoReal,
                    'foto'       => $fotoPerfil, // <--- Aquí se guarda la foto real
                    'isLoggedIn' => TRUE
                ];
                $session->set($ses_data);

                $session->setFlashdata('bienvenida', '¡Bienvenido(a) de nuevo, ' . $nombreReal . '!');

                // Redirecciones
                if ($data['rol'] == 'Profesor') return redirect()->to('/profesor/dashboard');
                if ($data['rol'] == 'Estudiante') return redirect()->to('/alumno/inicio_alumno');
                if ($data['rol'] == 'admin') return redirect()->to('/admin/dashboard');
                
                return redirect()->to('/');

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
        $reglas = [
            'nombre'     => 'required|min_length[2]',
            'apellido'   => 'required',
            'correo'     => 'required|valid_email|is_unique[auth.correo]', 
            'contraseña' => 'required|min_length[5]',
            'tipo_user'  => 'required'
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
        
        // --- Generar Token de Activación ---
        $activationToken = bin2hex(random_bytes(32));

        $db->transStart();

        // Insertamos el usuario como 'pendiente'
        $id_auth = $loginModel->insert([
            'correo'           => $email,
            'contraseña'       => password_hash($password, PASSWORD_DEFAULT),
            'rol'              => $rol,
            'status'           => 'pendiente',       // <--- Nace inactivo
            'activation_token' => $activationToken   // <--- Guardamos token
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
                'id_auth'             => $id_auth,
                'nombre_estudiante'   => $nombre,
                'apellido_estudiante' => $apellido
            ]);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('msg', 'Error al crear la cuenta.');
        }

        // --- Enviar Correo de Activación ---
        $this->enviarCorreoActivacion($email, $activationToken);

        session()->setFlashdata('msg', 'Registro exitoso. Por favor revisa tu correo para activar la cuenta.');
        return redirect()->to('/'); // <--- MODIFICADO: Redirecciona al INICIO
    }

    // --- FUNCIÓN PARA ENVIAR CORREO ---
    private function enviarCorreoActivacion($emailDestino, $token) {
    $emailService = \Config\Services::email();
    $emailService->setTo($emailDestino);
    $emailService->setSubject('Activa tu cuenta en UCOT');
    
    // Preparamos los datos para la vista de correo
    $data = [
        'url_activacion' => base_url("auth/activar/$token")
    ];

    // Cargamos la vista externa (esto limpia el controlador)
    $mensaje = view('vistas/correos/activacion_cuenta', $data);
    $emailService->setMessage($mensaje);
    
    if (!$emailService->send()) {
        // Solo para desarrollo, quitar en producción
        die($emailService->printDebugger(['headers'])); 
    }
}

// --- ACTIVAR CUENTA (Link del correo) ---
public function activar($token = null) {
    if (!$token) {
        return redirect()->to('/');
    }

    $loginModel = new \App\Models\LoginModel();
    $usuario = $loginModel->where('activation_token', $token)->first();

    if ($usuario) {
        // Actualizamos el estado y limpiamos el token
        $loginModel->update($usuario['id_auth'], [
            'status'           => 'activo',
            'activation_token' => null
        ]);

        return redirect()->to('/')->with('msg', '¡Cuenta activada correctamente! Ya puedes iniciar sesión.');
    } 

    return redirect()->to('/')->with('error', 'El enlace de activación es inválido o ya fue usado.');
}

    // --- RECUPERACIÓN DE CONTRASEÑA ---
    public function enviar_recovery() {
    $loginModel = new \App\Models\LoginModel();
    $emailDestino = $this->request->getPost('correo');

    $usuario = $loginModel->where('correo', $emailDestino)->first();

    if ($usuario) {
        $token = bin2hex(random_bytes(32));
        $expires = date("Y-m-d H:i:s", time() + 3600); 

        $loginModel->update($usuario['id_auth'], [
            'reset_token'   => $token,
            'reset_expires' => $expires
        ]);

        $emailService = \Config\Services::email();
        $emailService->setTo($emailDestino);
        $emailService->setSubject('Recuperar Contraseña - UCOT');

        $data = [
            'url_reset' => base_url("auth/reset/$token")
        ];

        $mensaje = view('vistas/correos/recuperar_clave', $data);
        
        $emailService->setMessage($mensaje);
        $emailService->send();
    }

    return redirect()->back()->with('msg', 'Si el correo existe, recibirás las instrucciones.');
}

    // 2. Vista para poner nueva clave (Valida token)
    public function vista_reset($token = null) {
        $loginModel = new LoginModel();
        
        $usuario = $loginModel->where('reset_token', $token)
                              ->where('reset_expires >=', date('Y-m-d H:i:s'))
                              ->first();

        if (!$usuario) {
            // MODIFICADO: Redirige al inicio si falla
            return redirect()->to('/')->with('error', 'El enlace ha expirado o no es válido.');
        }

        return view('vistas/auth/actualizar_password', ['token' => $token]);
    }

    // 3. Guardar la nueva clave
    public function guardar_clave() {
        $token = $this->request->getPost('token');
        $password = $this->request->getPost('password');

        $loginModel = new LoginModel();
        $usuario = $loginModel->where('reset_token', $token)->first();

        if ($usuario) {
            $loginModel->update($usuario['id_auth'], [
                'contraseña' => password_hash($password, PASSWORD_DEFAULT),
                'reset_token' => null,
                'reset_expires' => null
            ]);
            // MODIFICADO: Redirige al inicio
            return redirect()->to('/')->with('msg', 'Contraseña actualizada. Inicia sesión.');
        }

        // MODIFICADO: Redirige al inicio
        return redirect()->to('/')->with('error', 'Error al actualizar la contraseña.');
    }

    // --- VISTAS EXISTENTES ---
    public function salir() {
        session()->destroy();
        return redirect()->to('/');
    }

    public function index() {
        $info['footer'] = view('Template/footer');
        $info['header'] = view('Template/header');
        return view('vistas/inicio', $info);
    }

    public function login() { return view('vistas/auth/login'); }
    public function registro() { return view('vistas/auth/registro'); }
    public function password_olvidada() { return view('vistas/auth/password_olvidada'); }

    // Esta vista ya no se llama directo, sino a través de vista_reset
    public function actualizar_password() { 
        return redirect()->to('/'); 
    }
}