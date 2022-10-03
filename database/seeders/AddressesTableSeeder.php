<?php

declare(strict_types=1);

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use JsonException;
use Storage;

/**
 * Class AddressesTableSeeder
 */
class AddressesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     * @throws FileNotFoundException
     * @throws JsonException
     */
    public function run(): void
    {
        DB::table('addresses')->delete();

        $path_address = DIRECTORY_SEPARATOR.'monitor'.DIRECTORY_SEPARATOR.'addresses.json';
        $path_detail = DIRECTORY_SEPARATOR.'monitor'.DIRECTORY_SEPARATOR.'details.json';
        $path_routes = DIRECTORY_SEPARATOR.'monitor'.DIRECTORY_SEPARATOR.'routes.json';

        if (!Storage::disk('local')->exists($path_address) || !Storage::disk('local')->exists($path_detail) || !Storage::disk('local')->exists($path_routes)) {
            return;
        }

        $addresses = json_decode(Storage::disk('local')->get($path_address), true, 512, JSON_THROW_ON_ERROR);
        $details = json_decode(Storage::disk('local')->get($path_detail), true, 512, JSON_THROW_ON_ERROR);
        $routes = json_decode(Storage::disk('local')->get($path_routes), true, 512, JSON_THROW_ON_ERROR);

        if (empty($addresses)) {
            return;
        }

        $format = 'Y-m-d H:i:s';

        foreach ($addresses as $address) {
            //ADDRESSES
            DB::table('addresses')->insert(
                [
                    'address_id' => $address['address_id'],
                    'address' => $address['address'],
                    'lat' => $address['lat'],
                    'lut' => $address['lut'],
                    'province' => $address['province'],
                    'locality' => $address['locality'],
                    'code' => $address['code'],
                    'created_at' => Carbon::parse($address['created_at'])->format($format),
                ]
            );
        }

        foreach ($details as $detail) {
            DB::table('addresses_details')->insert(
                [
                    'address_detail_id' => $detail['address_detail_id'],
                    'initial_address_id' => $detail['initial_address_id'],
                    'end_address_id' => $detail['end_address_id'],
                    'distance' => $detail['distance'],
                    'duration' => $detail['duration'],
                    'created_at' => Carbon::parse($detail['created_at'])->format($format),
                ]
            );
        }

        foreach ($routes as $route) {
            if (isset($route['address_route_id'])) {
                DB::table('addresses_routes')->insert(
                    [
                        'address_route_id' => $route['address_route_id'],
                        'detail_id' => $route['detail_id'],
                        'route' => json_encode($route['route'], JSON_THROW_ON_ERROR),
                        'created_at' => Carbon::parse($route['created_at'])->format($format),
                    ]
                );
            }
        }
    }
}
