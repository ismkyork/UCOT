<?php
namespace App\Models;
use CodeIgniter\Model;

class CitaModel extends Model {
    protected $table      = 'citas';
    protected $primaryKey = 'id_cita';
    protected $allowedFields = [
        'id_alumno', 'id_profesor', 'fecha_hora_inicio', 
        'duracion_min', 'materia', 'estado_cita'
    ];

    public function getCitasDetalladas() {
      return $this->select('citas.*, alumno.email as nombre_alumno, profe.email as nombre_profe')
            ->join('auth as alumno', 'alumno.id_auth = citas.id_alumno')
            ->join('auth as profe', 'profe.id_auth = citas.id_profesor')
            ->findAll();
    }

  protected $validationRules = [
    'fecha_hora_inicio' => 'required', // O 'required|valid_date' si usas formato standard
    'duracion_min'      => 'required|numeric',
    'materia'           => 'required|max_length[255]',
];
 
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}