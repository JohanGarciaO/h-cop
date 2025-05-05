<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use App\Models\State;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $states = Http::get('https://servicodados.ibge.gov.br/api/v1/localidades/estados')->json();

        foreach ($states as $state) {
            State::create([
                'id' => $state['id'],
                'name' => $state['nome'],
                'acronym' => $state['sigla'],
            ]);
        }
    }
}