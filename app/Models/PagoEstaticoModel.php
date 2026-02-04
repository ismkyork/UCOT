<?php
namespace App\Models;
use CodeIgniter\Model;

class PagoEstaticoModel extends Model {
    protected $table      = 'pago_estatico';
    protected $primaryKey = 'id_pago';
    
    // IMPORTANTE: Si id_pago NO es auto-increment (porque es el nro de referencia manual), 
    // debemos poner esto en false.
    protected $useAutoIncrement = false; 

    protected $allowedFields = [
        'id_pago', 
        'id_cita', 
        'monto', 
        'fecha_pago', 
        'screenshot', 
        'estado_pago', 
        'fecha_confirmacion'
    ];
}