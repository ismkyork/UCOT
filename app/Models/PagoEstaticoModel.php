<?php

namespace App\Models;

use CodeIgniter\Model;

class PagoEstaticoModel extends Model 
{
    protected $table      = 'pagos';
    protected $primaryKey = 'id_pago';
    protected $useAutoIncrement = false; 
    
    protected $returnType     = 'array';

    protected $allowedFields = [
        'id_pago', 
        'id_cita', 
        'monto', 
        'fecha_pago', 
        'screenshot', 
        'estado_pago', 
        'fecha_confirmacion'
    ];
    protected $useTimestamps = false; 
}