<?php
namespace App\Models;
use CodeIgniter\Model;

class FeedbackModel extends Model
{
    protected $table            = 'feedback';
    protected $primaryKey       = 'id_feedback';
    protected $allowedFields    = ['puntuacion', 'comentario', 'fecha_evaluacion','id_profesor',
        'id_estudiante'];
    protected $useAutoIncrement = true;
    protected $useTimestamps    = false;
    protected $createdField     = 'fecha_evaluacion';
   
   
    //para obtener los comentarios de un profesor específico, incluyendo el nombre del estudiante que hizo el comentario

public function obtenerComentariosPorProfesor($id_profesor)
    {
        return $this->select('feedback.*, perfiles_estudiantes.nombre_estudiante, perfiles_estudiantes.apellido_estudiante')
                    ->join('perfiles_estudiantes', 'perfiles_estudiantes.id_estudiante = feedback.id_estudiante')
                    ->where('feedback.id_profesor', $id_profesor)
                    ->orderBy('feedback.fecha_evaluacion', 'DESC')
                    ->findAll();
    }


}