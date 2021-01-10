<?php

namespace Database\Factories;

use App\Models\University;
use Illuminate\Database\Eloquent\Factories\Factory;

class UniversityFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = University::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'alpha_two_code' => $this->faker->countryCode,
            'country' => $this->faker->country,
            'name' => $this->faker->words(4, true),
            'domains' => [$this->faker->domainName],
            'ttl' => rand(5, 15)
        ];
    }
}
