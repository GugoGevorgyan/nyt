<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class AdminCorporatesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        DB::table('admin_corporates')->delete();

        DB::table('admin_corporates')->insert(array(
            0 =>
                array(
                    'admin_corporate_id' => 1,
                    'company_id' => 1,
                    'created_at' => '2021-09-24 18:27:22.786336',
                    'email' => 'hammes.lamar@hotmail.com',
                    'franchise_id' => 1,
                    'name' => 'Colin',
                    'password' => '$argon2id$v=19$m=2048,t=4,p=4$SFgyLy9IcDd4QmpwZkZKVA$5eKNi/guX4kSnAzGvcCQgGK6Qs7qq/kLlRZecQ3xU3I',
                    'patronymic' => 'Milton',
                    'phone' => '1-995-680-8228',
                    'remember_token' => null,
                    'surname' => 'O\'Hara',
                    'updated_at' => '2021-09-24 18:27:22.786197',
                ),
            1 =>
                array(
                    'admin_corporate_id' => 2,
                    'company_id' => 2,
                    'created_at' => '2021-09-24 18:27:22.799531',
                    'email' => 'briana.oberbrunner@kuhlman.com',
                    'franchise_id' => 1,
                    'name' => 'Orrin',
                    'password' => '$argon2id$v=19$m=2048,t=4,p=4$NHhYeWgwZ3JRUDY1dW9WVw$zmoLM/LQupGnLu4G3+uXqcQ0mIhJR5i8dH/PHlSZ5jc',
                    'patronymic' => 'Ubaldo',
                    'phone' => '(698) 673-0440 x524',
                    'remember_token' => null,
                    'surname' => 'Hamill',
                    'updated_at' => '2021-09-24 18:27:22.799493',
                ),
            2 =>
                array(
                    'admin_corporate_id' => 3,
                    'company_id' => 3,
                    'created_at' => '2021-09-24 18:27:22.816115',
                    'email' => 'zkassulke@waelchi.info',
                    'franchise_id' => 1,
                    'name' => 'Ford',
                    'password' => '$argon2id$v=19$m=2048,t=4,p=4$c0lSdkU0ZFBvZVhXUkNWYg$Ww0Y12oth57nv7B5o3XIpf1iKSbaChCPaI/Z5K2haL8',
                    'patronymic' => 'Nicholaus',
                    'phone' => '834.452.8663',
                    'remember_token' => null,
                    'surname' => 'Ruecker',
                    'updated_at' => '2021-09-24 18:27:22.816077',
                ),
            3 =>
                array(
                    'admin_corporate_id' => 4,
                    'company_id' => 4,
                    'created_at' => '2021-09-24 20:34:23.929101',
                    'email' => 'info@ucom.am',
                    'franchise_id' => 1,
                    'name' => null,
                    'password' => '$argon2id$v=19$m=2048,t=4,p=4$SFlDR1lYLmpodTBLb0V6Tg$5JBZiRMcvKOpweI7HMRFplIUi17I6AeLz5xjxok16Lw',
                    'patronymic' => 'Hayk',
                    'phone' => '88888888880',
                    'remember_token' => 'u6Gyn4ETSrIY0qEyWh32bvcBVA9wH7zECUcOSpdXMzYRxN15wNqbwS94UjZi',
                    'surname' => 'Yesayan',
                    'updated_at' => '2021-09-24 20:34:23.929101',
                ),
            4 =>
                array(
                    'admin_corporate_id' => 5,
                    'company_id' => 5,
                    'created_at' => '2021-09-24 20:34:23.929101',
                    'email' => 'transgaz@mail.ru',
                    'franchise_id' => 1,
                    'name' => 'Trans',
                    'password' => '$argon2id$v=19$m=2048,t=4,p=4$Nm12TkhpZkdybUwzVUYzMw$QvJWMci5Bc9zv0iA7it8ms9MMrDvsKyljHrjWfMr59A',
                    'patronymic' => 'Gazovich',
                    'phone' => '010203040506',
                    'remember_token' => '$argon2id$v=19$m=2048,t=4,p=4$Nm12TkhpZkdybUwzVUYzMw$QvJWMci5Bc9zv0iA7it8ms9MMrDvsKyljHrjWfMr59A',
                    'surname' => 'Neft',
                    'updated_at' => '2021-09-24 20:34:23.929101',
                ),
        ));
    }
}
