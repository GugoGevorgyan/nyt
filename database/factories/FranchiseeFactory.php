<?php

declare(strict_types=1);

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class FranchiseeFactory extends Factory
{
    public function definition()
    {
        return [
            'owner_name' => $this->faker->name,
            'owner_email' => $this->faker->email,
            'name' => $this->faker->streetName,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s.u'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s.u'),
        ];
    }
}
