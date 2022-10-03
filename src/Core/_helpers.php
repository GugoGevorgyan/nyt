<?php
/** @noinspection PhpFunctionNamingConventionInspection */

declare(strict_types=1);

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Redis\Connections\Connection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use League\Geotools\Coordinate\Coordinate;
use League\Geotools\Geotools;
use Psr\Http\Message\ResponseInterface;
use Redis as AutocompleteRedis;
use ServiceEntity\GeoIP\Location;
use Src\Core\Enums\ConstGuards;
use Src\Exceptions\HelperException;
use Src\Jobs\Yandex\YandexGeoCodeJob;
use Src\Jobs\Yandex\YandexGeoMatrixJob;
use Src\Jobs\Yandex\YandexGeoRouteJob;
use Src\Models\Client\Client;
use Src\Models\Corporate\AdminCorporate;
use Src\Models\Driver\Driver;
use Src\Models\SystemUsers\SuperAdmin;
use Src\Models\SystemUsers\SystemWorker;

if (!function_exists('multi_explode')) {
    /**
     * @param $delimiters
     * @param $data
     * @return array
     */
    function multi_explode($delimiters, $data): array
    {
        $make_ready = str_replace($delimiters, $delimiters[0], $data);

        return explode($delimiters[0], $make_ready);
    }
}

if (!function_exists('array_sort_by')) {
    /**
     * @param  array  $array
     * @param  string  $sort_by
     */
    function array_sort_by(array &$array, string $sort_by)
    {
        usort($array, static fn($a, $b) => strtotime($a[$sort_by]) - strtotime($b[$sort_by]));
    }
}

if (!function_exists('module_path')) {
    /**
     * @param  string  $path
     * @return string
     */
    function module_path(string $path = ''): string
    {
        return app()->basePath().DS.'modules'.DS.$path;
    }
}

if (!function_exists('get_guard')) {
    /**
     * @return string|null
     */
    function get_guard(): ?string
    {
        $guard = '';

        if (Auth::guard(ConstGuards::CLIENTS_WEB()->getValue())->check()) {
            $guard = ConstGuards::CLIENTS_WEB()->getValue();
        }

        if (Auth::guard(ConstGuards::BEFORE_CLIENTS_WEB()->getValue())->check()) {
            $guard = ConstGuards::BEFORE_CLIENTS_WEB()->getValue();
        }

        if (Auth::guard(ConstGuards::CLIENTS_API()->getValue())->check()) {
            $guard = ConstGuards::CLIENTS_API()->getValue();
        }

        if (Auth::guard(ConstGuards::DRIVERS_WEB()->getValue())->check()) {
            $guard = ConstGuards::DRIVERS_WEB()->getValue();
        }

        if (Auth::guard(ConstGuards::DRIVERS_API()->getValue())->check()) {
            $guard = ConstGuards::DRIVERS_API()->getValue();
        }

        if (Auth::guard(ConstGuards::SYSTEM_WORKERS_WEB()->getValue())->check()) {
            $guard = ConstGuards::SYSTEM_WORKERS_WEB()->getValue();
        }

        if (Auth::guard(ConstGuards::SYSTEM_WORKERS_API()->getValue())->check()) {
            $guard = ConstGuards::SYSTEM_WORKERS_API()->getValue();
        }

        if (Auth::guard(ConstGuards::ADMIN_SUPER_WEB()->getValue())->check()) {
            $guard = ConstGuards::ADMIN_SUPER_WEB()->getValue();
        }

        if (Auth::guard(ConstGuards::ADMIN_SUPER_API()->getValue())->check()) {
            $guard = ConstGuards::ADMIN_SUPER_API()->getValue();
        }

        if (Auth::guard(ConstGuards::ADMIN_CORPORATE_WEB()->getValue())->check()) {
            $guard = ConstGuards::ADMIN_CORPORATE_WEB()->getValue();
        }

        if (Auth::guard(ConstGuards::ADMIN_CORPORATE_API()->getValue())->check()) {
            $guard = ConstGuards::ADMIN_CORPORATE_API()->getValue();
        }

        if (Auth::guard(ConstGuards::API_TERMINALS()->getValue())->check()) {
            $guard = ConstGuards::API_TERMINALS()->getValue();
        }

        return $guard;
    }
}

if (!function_exists('get_user_id')) {
    /**
     * @return int|null
     */
    function get_user_id(): ?int
    {
        if (!Auth::guest()) {
            return (int)Auth::user()->{Auth::user()->getKeyName()};
        }

        return null;
    }
}

if (!function_exists('user')) {
    /**
     * @return null|Authenticatable|Client|SystemWorker|Driver|AdminCorporate|SuperAdmin
     */
    function user()
    {
        if (!Auth::guest()) {
            return Auth::user();
        }

        return null;
    }
}

if (!function_exists('get_request_attribute')) {
    /**
     * @param  string  $key
     * @return mixed|null
     */
    function get_request_attribute(string $key = '')
    {
        $params = app('request')->attributes->all();

        if (empty($key)) {
            return $params;
        }

        if (str_contains($key, '.')) {
            return \Illuminate\Support\Arr::get($params, $key, null);
        }

        return !empty($params[$key]) ? $params[$key] : null;
    }
}

if (!function_exists('get_model_for_guard')) {
    /**
     * @param  string  $guard
     *
     * @return string|null
     */
    function get_model_for_guard(string $guard): ?string
    {
        return collect(config('auth.guards'))
            ->map(
                static function ($guard) {
                    if (!isset($guard['provider'])) {
                        return null;
                    }

                    return config("auth.providers.{$guard['provider']}.model");
                }
            )->get($guard);
    }
}

if (!function_exists('is_not_lumen')) {
    /**
     * @return bool
     */
    function is_not_lumen(): bool
    {
        return false === stripos(app()->version(), 'lumen');
    }
}

if (!function_exists('array_clean_null')) {
    /**
     * @param  array  $haystack
     * @return array
     */
    function array_clean_null(array $haystack): array
    {
        foreach ($haystack as $key => $value) {
            if (is_array($value)) {
                $haystack[$key] = array_clean_null($haystack[$key]);
            }

            if (empty($haystack[$key])) {
                unset($haystack[$key]);
            }
        }

        return $haystack;
    }
}

if (!function_exists('array_multi_diff')) {
    /**
     * @param  array  $array1
     * @param  array  $array2
     * @return array  $array1
     */
    function array_multi_diff(array $array1, array $array2): array
    {
        $result = [];

        foreach ($array1 as $key => $val) {
            if (isset($array2[$key])) {
                if (is_array($val) && $array2[$key]) {
                    $result[$key] = array_multi_diff($val, $array2[$key]);
                }
            } else {
                $result[$key] = $val;
            }
        }

        return $result;
    }
}

if (!function_exists('write')) {
    /**
     * @param  mixed  ...$data
     * @throws JsonException
     */
    function write(...$data)
    {
        $file = 'writer.json';
        File::append(base_path("var/$file"), json_encode($data, JSON_THROW_ON_ERROR));
    }
}

//=========================================================GEOCODE===================================================//
if (!function_exists('y_geocode')) {
    /**
     * @param $geocode
     * @param  int  $result
     * @param  string  $format
     * @param  string  $lang
     * @return mixed
     */
    function y_geocode($geocode, int $result = 10, string $format = 'json', string $lang = 'en_RU')
    {
        return dispatch_now(new YandexGeoCodeJob($geocode, $result, $format, $lang));
    }
}

if (!function_exists('y_geocode_matrix')) {
    /**
     * @param $origins
     * @param $destinations
     * @param  string  $mode
     * @return mixed|ResponseInterface
     */
    function y_geocode_matrix($origins, $destinations, string $mode = 'transit')
    {
        return dispatch_now(new YandexGeoMatrixJob($origins, $destinations, $mode));
    }
}

if (!function_exists('y_geocode_route')) {
    /**
     * @param $geocode
     * @param  int  $result
     * @param  string  $format
     * @return mixed|ResponseInterface
     */
    function y_geocode_route($geocode, $result = 10, $format = 'json')
    {
        return dispatch_now(new YandexGeoRouteJob($geocode, $result, $format));
    }
}
//=========================================================GEOCODE====================================================//

if (!function_exists('is_in_array_in_array')) {
    /**
     * @param  array  $array
     * @param $key_value
     * @param  string  $key
     * @return array|null
     */
    function is_in_array_in_array(array $array, $key_value, string $key = ''): ?array
    {
        $within_array = [];

        foreach ($array as $k => $v) {
            if (is_array($v[$key])) {
                if (in_array($key_value, $v[$key], true)) {
                    $within_array[] = $v;
                } else {
                    is_in_array_in_array($v[$key], $key_value, '');
                }
            }
        }

        return !empty($within_array) ? $within_array : null;
    }
}

if (!function_exists('is_assoc')) {
    /**
     * @param  array  $arr
     * @return bool
     */
    function is_assoc(array $arr): bool
    {
        if ([] === $arr) {
            return false;
        }

        return array_keys($arr) !== range(0, count($arr) - 1);
    }
}

if (!function_exists('decode')) {
    /**
     * @param  array  $data
     * @return mixed
     * @throws JsonException
     */
    function decode(array $data = [])
    {
        return
            json_decode(
                json_encode($data, JSON_THROW_ON_ERROR | JSON_NUMERIC_CHECK),
                true,
                512,
                JSON_THROW_ON_ERROR | JSON_NUMERIC_CHECK
            );
    }
}

if (!function_exists('delete_element_in')) {
    /**
     * @param $element
     * @param $array
     */
    function delete_element_in($element, &$array)
    {
        $index = array_search($element, $array, true);

        if (false !== $index) {
            unset($array[$index]);
        }
    }
}

if (!function_exists('miles_to_km')) {
    /**
     * @param $mile
     * @return float
     */
    function miles_to_km($mile): float
    {
        return $mile * 1.60934;
    }
}

if (!function_exists('km_to_miles')) {
    /**
     * @param $mile
     * @return float
     */
    function km_to_miles($km): float
    {
        return $km * 0.621371;
    }
}

if (!function_exists('meters_to_miles')) {
    /**
     * @param $meters
     * @return float
     */
    function meters_to_miles($meters): float
    {
        return $meters * 0.000621371;
    }
}

if (!function_exists('get_numeric')) {
    /**
     * @param $value
     * @return array|string|null
     */
    function get_numeric($value): array|string|null
    {
        return preg_replace('/[\D]/', '', $value);
    }
}

if (!function_exists('number_breakdown')) {
    /**
     * @param $number
     * @param  bool  $return_unsigned
     * @return array|float|int
     */
    function number_breakdown($number, bool $return_unsigned = false): float|array|int
    {
        $negative = 1;

        if ($number < 0) {
            $negative = -1;
            $number *= -1;
        }

        if ($return_unsigned) {
            return [
                floor($number),
                ($number - floor($number))
            ];
        }

        return ($number - floor($number)) * $negative;
    }
}

if (!function_exists('array_flatten')) {
    /**
     * @param  array  $array
     * @return array|null
     */
    function array_flatten(array $array): ?array
    {
        if (!is_array($array)) {
            return null;
        }

        $result = [];

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                /** @noinspection SlowArrayOperationsInLoopInspection */
                $result = array_merge($result, array_flatten($value));
            } else {
                $result[$key] = $value;
            }
        }

        return $result;
    }
}

if (!function_exists('array_flatten_adjustable')) {
    /**
     * @param $points
     * @return array
     */
    function array_flatten_adjustable($points): array
    {
        $result = [];

        foreach ($points as $key => $value) {
            if (is_array($value)) {
                /** @noinspection SlowArrayOperationsInLoopInspection */
                $result = array_merge($result, $value);
            } else {
                $result[$key] = $value;
            }
        }

        return $result;
    }
}

if (!function_exists('get_max')) {
    /**
     * @param $array
     * @return mixed
     */
    function get_max($array): mixed
    {
        $n = count($array);
        $max = $array[0];

        for ($i = 1; $i < $n; $i++) {
            if ($max < $array[$i]) {
                $max = $array[$i];
            }
        }

        return $max;
    }
}

if (!function_exists('get_min')) {
    /**
     * @param $array
     * @return mixed
     */
    function get_min($array): mixed
    {
        $n = count($array);
        $min = $array[array_key_first($array)];

        for ($i = 1; $i < $n; $i++) {
            if ($min > $array[$i]) {
                $min = [$i => $array[$i]];
            }
        }

        return $min;
    }
}

if (!function_exists('assort_multi')) {
    /**
     * @param  array  $datum
     * @return array
     */
    function assort_multi(array $datum): array
    {
        if (count($datum) > 1) {
            foreach ($datum as &$data) {
                asort($data);
            }
        } else {
            asort($datum);
        }

        return $datum;
    }
}

if (!function_exists('crypto_rand_secure')) {
    /**
     * @param $min
     * @param $max
     * @return int
     */
    function crypto_rand_secure($min, $max): int
    {
        $range = $max - $min;

        if ($range < 1) {
            return $min;
        }

        $log = ceil(log($range, 2));
        $bytes = (int)($log / 8) + 1;
        $bits = (int)$log + 1;
        $filter = (1 << $bits) - 1;

        do {
            /** @noinspection CryptographicallySecureRandomnessInspection */
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd &= $filter;
        } while ($rnd > $range);

        return $min + $rnd;
    }
}

if (!function_exists('get_token')) {
    /**
     * @param  int  $length
     * @return string
     */
    function get_token(int $length = 32): string
    {
        $token = '';
        $codeAlphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $codeAlphabet .= 'abcdefghijklmnopqrstuvwxyz';
        $codeAlphabet .= '0123456789';
        $max = strlen($codeAlphabet);

        for ($i = 0; $i < $length; $i++) {
            $token .= $codeAlphabet[crypto_rand_secure(0, $max - 1)];
        }

        return $token;
    }
}

if (!function_exists('distance_cords')) {
    /**
     * @param $cord_a_1
     * @param $cord_a_2
     * @param $cord_b_1
     * @param $cord_b_2
     * @param  string  $in
     * @return float
     * @todo not used in many data, e.g. inside a loop: use it if high accuracy is important
     */
    function distance_cords($cord_a_1, $cord_a_2, $cord_b_1, $cord_b_2, string $in = 'km'): float
    {
        $geo_tools = new Geotools();

        $cord_a = new Coordinate([$cord_a_1, $cord_a_2]);
        $cord_b = new Coordinate([$cord_b_1, $cord_b_2]);
        $distance = $geo_tools->distance()->setFrom($cord_a)->setTo($cord_b);

        if ('m' === $in) {
            return round($distance->flat());
        }

        return round($distance->in('km')->haversine(), 2);
    }
}

if (!function_exists('distance_by_coordinates')) {
    /**
     * @param  float  $lat1
     * @param  float  $lon1
     * @param  float  $lat2
     * @param  float  $lon2
     * @param  string  $unit
     * @return float|int|string
     * @todo use it if speed and not accuracy
     * @noinspection PhpFunctionNamingConventionInspection
     */
    function distance_by_coordinates(float $lat1, float $lon1, float $lat2, float $lon2, string $unit = 'Km'): float|int|string
    {
        if (($lat1 === $lat2) && ($lon1 === $lon2)) {
            return 0;
        }

        $theta = $lon1 - $lon2;
        $distance = (sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta))));
        $distance = acos($distance);
        $distance = rad2deg($distance);
        $distance *= 60 * 1.1515;
        $kilometer = round($distance * 1.609344, 2);

        if ('Km' === $unit) {
            return $kilometer;
        }

        if ('M' === $unit) {
            return $kilometer * 1000;
        }

        return 'Unit Type not valid Km || M';
    }
}

if (!function_exists('http_build_query')) {
    /**
     * @param $data
     * @param  string  $prefix
     * @param  string  $sep
     * @param  string  $key
     * @return string
     */
    function http_build_query($data, $prefix = '', $sep = '', $key = ''): string
    {
        $ret = [];

        foreach ((array)$data as $k => $v) {
            if (is_int($k) && null !== $prefix) {
                $k = urlencode($prefix.$k);
            }

            if ((!empty($key)) || (0 === $key)) {
                $k = $key.'['.urlencode($k).']';
            }

            if (is_array($v) || is_object($v)) {
                $ret[] = http_build_query($v, '', $sep, $k);
            } else {
                $ret[] = $k.'='.urlencode($v);
            }
        }

        if (empty($sep)) {
            $sep = ini_get('arg_separator.output');
        }

        return implode($sep, $ret);
    }
}

if (!function_exists('preg_nested_contains')) {
    /**
     * @param  array  $data
     * @param  string  $preg
     * @param  array  $needles
     * @return array
     */
    function preg_nested_contains(array $data, string $preg = '/\D/', array $needles = []): array
    {
        $values = [];

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                if (session()->has('number_cleaner')) {
                    foreach (session('number_cleaner') as $number) {
                        session()->put('number_cleaner'.$number, $key);
                    }
                } else {
                    session()->push('number_cleaner', $key);
                }

                return preg_nested_contains($value, $preg, $needles);
            }

            foreach ($needles as $needle) {
                if (is_array($needle)) {
                    return preg_nested_contains($data, $preg, $needle);
                }

                if ($value && Str::contains($key, $needles)) {
                    $values[$key] = preg_replace($preg, '', is_int($value) ? (string)$value : $value);
                    break;
                }
            }
        }

        $keys = session('number_cleaner');
        session()->forget('number_cleaner');

        return compact('keys', 'values');
    }
}

if (!function_exists('round_t')) {
    /**
     * @param $int
     * @return float
     */
    function round_t($int): float
    {
        return ceil(round($int / 60, 2));
    }
}

if (!function_exists('round_d')) {
    /**
     * @param $int
     * @return float
     */
    function round_d($int): float
    {
        return ceil($int / 100) / 10;
    }
}

if (!function_exists('f_now')) {
    /**
     * @return string
     */
    function f_now(): string
    {
        return now(now()->timezone)->format('Y-m-d H:i:s');
    }
}

if (!function_exists('redis')) {
    /**
     * @param  string  $connection
     * @return AutocompleteRedis|Connection
     */
    function redis(string $connection = 'app'): Connection
    {
        return Redis::connection($connection);
    }
}

if (!function_exists('renderer')) {
    /**
     * @param  string  $folder
     * @param  string  $file
     * @return false|string
     * @throws FileNotFoundException
     * @throws HelperException
     */
    function renderer(string $folder = 'app', string $file = 'app.js'): bool|string
    {
        if (!extension_loaded('v8js')) {
            throw new HelperException('v8js extension is not installed');
        }

        $renderer_source = File::get(base_path('node_modules'.DS.'vue-server-renderer'.DS.'basic.js'));
        $app_source = File::get(public_path($folder.DS.'js'.DS.$file));
        $v8 = new V8Js();

        ob_start();
        $v8->executeString('var process = { env: { VUE_ENV: "server", NODE_ENV: "production" }}; this.global = { process: process };');
        $v8->executeString($renderer_source);
        $v8->executeString($app_source);
        return ob_get_clean();
    }
}

if (!function_exists('odd')) {
    /**
     * @param  float|int|string  $number
     * @return bool
     */
    function odd(float|int|string $number): bool
    {
        return !(0 === $number % 2);
    }
}

if (!function_exists('qr_generate')) {
    /**
     * @param  string  $text
     * @param  int  $width
     * @param  int  $height
     * @return string
     */
    function qr_generate(string $text, int $width = 300, int $height = 300): string
    {
        $qr_response = Http::get(
            'https://chart.googleapis.com/chart'
            .'?chs='.$width.'x'.$height
            .'&cht=qr&choe=UTF-8&chld=L|0'
            .'&chl='.$text
        );

        return $qr_response->body();
    }
}

if (!function_exists('recToRec')) {
    /**
     * @param  array|object  $values
     * @return Collection
     */
    function recToRec($values): Collection
    {
        return $values instanceof Collection ? $values->recToRec() : collect($values)->recToRec();
    }
}

if (!function_exists('igs')) {
    /**
     * @param  mixed  $data
     * @return string|null
     */
    function igs(mixed $data): ?string
    {
        return igbinary_serialize($data);
    }
}

if (!function_exists('igus')) {
    /**
     * @param  string  $data
     * @return mixed
     */
    function igus(string $data): mixed
    {
        return igbinary_unserialize($data);
    }
}

if (!function_exists('pc_pos')) {
    /**
     * @return bool
     */
    function pc_pos(): bool
    {
        return function_exists('pcntl_async_signals') && function_exists('posix_kill') && function_exists('proc_open');
    }
}

if (!function_exists('anonim')) {
    /**
     * @return object
     */
    function anonim(): object
    {
        return new class {
        };
    }
}

if (!function_exists('geoip')) {
    /**
     * Get the location of the provided IP.
     *
     * @param  null  $ip
     *
     * @return Location
     * @throws Exception
     */
    function geoip($ip = null): Location
    {
        if (null === $ip) {
            return app('geoip');
        }

        return app('geoip')->getLocation($ip);
    }
}

if (!function_exists('address_short')) {
    /**
     * @param  string  $address
     * @return array|string|null
     */
    function address_short(string $address): array|string|null
    {
        return preg_replace('/(?iU)^[^,]*,\s*/', '', $address);
    }
}

if (!function_exists('parse_result')) {
    /**
     * @param $instance
     * @param ...$_i
     * @return Collection
     */
    function parse_result(&$instance, ...$_i): Collection
    {
        $new_datum = !empty($_i) ? array_combine($_i[0], $_i[1]) : [];

        if (!empty($new_datum)) {
            if ($instance instanceof \Illuminate\Database\Eloquent\Collection) {
                foreach ($new_datum as $key => $value) {
                    $instance = $instance->put($key, $value);
                }
            } elseif ($instance instanceof Model) {
                foreach ($new_datum as $key => $value) {
                    $instance->{$key} = $value;
                }

                $instance = collect($instance->getAttributes())->merge($instance->relationsToArray());
            } else {
                $instance = collect($instance)->merge(collect($new_datum));
            }
        } else {
            $instance = !$instance instanceof Collection ? collect($instance) : $instance;
        }

        return $instance;
    }
}

if (!function_exists('async_loader')) {
    /**
     * @return string
     */
    function async_loader(): string
    {
        return base_path('app/AsyncLoader/RuntimeAutoload.php');
    }
}

if (!function_exists('get_nearest_timezone')) {
    /**
     * @param  string|float  $cur_lat
     * @param  string|float  $cur_long
     * @param  string  $country_code
     * @return string
     */
    function get_nearest_timezone(string|float $cur_lat, string|float $cur_long, string $country_code = ''): string
    {
        $cur_lat = (float)$cur_lat;
        $cur_long = (float)$cur_long;

        $timezone_ids = ($country_code)
            ? DateTimeZone::listIdentifiers(DateTimeZone::PER_COUNTRY, $country_code)
            : DateTimeZone::listIdentifiers();

        if (!$timezone_ids || !is_array($timezone_ids) || !isset($timezone_ids[0])) {
            return '';
        }

        $time_zone = '';
        $tz_distance = 0;

        if (1 === count($timezone_ids)) {
            $time_zone = $timezone_ids[0];
        } else {
            foreach ($timezone_ids as $timezone_id) {
                $location = (new DateTimeZone($timezone_id))->getLocation();

                if (!$location) {
                    return '';
                }

                $tz_lat = $location['latitude'];
                $tz_long = $location['longitude'];

                $theta = $cur_long - $tz_long;
                $distance = (sin(deg2rad($cur_lat)) * sin(deg2rad($tz_lat))) + (cos(deg2rad($cur_lat)) * cos(deg2rad($tz_lat)) * cos(deg2rad($theta)));
                $distance = acos($distance);
                $distance = abs(rad2deg($distance));

                if (!$time_zone || $tz_distance > $distance) {
                    $time_zone = $timezone_id;
                    $tz_distance = $distance;
                }
            }
        }

        return $time_zone;
    }
}

if (!function_exists('array_swap_assoc')) {
    /**
     * @param $key1
     * @param $key2
     * @param  array|Collection  $items
     * @return void
     * @ref $array
     */
    function array_swap_assoc($key1, $key2, &$items): void
    {
        if (($items[$key1] ?? false) && ($items[$key2] ?? false)) {
            $value1 = $items[$key1];
            $value2 = $items[$key2];
            $items[$key1] = $value2;
            $items[$key2] = $value1;
        }
    }
}

if (!function_exists('redis_remove_uuid')) {
    /**
     * @throws JsonException
     */
    function redis_uuid(array $data, string $uuid)
    {
        foreach ($data as $item) {
            $item_decode = json_decode($item, false, 512, JSON_THROW_ON_ERROR);
            if ($item_decode->uuid === $uuid) {
                $item->zrem();
            }
        }
    }
}

if (!function_exists('set_queue_id')) {
    /**
     * @param $passphrase
     * @param $uuid
     */
    function set_queue_id($passphrase, $uuid)
    {
        \redis()->hSet($passphrase, 'uuid', $uuid);
    }
}

if (!function_exists('rem_queue_id')) {
    /**
     * @param $passphrase
     */
    function rem_queue_id($passphrase)
    {
        \redis()->hDel($passphrase, ...['uuid']);
    }
}

if (!function_exists('jsponse')) {
    /**
     * @param  mixed|array  $data
     * @param  int  $status
     * @param  array  $headers
     * @param  int  $options
     * @return JsonResponse
     */
    function jsponse(mixed $data = [], int $status = 200, array $headers = [], int $options = 0): JsonResponse
    {
        return response()->json($data, $status, $headers, $options);
    }
}
