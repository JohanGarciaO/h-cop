<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Address;
use App\Enums\Gender;
use App\Models\Committee;
use App\Models\User;

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
        $userId = User::first()->id;

        return [
            'name' => $this->faker->name,
            'document' => $this->faker->numerify('###.###.###-##'),
            'phone' => $this->faker->phoneNumber,
            'email' => $this->faker->safeEmail,
            'address_id' => Address::inRandomOrder()->first()?->id ?? Address::factory(),
            'gender' => $this->faker->randomElement(Gender::getValues()),
            'committee_id' => Committee::inRandomOrder()->first()?->id ?? Committee::factory(),
            'created_by' => $userId,
            'updated_by' => $userId,
        ];
    }
}
