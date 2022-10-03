<?php

declare(strict_types=1);

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class SystemWorkerFactory extends Factory
{
    public function definition()
    {
        return [
            'franchise_id' => $this->faker->numberBetween($min = 1, $max = 5),
            'name' => $this->faker->name,
            'nickname' => $this->faker->userName,
            'email' => $this->faker->email,
            'password' => Hash::make('secret'),
            'phone' => 89177856936,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s.u'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s.u'),
        ];
    }
}
