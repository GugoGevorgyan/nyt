<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class CompaniesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        DB::table('companies')->delete();

        DB::table('companies')->insert(array(
            0 =>
                array(
                    'address' => '352 Amparo Village
East Oletaland, OR 27574',
                    'code' => '14715',
                    'company_id' => 1,
                    'contract_end' => '2021-04-05',
                    'contract_scan' => '/storage/company-contract-scans/scan.jpg',
                    'contract_start' => '2021-04-05',
                    'created_at' => '2021-04-05 16:48:04.911925',
                    'details' => 'Explicabo non aut magni totam. Nam dignissimos nam laudantium explicabo perspiciatis quibusdam et. Non quas sed reprehenderit perferendis.',
                    'email' => 'hudson.kirstin@bradtke.com',
                    'entity_id' => 2,
                    'franchise_id' => 1,
                    'frequency' => 3,
                    'limit' => '50000',
                    'name' => 'Центральный банк ',
                    'order_canceled_timeout' => 8,
                    'period' => 2,
                    'spent' => 0.0,
                    'updated_at' => '2021-04-05 16:48:04.911925',
                ),
            1 =>
                array(
                    'address' => '65641 Dewayne Bypass
Spencerburgh, DC 28108',
                    'code' => '62900',
                    'company_id' => 2,
                    'contract_end' => '2021-04-05',
                    'contract_scan' => '/storage/company-contract-scans/scan.jpg',
                    'contract_start' => '2021-04-05',
                    'created_at' => '2021-04-05 16:48:04.915905',
                    'details' => 'Voluptatem commodi rerum ducimus distinctio. Est et nisi ducimus quidem. Tempore autem inventore et. Optio explicabo animi molestiae labore doloribus dolores adipisci.',
                    'email' => 'mohr.annamae@bechtelar.com',
                    'entity_id' => 1,
                    'franchise_id' => 1,
                    'frequency' => 4,
                    'limit' => '50000',
                    'name' => 'Лореаль ',
                    'order_canceled_timeout' => 13,
                    'period' => 2,
                    'spent' => 0.0,
                    'updated_at' => '2021-04-05 16:48:04.915905',
                ),
            2 =>
                array(
                    'address' => '739 Justina Track Suite 497
Rodriguezburgh, DC 79269',
                    'code' => '22844',
                    'company_id' => 3,
                    'contract_end' => '2021-04-05',
                    'contract_scan' => '/storage/company-contract-scans/scan.jpg',
                    'contract_start' => '2021-04-05',
                    'created_at' => '2021-04-05 16:48:04.920180',
                    'details' => 'Tempora veritatis modi doloribus error quis. Nam corrupti in dolorem tenetur perspiciatis quas qui. Nobis nihil voluptates dolore laboriosam.',
                    'email' => 'lhaag@hotmail.com',
                    'entity_id' => 1,
                    'franchise_id' => 1,
                    'frequency' => 1,
                    'limit' => '50000',
                    'name' => 'Газпромбанк',
                    'order_canceled_timeout' => 1,
                    'period' => 7,
                    'spent' => 0.0,
                    'updated_at' => '2021-04-05 16:48:04.920180',
                ),
            3 =>
                array(
                    'address' => 'hoyeuj 1',
                    'code' => '4444',
                    'company_id' => 4,
                    'contract_end' => '2021-10-08',
                    'contract_scan' => '/storage/company/contract-scans/03688aa4dc14c894172f50d86f535cb8.jpg',
                    'contract_start' => '2021-09-06',
                    'created_at' => '2021-09-24 20:34:23.913365',
                    'details' => null,
                    'email' => 'info@mail.ru',
                    'entity_id' => 1,
                    'franchise_id' => 1,
                    'frequency' => 12,
                    'limit' => '5000',
                    'name' => 'UCOM',
                    'order_canceled_timeout' => 10,
                    'period' => 12,
                    'spent' => 0.0,
                    'updated_at' => '2021-09-24 20:34:23.913365',
                ),
            4 =>
                array(
                    'address' => '352 Amparo Village
East Oletaland, OR 27574',
                    'code' => '4581',
                    'company_id' => 5,
                    'contract_end' => '2021-10-08',
                    'contract_scan' => null,
                    'contract_start' => '2021-09-06',
                    'created_at' => '2021-09-24 20:34:23.913365',
                    'details' => null,
                    'email' => 'transneft@mail.ru',
                    'entity_id' => 1,
                    'franchise_id' => 1,
                    'frequency' => null,
                    'limit' => '50000',
                    'name' => 'Транснефть ',
                    'order_canceled_timeout' => null,
                    'period' => null,
                    'spent' => 0.0,
                    'updated_at' => '2021-09-24 20:34:23.913365',
                ),
        ));
    }
}
