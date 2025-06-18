<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin
        User::create([
            'role_id' => Role::first()->id,
            'username' => 'admin',
            'name' => 'Administrador do Sistema',
            'email' => 'admin@admin.com',
            'email_verified_at' => now(),
            'password' => Hash::make('hcop*hroot'),
            'document' => '000.000.000-00',
        ]);

        // Create Operators
        $operators = [
            [
                'username' => 'silvajsj',
                'name' => 'João da Silva Junior',
                'document' => '4570416', 
                'phone' => '(11) 91234-5678',
                'email' => 'joao.silva@exemplo.com',
            ],
            [
                'username' => 'mariaomom',
                'name' => 'Maria Oliveira Martins',
                'document' => '1234560', // Identificação militar
                'phone' => '(21) 99876-5432',
                'email' => 'mariamom@fab.mil.br',
            ],
            [
                'username' => 'carloscosta',
                'name' => 'Carlos Costa de Oliveira',
                'document' => '987.654.321-00', // CPF
                'phone' => '(31) 98765-4321',
                'email' => 'carlos.costa@exemplo.com',
            ],
        ];

        foreach ($operators as $operator) {
            User::create(array_merge($operator, [
                'role_id' => 2,
                'email_verified_at' => now(),
                'password' => Hash::make('senha*padrao'),
            ]));
        }
    }
}
