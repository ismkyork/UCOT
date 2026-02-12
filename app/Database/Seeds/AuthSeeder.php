<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AuthSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'correo'      => 'profesor@profesor2.com',
            'contraseña'  => password_hash('123456', PASSWORD_DEFAULT),
            'rol'         => 'Profesor',
            'status'      => 'active',
            
        ];

        // Inserta los datos en la tabla 'auth'
        $this->db->table('auth')->insert($data);
    }
}
