<?php

declare(strict_types=1);

namespace Database\Seeders;

use Carbon\Carbon;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;
use Src\Models\Driver\DriverInfo;
use Src\Models\Driver\DriverLicenseType;


/**
 * Class DriverInfoTableSeeder
 */
class DriverInfoTableSeeder extends Seeder
{

    /**
     * @param  Faker  $faker
     * @throws Exception
     */
    public function run(Faker $faker): void
    {
        for ($x = 0; $x < 15; $x++) {
            $photo = $faker->boolean(50) ?
                '/storage/seeders/drivers/boy'.random_int(1, 15).'.jpg' :
                '/storage/seeders/drivers/girl'.random_int(1, 10).'.jpg';

            $licenseTypes = DriverLicenseType::get();

            $info = DriverInfo::create(
                [
                    'franchise_id' => 1,
                    'name' => $faker->firstName,
                    'surname' => $faker->lastName,
                    'patronymic' => $faker->firstName,
                    'email' => $faker->email,
                    'citizen' => 'Российской Федерации',
                    'address' => 'Ленинский пр., '.random_int(1, 199),
                    'id_kis_art' => '',
                    'passport_serial' => $faker->randomNumber(2).' '.$faker->randomNumber(2),
                    'passport_number' => $faker->randomNumber(9),
                    'passport_issued_by' => 'МВД по Чувашской республике',
                    'passport_when_issued' => Carbon::now()->subYears(5),
                    'photo' => $photo,
                    'license_code' => $faker->randomNumber(9),
                    'license_date' => Carbon::now()->subYears(5),
                    'license_expiry' => Carbon::now()->addYears(5),
                    'experience' => $faker->randomNumber(1),
                    'birthday' => Carbon::now()->subYears(25)
                ]
            );

            foreach ($licenseTypes as $type) {
                if (rand(0, 1)) {
                    $info->license_types()->attach($type->driver_license_type_id);
                }
            }
        }
    }
}
