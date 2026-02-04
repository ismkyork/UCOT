<?php
namespace App\Models;
use CodeIgniter\Model;

class FeedbackModel extends Model
{
    protected $table            = 'feedback';
    protected $primaryKey       = 'id_feedback';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['puntuacion', 'comentario', 'fecha_evaluacion'];
    protected $useTimestamps    = true;
    protected $createdField     = 'fecha_evaluacion';
    protected $updatedField     = '';
}