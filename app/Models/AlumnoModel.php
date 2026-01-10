<?php
namespace App\Models;
use CodeIgniter\Model;

class AlumnoModel extends Model {
    protected $table      = 'perfiles_estudiantes';
    protected $primaryKey = 'id_estudiante';
    protected $allowedFields = ['id_auth', 'name', 'apellido'];

  
    public function getEstudianteCompleto($id) {
        return $this->select('perfiles_estudiantes.*, auth.email')
                    ->join('auth', 'auth.id_auth = perfiles_estudiantes.id_auth')
                    ->where('id_estudiante', $id)
                    ->first();
    }
}