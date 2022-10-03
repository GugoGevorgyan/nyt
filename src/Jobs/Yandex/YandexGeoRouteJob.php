<?php

declare(strict_types=1);

namespace Src\Jobs\Yandex;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Class YandexGeoRouteJob
 * @package Src\Jobs\Yandex
 */
class YandexGeoRouteJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use Batchable;

    /**
     * Create a new job instance.
     *
     * @param $geocode
     * @param  int  $result
     * @param  string  $format
     */
    public function __construct(protected $geocode, protected $result, protected $format)
    {
    }

    /**
     * Execute the job.
     *
     * @param  Client  $client
     * @return void
     */
    public function handle(Client $client)
    {
        try {
            $get_data = $client->get(config('nyt.y_route').'geocode='.$this->geocode.'&'.'result='.$this->result.'&'.'format='.$this->format.'&'.'lang=ru_RU');
            return json_decode($get_data->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR | JSON_NUMERIC_CHECK)['response'];
        } catch (Exception $exception) {
            $this->clientFailed($exception);
        }
    }

    /**
     * @param $exception
     */
    public function clientFailed($exception)
    {
        // @TODO
    }
}
