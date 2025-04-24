<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Address;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Guest>
 */
class GuestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'document' => $this->faker->numerify('###.###.###-##'),
            'phone' => $this->faker->phoneNumber,
            'email' => $this->faker->safeEmail,
            'address_id' => Address::inRandomOrder()->first()?->id ?? Address::factory(),
        ];
    }
}
