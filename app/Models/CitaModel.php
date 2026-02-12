<?php

namespace App\Models;

use CodeIgniter\Model;

class CitaModel extends Model {
    protected $table      = 'citas';
    protected $primaryKey = 'id_cita';

    // Se incluyen 'materia' y 'sistema' que vienen del formulario del alumno
    protected $allowedFields = [
        'id_alumno', 
        'id_profesor', 
        'id_horario', 
        'fecha_hora_inicio', 
        'duracion_min', 
        'materia', 
        'sistema', // <-- Campo para la plataforma (Zoom, Meet, etc.)
        'estado_cita'
    ];

    /**
     * Obtiene las citas con los nombres reales de los perfiles
     * Incluimos también el campo sistema en el select
     */
    public function getCitasDetalladas() {
        return $this->select('citas.*, est.nombre_estudiante, prof.nombre_profesor, h.hora_inicio, h.hora_fin')
            ->join('perfiles_estudiantes est', 'est.id_estudiante = citas.id_alumno')
            ->join('perfil_profesor prof', 'prof.id_profesor = citas.id_profesor')
            ->join('horarios h', 'h.id_horario = citas.id_horario', 'left')
            ->findAll();
    }

    // Actualizamos las reglas de validación para incluir el nuevo campo
    protected $validationRules = [
        'id_alumno'         => 'required|numeric',
        'id_profesor'       => 'required|numeric',
        'id_horario'        => 'required|numeric',
        'fecha_hora_inicio' => 'required', 
        'duracion_min'      => 'required|numeric',
        'materia'           => 'required|max_length[100]',
        'sistema'           => 'required|max_length[50]', // <-- Validación obligatoria
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}