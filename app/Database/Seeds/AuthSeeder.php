<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AuthSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'email'    => 'admin@prueba.com',
                'password' => password_hash('123456', PASSWORD_DEFAULT),
                'rol'      => 'admin',
            ],
            [
                'email'    => 'cliente@prueba.com',
                'password' => password_hash('123456', PASSWORD_DEFAULT),
                'rol'      => 'cliente',
            ],
        ];

        $this->db->table('auth')->insertBatch($data);
    }
}