<?php

namespace App\Models;

use CodeIgniter\Model;

class MateriaProfesorModel extends Model
{
    protected $table            = 'materias_profesor';
    protected $primaryKey       = 'id_vinculo';
    protected $allowedFields    = ['id_profesor', 'id_materia'];

    // Función para obtener los IDs de las materias de un profesor (para los checks)
    public function getMateriasIdsByProfesor($id_profesor)
    {
        $res = $this->where('id_profesor', $id_profesor)->findAll();
        return array_column($res, 'id_materia');
    }
}