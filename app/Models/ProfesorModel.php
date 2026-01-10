<?php namespace App\Models;
use CodeIgniter\Model;

    class ProfesorModel extends Model {
     protected $table = 'perfil_profesor';
     protected $primaryKey = 'id_profesor';
     protected $allowedFields = ['id_auth', 'nombre_profesor', 'apellido_profesor'];
    }