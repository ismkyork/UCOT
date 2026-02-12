<?php

namespace App\Models;
use CodeIgniter\Model;

class HorarioModel extends Model
{
    protected $table      = 'horarios';
    protected $primaryKey = 'id_horario';

    // Estos son los campos permitidos para guardar/editar (Mass Assignment)
    protected $allowedFields = [
        'id_profesor', 
        'fecha', 
        'hora_inicio', 
        'hora_fin', 
        'estado',
        'cupos_disponibles',
        'cupos_totales',
        'id_sistema', // <--- NUEVO: Para guardar la modalidad (Zoom, Meet...)
        'id_materia'  // <--- NUEVO: Para guardar el tema fijo (si aplica)
    ];

    // Función auxiliar para actualizar el estado automáticamente según los cupos
    public function actualizarEstadoPorCupos($id_horario)
    {
        $horario = $this->find($id_horario);
        if ($horario) {
            // Si quedan cupos es "Disponible", si es 0 es "Reservado"
            $nuevoEstado = ($horario['cupos_disponibles'] > 0) ? 'Disponible' : 'Reservado';
            
            // Usamos save o update
            $this->update($id_horario, ['estado' => $nuevoEstado]);
        }
    }
}