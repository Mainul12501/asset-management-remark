<?php

namespace Database\Factories;

use App\Models\Thana;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class ThanaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Thana::class;

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
            'district_id' => \App\Models\District::factory(),
        ];
    }
}
