<?php

namespace Modules\Wallet\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PricingPlanFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Wallet\Models\PricingPlan::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement(['Gold', 'Silver', 'Bronze', 'Platinum']),
            'price' => $this->faker->numberBetween(5, 100),
            'description' => $this->faker->paragraph,
            //'features' => $this->features(),
            'is_best' => false,
            'enabled' => true,
        ];
    }

    public function best(): PricingPlanFactory
    {
        return $this->state([
            'is_best' => true,
        ]);
    }

    public function disabled(): PricingPlanFactory
    {
        return $this->state([
            'enabled' => false,
        ]);
    }

    private function features(): array
    {
        return [];
    }
}
