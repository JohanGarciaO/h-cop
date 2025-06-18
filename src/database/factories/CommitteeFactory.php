<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Committee;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Committee>
 */
class CommitteeFactory extends Factory
{
    protected $model = Committee::class;

    public function definition(): array
    {
        $userId = User::first()->id;

        return [
            'name' => 'Comitiva ' . $this->faker->unique()->company,
            'description' => 'Comitiva oficial da empresa ' . $this->faker->company,
            'created_by' => $userId,
            'updated_by' => $userId,
        ];
    }
}
