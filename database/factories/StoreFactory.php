<?php

namespace Database\Factories;

use App\Models\Store;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class StoreFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Store::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(10),
            'code' => $this->faker->unique->text(255),
            'total_area_sqft' => $this->faker->randomNumber(1),
            'address' => $this->faker->text(),
            'area' => $this->faker->text(255),
            'postal_code' => $this->faker->text(255),
            'latitude' => $this->faker->randomNumber(1),
            'longitude' => $this->faker->randomNumber(1),
            'monthly_rent' => $this->faker->randomNumber(2),
            'per_sqr_feet_rent' => $this->faker->randomNumber(2),
            'store_layout_img' => $this->faker->text(),
            'store_layout_pdf' => $this->faker->text(),
            'contact_persion' => $this->faker->text(255),
            'shop_official_mobile' => $this->faker->text(255),
            'shop_official_email' => $this->faker->text(255),
            'status' => $this->faker->numberBetween(0, 127),
            'opened_date' => $this->faker->text(255),
            'store_code' => $this->faker->text(255),
            'store_manager_id' => \App\Models\User::factory(),
            'division_id' => \App\Models\Division::factory(),
            'district_id' => \App\Models\District::factory(),
            'thana_id' => \App\Models\Thana::factory(),
        ];
    }
}
