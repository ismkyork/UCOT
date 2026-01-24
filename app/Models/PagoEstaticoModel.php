<?php
namespace App\Models;
use CodeIgniter\Model;

class PagoEstaticoModel extends Model {
    protected $table      = 'pago_estatico';
    protected $primaryKey = 'id_pago';
    // Definimos los campos que se pueden insertar
    protected $allowedFields = [
        'id_cita', 
        'monto', 
        'fecha_pago', 
        'screenshot', 
        'estado_pago', 
        'fecha_confirmacion'
    ];
}