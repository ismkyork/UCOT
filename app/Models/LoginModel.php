<?php namespace App\Models;
use CodeIgniter\Model;

class LoginModel extends Model {
    protected $table      = 'auth';
    protected $primaryKey = 'id_auth';
    protected $allowedFields = ['email', 'password', 'rol'];
    protected $returnType = 'array';
}