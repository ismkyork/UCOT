<?php

namespace App\Controllers;

use App\Models\LoginModel;
use App\Models\AlumnoModel;
use App\Models\ProfesorModel;

class Configuracion extends BaseController
{
    public function index()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to(base_url('login'));
        }

        return view('vistas/configuracion');
    }

    public function actualizar()
    {
        $session = session();
        $id_auth = $session->get('id_auth');
        $rol = $session->get('rol');

        $nombre = $this->request->getPost('nombre');
        $apellido = $this->request->getPost('apellido');
        $password = $this->request->getPost('password');

        // 1. Actualizar Perfil (Nombre/Apellido)
        if ($rol == 'Profesor') {
            $model = new ProfesorModel();
            $model->where('id_auth', $id_auth)->set([
                'nombre_profesor' => $nombre,
                'apellido_profesor' => $apellido
            ])->update();
        } else {
            $model = new AlumnoModel();
            $model->where('id_auth', $id_auth)->set([
                'name' => $nombre,
                'apellido' => $apellido
            ])->update();
        }

        // 2. Actualizar Contraseña (si escribió una)
        if (!empty($password)) {
            $loginModel = new LoginModel();
            $loginModel->update($id_auth, [
                'password' => password_hash($password, PASSWORD_DEFAULT)
            ]);
        }

        // Actualizar el nombre en la sesión para que el sidebar cambie al instante
        $session->set('nombre', $nombre);

        return redirect()->back()->with('msg', 'Datos actualizados correctamente');
    }
}