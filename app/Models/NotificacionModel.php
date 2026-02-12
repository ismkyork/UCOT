<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificacionModel extends Model
{
    protected $table      = 'notificaciones';
    protected $primaryKey = 'id_notificacion';

    protected $allowedFields = [
        'id_destinatario', 'titulo', 'mensaje', 'tipo', 'leido', 'created_at'
    ];

    // Obtener las notificaciones de un usuario (ordenadas por fecha)
    public function misNotificaciones($id_auth)
    {
        return $this->where('id_destinatario', $id_auth)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    // Contar cuántas no he leído
    public function contarNoLeidas($id_auth)
    {
        return $this->where('id_destinatario', $id_auth)
                    ->where('leido', 0)
                    ->countAllResults();
    }
}