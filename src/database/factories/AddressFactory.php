<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\State;
use App\Models\City;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $state = State::inRandomOrder()->first()?->id;

        return [
            'postal_code' => $this->faker->postcode,
            'state_id' => $state,
            'city_id' => City::where('state_id', $state)->inRandomOrder()->first()?->id,
            'street' => $this->faker->streetName,
            'number' => $this->faker->buildingNumber,
            'neighborhood' => $this->faker->citySuffix,
            'complement' => $this->faker->secondaryAddress,
        ];
    }
}
