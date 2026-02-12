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

    // --- FUNCIÓN PRIVADA PARA ENVIAR CORREO ---
    private function enviarCorreoActivacion($emailDestino, $token) {
        $emailService = \Config\Services::email();
        $emailService->setTo($emailDestino);
        $emailService->setSubject('Activa tu cuenta en UCOT');
        
               $mensaje = "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <style>
                @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
                
                body {
                    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                    line-height: 1.6;
                    color: #1e293b;
                    margin: 0;
                    padding: 0;
                    background-color: #f8fafc;
                }
                
                .container {
                    max-width: 560px;
                    margin: 30px auto;
                    background: white;
                    border-radius: 32px;
                    box-shadow: 0 20px 40px -10px rgba(0,0,0,0.08);
                    overflow: hidden;
                }
                
                .header {
                    background: linear-gradient(145deg, #0d6efd, #0b5ed7);
                    padding: 36px 32px 30px;
                    text-align: center;
                }
                
                .logo-icon {
                    font-size: 48px;
                    display: inline-block;
                    background: rgba(255,255,255,0.15);
                    width: 80px;
                    height: 80px;
                    line-height: 80px;
                    border-radius: 30px;
                    margin-bottom: 16px;
                    backdrop-filter: blur(4px);
                }
                
                .header h1 {
                    color: white;
                    margin: 0;
                    font-weight: 700;
                    font-size: 28px;
                    letter-spacing: -0.5px;
                    text-shadow: 0 2px 4px rgba(0,0,0,0.1);
                }
                
                .header p {
                    color: rgba(255,255,255,0.9);
                    margin: 8px 0 0;
                    font-size: 16px;
                    font-weight: 400;
                }
                
                .content {
                    padding: 40px 32px;
                    background: white;
                }
                
                .welcome-message {
                    font-size: 18px;
                    font-weight: 500;
                    color: #0d6efd;
                    margin-bottom: 16px;
                    display: flex;
                    align-items: center;
                    gap: 8px;
                }
                
                .greeting {
                    font-size: 24px;
                    font-weight: 700;
                    color: #0a0f1c;
                    margin-bottom: 20px;
                    line-height: 1.3;
                }
                
                .highlight {
                    background: linear-gradient(120deg, #fef9e7 0%, #fef9e7 40%, #fff 80%);
                    padding: 24px;
                    border-radius: 24px;
                    margin: 24px 0;
                    border-left: 5px solid #0d6efd;
                }
                
                .btn {
                    display: inline-block;
                    background: linear-gradient(145deg, #0d6efd, #0a58ca);
                    color: white !important;
                    font-weight: 600;
                    padding: 16px 36px;
                    border-radius: 50px;
                    text-decoration: none;
                    font-size: 17px;
                    letter-spacing: 0.3px;
                    margin: 16px 0 8px;
                    box-shadow: 0 8px 20px rgba(13,110,253,0.25);
                    transition: all 0.2s ease;
                    border: 1px solid rgba(255,255,255,0.1);
                }
                
                .btn:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 12px 28px rgba(13,110,253,0.35);
                }
                
                .features {
                    display: flex;
                    justify-content: space-between;
                    margin: 40px 0 20px;
                    padding-top: 20px;
                    border-top: 2px solid #f1f5f9;
                }
                
                .feature-item {
                    text-align: center;
                    flex: 1;
                }
                
                .feature-emoji {
                    font-size: 28px;
                    margin-bottom: 8px;
                    display: block;
                }
                
                .feature-text {
                    font-size: 13px;
                    font-weight: 600;
                    color: #475569;
                }
                
                .footer {
                    background: #f8fafc;
                    padding: 32px;
                    text-align: center;
                    border-top: 1px solid #e2e8f0;
                }
                
                .footer p {
                    margin: 6px 0;
                    color: #64748b;
                    font-size: 14px;
                }
                
                .footer a {
                    color: #0d6efd;
                    text-decoration: none;
                    font-weight: 500;
                }
                
                .small-note {
                    background: white;
                    padding: 16px 20px;
                    border-radius: 16px;
                    font-size: 13px;
                    color: #64748b;
                    margin-top: 24px;
                    border: 1px dashed #cbd5e1;
                }
                
                .emoji-big {
                    font-size: 22px;
                    vertical-align: middle;
                    margin-right: 6px;
                }
                
                @media only screen and (max-width: 600px) {
                    .container { margin: 16px; border-radius: 24px; }
                    .content { padding: 28px 20px; }
                    .features { flex-direction: column; gap: 16px; }
                }
            </style>
        </head>
        <body>
            <div class='container'>
                
                <!-- HEADER CON GRADIENTE Y EMBLEMA -->
                <div class='header'>
                    <div class='logo-icon'>🎓✨</div>
                    <h1>¡Bienvenido a UCOT!</h1>
                    <p>Donde el conocimiento encuentra su rumbo</p>
                </div>
                
                <!-- CONTENIDO PRINCIPAL -->
                <div class='content'>
                    
                    <div class='welcome-message'>
                        <span class='emoji-big'>🤝</span> Nos alegra tenerte aquí
                    </div>
                    
                    <div class='greeting'>
                        Hola, <span style='color: #0d6efd; border-bottom: 3px solid #ffd166; padding-bottom: 2px;'>futuro profesional</span> ✨
                    </div>
                    
                    <p style='font-size: 16px; color: #334155; margin-bottom: 24px;'>
                        Estás a solo un clic de formar parte de la comunidad educativa 
                        más vibrante de Venezuela. Prepara tus conocimientos y 
                        conecta con estudiantes apasionados.
                    </p>
                    
                    <!-- TARJETA DE ACTIVACIÓN DESTACADA -->
                    <div class='highlight'>
                        <div style='display: flex; align-items: center; gap: 12px; margin-bottom: 16px;'>
                            <span style='font-size: 32px;'>🔐</span>
                            <span style='font-weight: 700; font-size: 18px; color: #0a0f1c;'>Activa tu cuenta en segundos</span>
                        </div>
                        
                        <p style='margin-bottom: 20px; color: #334155;'>
                            Haz clic en el botón de abajo para verificar tu correo 
                            y comenzar tu viaje como parte de UCOT. 
                        </p>
                        
                        <!-- BOTÓN PRINCIPAL - MUY DESTACADO -->
                        <div style='text-align: center;'>
                            <a href='".base_url("auth/activar/$token")."' class='btn'>
                                ✅ ACTIVAR MI CUENTA AHORA
                            </a>
                            <p style='font-size: 13px; color: #5d6d7e; margin-top: 12px; letter-spacing: 0.3px;'>
                                ⚡ El enlace expirará en 24 horas por seguridad
                            </p>
                        </div>
                    </div>
                    
                    <!-- FEATURES: QUÉ ESPERAR DE UCOT -->
                    <div class='features'>
                        <div class='feature-item'>
                            <span class='feature-emoji'>📚</span>
                            <span class='feature-text'>Clases personalizadas</span>
                        </div>
                        <div class='feature-item'>
                            <span class='feature-emoji'>💰</span>
                            <span class='feature-text'>Alta calidad y perfección</span>
                        </div>
                        <div class='feature-item'>
                            <span class='feature-emoji'>⏱️</span>
                            <span class='feature-text'>Horarios flexibles</span>
                        </div>
                    </div>
                    
                    <!-- MENSAJE PERSONAL TIPO CARTA -->
                    <div style='background: #fef2f2; padding: 20px 24px; border-radius: 20px; margin: 20px 0 10px;'>
                        <p style='margin: 0; font-style: italic; color: #4b5563;'>
                            <span style='font-size: 20px; color: #ef4444;'>❤️</span> 
                            “La educación es el arma más poderosa para cambiar el mundo. 
                            <span style='color: #0d6efd; font-weight: 600;'>UCOT</span> es tu plataforma para hacerlo realidad.”
                        </p>
                        <p style='margin: 16px 0 0; font-weight: 600; color: #1e293b;'>
                            — Equipo UCOT
                        </p>
                    </div>
                    
                    <!-- NOTA DE SEGURIDAD (con emoji de escudo) -->
                    <div class='small-note'>
                        <span style='font-size: 18px; margin-right: 8px;'>🛡️</span> 
                        <strong>¿No solicitaste este registro?</strong> Si no creaste una cuenta en UCOT, 
                        simplemente ignora este correo. Tu correo está seguro con nosotros.
                    </div>
                    
                </div>
                
                <!-- FOOTER ELEGANTE -->
                <div class='footer'>
                    <p style='font-weight: 600; color: #1e293b;'>UCOT · Un Cambio Oportuno en Ti</p>
                    <p>📍 Caracas, Venezuela · 📧 ucot2025@gmail.com</p>
                    <p style='margin-top: 16px; font-size: 12px; opacity: 0.7;'>
                        © 2025 UCOT. Todos los derechos reservados.
                        <br>

                    </p>
                </div>
                
            </div>
        </body>
        </html>
        ";


        $emailService->setMessage($mensaje);
        
        if (!$emailService->send()) {
    // Esto detendrá la página y te mostrará el error técnico en la cara
    die($emailService->printDebugger(['headers'])); 
}
    }

    // --- ACTIVAR CUENTA (Link del correo) ---
    public function activar($token = null) {
        if (!$token) return redirect()->to('/'); // <--- MODIFICADO: Al inicio si no hay token

        $loginModel = new LoginModel();
        $usuario = $loginModel->where('activation_token', $token)->first();

        if ($usuario) {
            $loginModel->update($usuario['id_auth'], [
                'status' => 'activo',
                'activation_token' => null
            ]);
            // MODIFICADO: Redirige al inicio
            return redirect()->to('/')->with('msg', '¡Cuenta activada correctamente! Ya puedes iniciar sesión.');
        } else {
            // MODIFICADO: Corregido error de sintaxis y redirige al inicio
            return redirect()->to('/')->with('error', 'El enlace de activación es inválido o ya fue usado.');
        }
    }

    // --- RECUPERACIÓN DE CONTRASEÑA ---

    // 1. Procesar envío de correo de recuperación
    public function enviar_recovery() {
        $loginModel = new LoginModel();
        $emailDestino = $this->request->getPost('correo');

        $usuario = $loginModel->where('correo', $emailDestino)->first();

        if ($usuario) {
            $token = bin2hex(random_bytes(32));
            $expires = date("Y-m-d H:i:s", time() + 3600); // 1 hora

            $loginModel->update($usuario['id_auth'], [
                'reset_token' => $token,
                'reset_expires' => $expires
            ]);

            $emailService = \Config\Services::email();
            $emailService->setTo($emailDestino);
            $emailService->setSubject('Recuperar Contraseña - UCOT');

                      $mensaje = "
            <div style='font-family: Arial, sans-serif; max-width: 500px; margin: 0 auto; background: white; border-radius: 20px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); overflow: hidden;'>

                <!-- HEADER SIMPLE -->
                <div style='background: #2563eb; padding: 25px 20px; text-align: center;'>
                    <div style='font-size: 45px; margin-bottom: 5px;'>🔐🛡️</div>
                    <h2 style='color: white; margin: 0; font-size: 22px;'>¿Olvidaste tu contraseña?</h2>
                    <p style='color: rgba(255,255,255,0.9); margin: 5px 0 0; font-size: 14px;'>Tranquilo, te ayudamos</p>
                </div>
                
                <!-- CONTENIDO -->
                <div style='padding: 30px 25px; background: white;'>
                    
                    <p style='font-size: 16px; color: #1e293b; margin-top: 0;'>
                        <span style='font-size: 20px;'>👋</span> Hola, recibimos tu solicitud para restablecer la contraseña.
                    </p>
                    
                    <!-- BOTÓN -->
                    <div style='background: #f0f9ff; padding: 25px; border-radius: 16px; text-align: center; margin: 20px 0;'>
                        <a href='" . base_url("auth/reset/$token") . "' 
                        style='background: #2563eb; color: white; padding: 14px 30px; border-radius: 50px; 
                                text-decoration: none; font-weight: bold; display: inline-block; font-size: 16px;'>
                            🔄 Crear nueva contraseña
                        </a>
                        
                        <!-- TIEMPO -->
                        <div style='background: #fff4e6; border-radius: 12px; padding: 12px; margin-top: 20px; font-size: 13px;'>
                            <span style='font-size: 18px;'>⏳</span> 
                            <span style='color: #9a3412; font-weight: bold;'>Vence en 1 hora</span> — por tu seguridad
                        </div>
                    </div>
                    
                    <!-- TIPS RÁPIDOS -->
                    <div style='background: #f8fafc; border-left: 4px solid #2563eb; padding: 15px; margin: 20px 0; font-size: 13px;'>
                        <p style='margin: 0 0 8px; font-weight: bold; color: #0a0f1c;'>🔒 Contraseña segura:</p>
                        <p style='margin: 5px 0; color: #475569;'>✓ 8+ caracteres, mezcla letras, números y símbolos</p>
                        <p style='margin: 5px 0; color: #475569;'>✗ Evita fechas, nombres o '123456'</p>
                    </div>
                    
                    <!-- MENSAJE DE APOYO -->
                    <div style='background: #ecfdf5; border-radius: 12px; padding: 15px; margin: 20px 0 10px;'>
                        <p style='margin: 0; color: #065f46; font-style: italic; display: flex; align-items: center; gap: 8px;'>
                            <span style='font-size: 22px;'>🌿</span> 
                            <span>¿No solicitaste esto? Ignora el mensaje, tu cuenta sigue segura.</span>
                        </p>
                    </div>
                    
                    <!-- ENLACE TEXTO (por si falla el botón) -->
                    <div style='background: #f1f5f9; padding: 12px; border-radius: 10px; margin-top: 20px; font-size: 12px; word-break: break-all;'>
                        <span style='color: #475569;'>🔗 Enlace directo:</span><br>
                        <span style='color: #2563eb;'>" . base_url("auth/reset/$token") . "</span>
                    </div>
                    
                </div>
                
                <!-- FOOTER -->
                <div style='background: #f8fafc; padding: 20px; text-align: center; border-top: 1px solid #e2e8f0;'>
                    <p style='margin: 0; color: #64748b; font-size: 12px;'>
                        UCOT · Ayuda: ucot2025@gmail.com
                    </p>
                    <p style='margin: 8px 0 0; color: #94a3b8; font-size: 11px;'>
                        © 2025 · Seguridad y confianza
                    </p>
                </div>
                
            </div>";


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