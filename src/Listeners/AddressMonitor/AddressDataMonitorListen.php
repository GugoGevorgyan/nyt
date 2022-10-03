<?php

declare(strict_types=1);

namespace Src\Listeners\AddressMonitor;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Response;
use Illuminate\Contracts\Queue\ShouldQueue;
use JsonException;
use Src\Core\Enums\ConstQueue;
use Src\Events\App\AddressMonitorEvent;
use Src\Repositories\Address\AddressContract;
use Src\Repositories\AddressDetail\AddressDetailContract;
use Src\Repositories\AddressRoute\AddressRouteContract;

/**
 * Class AddressDataMonitorListen
 * @package Src\Listeners
 */
class AddressDataMonitorListen implements ShouldQueue
{
    /**
     * @var Response
     */
    protected $request;
    /**
     * @var
     */
    protected $response;
    /**
     * @var string
     */
    protected string $url;
    /**
     * @var array
     */
    protected array $cords;

    /**
     * Create the event listener.
     *
     * @param  AddressContract  $addressContract
     * @param  AddressDetailContract  $detailContract
     * @param  AddressRouteContract  $routeContract
     */
    public function __construct(
        protected AddressContract $addressContract,
        protected AddressDetailContract $detailContract,
        protected AddressRouteContract $routeContract
    ) {
    }

    /**
     * Handle the event.
     *
     * @param  AddressMonitorEvent  $event
     * @return void
     * @throws JsonException
     */
    public function handle(AddressMonitorEvent $event): void
    {
        $this->request = $event->requestData;
        $this->response = $event->responseData;
        $this->url = $event->url;
        $this->cords = $event->cords;

        if ($this->request instanceof ClientException) {
            return;
        }

        switch ($this->url) {
            case config('nyt.y_geocode'):
                $this->geocodeRequest();
                break;
            case config('nyt.y_matrix'):
                $this->matrixRequest();
                break;
            case config('nyt.y_route'):
                $this->routeRequest();
                break;
            default:
        }
    }

    /**
     * @throws JsonException
     */
    protected function geocodeRequest(): void
    {
        if (isset($this->response['GeoObjectCollection']['metaDataProperty']['GeocoderResponseMetaData']['Point'])) {
            $cord_cleaner = explode(' ', $this->response['GeoObjectCollection']['metaDataProperty']['GeocoderResponseMetaData']['Point']['pos']);
        } else {
            $cord_cleaner = explode(' ', $this->response['GeoObjectCollection']['featureMember'][0]['GeoObject']['Point']['pos']);
        }

        $cord = decode(['lat' => $cord_cleaner[1], 'lut' => $cord_cleaner[0]]);
        $address = $this->response['GeoObjectCollection']['featureMember'][0]['GeoObject']['metaDataProperty']['GeocoderMetaData']['Address'];
        $short_address = $this->response['GeoObjectCollection']['featureMember'][0]['GeoObject']['name'];
        $full_address = $address['formatted'];

        if (!$this->addressContract->where('lat', '=', $cord['lat'])->where('lut', '=', $cord['lut'])->exists()) {
            foreach ($address['Components'] as $value) {
                if ('province' === $value['kind']) {
                    $province = $value['name'];
                }

                if ('locality' === $value['kind']) {
                    $locality = $value['name'];
                }
            }

            $this->addressContract->create([
                'address' => $full_address,
                'short_address' => $short_address,
                'locality' => $locality ?? null,
                'province' => $province ?? null,
                'code' => $address['country_code'],
                'lat' => $cord['lat'] ?? null,
                'lut' => $cord['lut'] ?? null,
            ]);
        }
    }

    /**
     * @throws JsonException
     */
    protected function matrixRequest()
    {
//        if (1 === count($this->response['rows'])) {
//            $row = $this->response['rows'][0]['elements'][0];
//            $uri = $this->addressContract->where('name', '=', $this->request->getRequest()->getUri());
//            $origins = explode('origins=', $uri);
//            $origin = explode('&destinations=', $origins[0]);
//        }
    }

    /**
     * @throws JsonException
     */
    protected function routeRequest(): void
    {
        $steps = $this->response['route']['legs'][0];

        if ('FAIL' === $steps['status']) {
            return;
        }

        $duration = 0;
        $length = 0;
        $points = [];

        foreach ($steps['steps'] as $single_step) {
            $length += $single_step['length'];
            $duration += $single_step['duration'];
            $points[] = $single_step['polyline']['points'];
        }

        $point = array_flatten_adjustable($points);

        $response_from = explode(',', $this->cords[0]);
        $response_to = explode(',', $this->cords[1]);

        $addresses = $this->addressContract->findAll(['address_id', 'lat', 'lut']);

        foreach ($addresses as $address) {
            $first_coincidence[$address->address_id] = distance_cords(
                $address->lat,
                $address->lut,
                $response_from[0],
                $response_from[1],
                'm'
            );
            $last_coincidence[$address->address_id] = distance_cords(
                $address->lat,
                $address->lut,
                $response_to[0],
                $response_to[1],
                'm'
            );
        }

        $first_min_distance = min($first_coincidence);
        $last_min_distance = min($last_coincidence);

        $first_min_distance_id = array_keys($first_coincidence, min($first_coincidence));
        $last_min_distance_id = array_keys($last_coincidence, min($last_coincidence));

        $routes = $this->detailContract
            ->where('initial_address_id', '=', $first_min_distance_id[0])
            ->where('end_address_id', '=', $last_min_distance_id[0])
            ->count();

        if ($routes >= 1 || ($first_min_distance_id[0] === $last_min_distance_id[0])) {
            return;
        }

        if (($first_min_distance <= 30) && ($last_min_distance <= 30)) {
            $detail = $this->detailContract
                ->create([
                    'initial_address_id' => $first_min_distance_id[0],
                    'end_address_id' => $last_min_distance_id[0],
                    'distance' => round_d($length),
                    'duration' => round($duration / 60)
                ]);

            if (!$detail) {
                return;
            }

            $route = [];

            foreach ($point as $item) {
                $route['route'][] = decode(['lat' => $item[0], 'lut' => $item[1]]);
            }

            $route['detail_id'] = $detail->{$detail->getKeyName()};
            $this->routeContract->create($route);
        }
    }

    /**
     * Get the name of the listener's queue.
     *
     * @return string
     */
    public function viaQueue(): string
    {
        return ConstQueue::BASE()->getValue();
    }
}
