<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class DriverInfoFactory extends Factory
{
    /**
     * @throws Exception
     */
    public function definition()
    {
        $photo = $this->faker->boolean(50) ?
            '/storage/seeders/drivers/boy'.random_int(1, 15).'.jpg' :
            '/storage/seeders/drivers/girl'.random_int(1, 10).'.jpg';

        return [
            'franchise_id' => 1,
            'license_type' => 'A1',
            'name' => $this->faker->firstName,
            'surname' => $this->faker->lastName,
            'patronymic' => $this->faker->firstName,
            'citizen' => 'Российской Федерации',
            'country_id' => 182,
            'region_id' => 1901,
            'city_id' => 99972,
            'address' => 'Ленинский пр., '.random_int(1, 199),
            'zip_code' => $this->faker->randomNumber(4),
            'passport_serial' => random_int(10, 99).' '.rand(10, 99),
            'passport_number' => random_int(100000000, 900000000),
            'passport_issued_by' => 'МВД по Чувашской республике',
            'passport_when_issued' => Carbon::now()->subYears(5),
            'passport_expiry' => Carbon::now()->addYears(5),
            'photo' => $photo,
            'license_code' => random_int(100000000, 900000000),
            'experience' => random_int(1, 99),
            'birthday' => Carbon::now()->subYear(25)
        ];
    }
}
