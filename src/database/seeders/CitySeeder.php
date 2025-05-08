<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use App\Models\State;
use App\Models\City;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $states = State::all();
        $total = $states->count();
        $count = 1;

        foreach ($states as $state) {
            $this->command->info("[$count/$total] Importando cidades do estado: {$state->name}");

            $response = Http::get("https://servicodados.ibge.gov.br/api/v1/localidades/estados/{$state->acronym}/municipios");

            if ($response->failed()) {
                $this->command->error("❌ Falha ao buscar cidades de {$state->name}");
                sleep(1); // aguarda 1 segundo e continua
                continue;
            }

            $cities = $response->json();

            foreach ($cities as $cityData) {
                City::create([
                    'state_id' => $state->id,
                    'name' => $cityData['nome'],
                ]);
            }

            // sleep(1); // pausa de 1 segundo entre cada estado para evitar 429
            $count++;
        }

        $this->command->info('✅ Todas as cidades foram importadas com sucesso!');
    }
}