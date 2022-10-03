<?php
declare(strict_types=1);


namespace Src\Support\Filters;

use Src\Support\Facades\Geo;
use function is_array;

/**
 * Class ValidAddressFilter
 * @package Src\Filters
 */
class ValidAddressFilter extends BaseFilter
{
    /**
     * @return bool|void
     */
    public function filter()
    {
        return $this->app->extend('valid_address', static function ($attribute, $address, $key) {
            if (!empty($key)) {
                $get_address = $address . ',' . $key[0];
            } elseif (!empty($address) && is_array($address)) {
                $get_address = $address[0] . ',' . $address[1];
            } else {
                $get_address = $address;
            }

            $geocode = Geo::geocode($get_address);

            $result = $geocode['GeoObjectCollection']['metaDataProperty']['GeocoderResponseMetaData']['found'];

            return !($result < 1);
        });
    }
}
