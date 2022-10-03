<?php

declare(strict_types=1);

namespace Src\Core\Geo;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use ReflectionException;
use Src\Core\Additional\Guzzle;
use Src\Core\Complex\GetRightYKey;
use Src\Core\Enums\ConstApiKey;
use Src\Events\App\AddressMonitorEvent;
use Src\Exceptions\Yandex\GeocodeException;
use Src\Exceptions\Yandex\MapMatrixStatusFailedException;
use Src\Exceptions\Yandex\MapRouteFailedException;
use Src\Repositories\ApiKey\ApiKeyContract;

/**
 * Class Geo
 * @package Src\Additional
 */
class Geo
{
    /**
     * @var Guzzle
     */
    protected Guzzle $client;
    /**
     * @var ApiKeyContract
     */
    protected ApiKeyContract $apiKeyContract;

    /**
     * Geo constructor.
     */
    public function __construct()
    {
        $this->client = app(Guzzle::class);
        $this->apiKeyContract = app(ApiKeyContract::class);
    }

    /**
     * @param  string  $geocode  = cords or address
     * @param  int  $result  = 0-9
     * @param  string  $format  = xml, json
     * @param  string  $language  =  ru_RU, uk_UA, be_BY, en_RU, en_US, tr_TR
     * @return Exception|array
     * @throws GeocodeException|GuzzleException
     * @throws ReflectionException
     */
    public function geocode(string $geocode, int $result = 10, string $format = 'json', string $language = 'en_RU')
    {
        $geocode = 'geocode='.$geocode.'&';
        $result = 'result='.$result.'&';
        $format = 'format='.$format.'&';
        $lang = 'lang='.$language;
        $datum = $geocode.$result.$format.$lang;
        $geocode_path = GetRightYKey::complex(ConstApiKey::Y_GEOCODE()->getValue());

        try {
            if (!$geocode_path) {
                exit('406');
            }

            $get_data = $this->client->get($geocode_path.$datum, ['decode_content' => true]);

            $result = json_decode($get_data->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR)['response'];

            AddressMonitorEvent::dispatch($get_data, $result, [$geocode], config('nyt.y_geocode'));

            return $result;
        } catch (Exception $exception) {
            if ('406' !== $exception->getCode() && '403' === $exception->getCode()) {
                $old_key = rtrim(substr($geocode_path, strpos($geocode_path, "apikey=") + \strlen('apikey=')), '&');
                $key = GetRightYKey::complex(ConstApiKey::Y_GEOCODE()->getValue(), $old_key);
                $key ? $this->geocode($geocode, $result, $format, $language) : null;
            }

            throw new GeocodeException('Server Error Geocode Limit', 500);
        }
    }

    /**
     * @param $origins
     * @param $destinations
     * @param  int|null  $departure_time
     * @param  string  $mode
     * @return mixed
     * @throws GeocodeException
     * @throws GuzzleException
     * @throws ReflectionException
     */
    public function matrix($origins, $destinations, int $departure_time = null, string $mode = 'driving')
    {
        $origins = 'origins='.$origins.'&';
        $destinations = 'destinations='.$destinations.'&';
        $departure_time = $departure_time ? "departure_time=$departure_time&" : '';
        $mode = 'mode='.$mode;

        $datum = $origins.$destinations.$departure_time.$mode;
        $matrix_path = GetRightYKey::complex(ConstApiKey::Y_MATRIX()->getValue());

        try {
            $get_data = $this->client->get($matrix_path.$datum, ['decode_content' => true]);

            $data = json_decode(
                $get_data->getBody()->getContents(),
                true,
                512,
                JSON_THROW_ON_ERROR
            )['rows'][0]['elements'][0];

            if ('OK' !== $data['status']) {
                throw new MapMatrixStatusFailedException('Matrix status code exception');
            }

            unset($data['status']);

            AddressMonitorEvent::dispatch($get_data, $data, [$origins, $destinations], config('nyt.y_matrix'));

            return $data;
        } catch (Exception $exception) {
            throw new GeocodeException('Server Error Matrix Route Limit', 500);
        }
    }

    /**
     * @param $from
     * @param $to
     * @param  int|null  $departure_time
     * @param  string  $avoid_tolls
     * @param  string  $mode
     * @return mixed
     * @throws GuzzleException
     * @throws MapRouteFailedException
     * @throws ReflectionException
     */
    public function route($from, $to, int $departure_time = null, string $avoid_tolls = 'false', string $mode = 'driving')
    {
        $waypoints = urldecode("waypoints=$from|$to&");
        $departure_time = $departure_time ? "departure_time=$departure_time&" : '&';
        $avoid_tolls = "avoid_tolls=$avoid_tolls&";
        $mode = "mode=$mode";

        $datum = $waypoints.$departure_time.$avoid_tolls.$mode;
        $route_path = GetRightYKey::complex(ConstApiKey::Y_ROUTE()->getValue());

        try {
            $get_data = $this->client->get($route_path.$datum, ['decode_content' => true]);

            $data = json_decode($get_data->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
            AddressMonitorEvent::dispatch($get_data, $data, [$from, $to], config('nyt.y_route'));

            return $data;
        } catch (Exception $exception) {
            throw new MapRouteFailedException($exception->getMessage());
        }
    }
}
