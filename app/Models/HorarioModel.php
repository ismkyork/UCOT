<?php

namespace App\Models;
use CodeIgniter\Model;

class HorarioModel extends Model
{
    protected $table      = 'horarios';
    protected $primaryKey = 'id_horario';

    // Estos son los campos de la tabla SQL que se van a editar
    protected $allowedFields = [
        'id_profesor', 
        'week_day', 
        'hora_inicio', 
        'hora_fin', 
        'estado'
    ];
}