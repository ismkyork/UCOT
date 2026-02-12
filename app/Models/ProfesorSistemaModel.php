<?php namespace App\Models;

use CodeIgniter\Model;

class ProfesorSistemasModel extends Model {
    protected $table = 'profesor_sistemas_vinculo';
    protected $primaryKey = 'id_vinculo';
    protected $allowedFields = ['id_profesor', 'id_sistema'];
}