<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Housekeeper;

class HousekeepersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create('pt_BR');

        foreach (range(1, 10) as $i) {
            Housekeeper::create([
                'name' => $faker->name,
                'document' => $faker->unique()->cpf(false), // ou $faker->numerify('###.###.###-##')
                'phone' => $faker->boolean(70) ? $faker->phoneNumber : null, // 70% chance de ter telefone
            ]);
        }
    }
}
