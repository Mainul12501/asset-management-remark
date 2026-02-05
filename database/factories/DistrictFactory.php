<?php

namespace Database\Factories;

use App\Models\District;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class DistrictFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = District::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique->name(),
            'boundary_polygon' => "{$this->faker->latitude()},{$this->faker->longitude()}",
            'division_id' => \App\Models\Division::factory(),
        ];
    }
}
