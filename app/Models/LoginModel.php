<?php namespace App\Models;
use CodeIgniter\Model;

class LoginModel extends Model {
    protected $table      = 'auth';
    protected $primaryKey = 'id_auth';
    protected $allowedFields = [
        'correo', 
        'contraseña', 
        'rol',
        'status', 
        'activation_token', 
        'reset_token', 
        'reset_expires'
    ];
    protected $returnType = 'array';

    // Función para que el Admin vea todos los usuarios con su perfil
    public function getUsuariosCompletos() {
        return $this->select('auth.id_auth, auth.correo, auth.rol, auth.status, p.nombre_profesor, p.apellido_profesor, e.nombre_estudiante')
                    ->join('perfil_profesor p', 'p.id_auth = auth.id_auth', 'left')
                    ->join('perfiles_estudiantes e', 'e.id_auth = auth.id_auth', 'left')
                    ->findAll();
    }
}