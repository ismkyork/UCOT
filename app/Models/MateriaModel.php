<?php

namespace App\Models;

use CodeIgniter\Model;

class MateriaModel extends Model
{
    protected $table            = 'materias';
    protected $primaryKey       = 'id_materia';
    protected $allowedFields    = ['nombre_materia'];

    /**
     * Obtiene las materias que dicta un profesor específico
     */
    public function obtenerPorProfesor($idProfesor)
    {
        return $this->db->table('materias_profesor mp')
            ->select('m.id_materia, m.nombre_materia')
            ->join('materias m', 'm.id_materia = mp.id_materia')
            ->where('mp.id_profesor', $idProfesor)
            ->get()->getResultArray();
    }

    /**
     * Guarda la relación entre profesor y materias
     */
    public function actualizarMateriasProfesor($idProfesor, $materiasIds)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('materias_profesor');

        // Iniciamos transacción
        $db->transStart();

        // 1. Borramos lo anterior
        $builder->where('id_profesor', $idProfesor)->delete();

        // 2. Insertamos lo nuevo si hay selección
        if (!empty($materiasIds)) {
            $data = [];
            foreach ($materiasIds as $idMat) {
                $data[] = [
                    'id_profesor' => $idProfesor,
                    'id_materia'  => $idMat
                ];
            }
            $builder->insertBatch($data);
        }

        $db->transComplete();
        return $db->transStatus();
    }
}