<?php 

namespace App\Models;

use CodeIgniter\Model;

class ProfesorModel extends Model {
    protected $table      = 'perfil_profesor';
    protected $primaryKey = 'id_profesor';
        protected $allowedFields = [
        'id_auth', 
        'nombre_profesor', 
        'apellido_profesor', 
        'precio_clase', 
        'foto'
    ];

    public function getProfesoresConEmail() {
        return $this->select('perfil_profesor.*, auth.correo, auth.status')
                    ->join('auth', 'auth.id_auth = perfil_profesor.id_auth')
                    ->findAll();
    }
}


