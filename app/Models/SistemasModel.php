<?php namespace App\Models;

use CodeIgniter\Model;

class SistemasModel extends Model {
    protected $table = 'sistemas_clase';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nombre']; // Ajusta según tus columnas
}