<?php

declare(strict_types=1);

namespace Src\Jobs\Yandex;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Class YandexGeoCodeJob
 * @package Src\Jobs\Yandex
 */
class YandexGeoCodeJob implements ShouldQueue
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
     * @param $language
     */
    public function __construct(protected $geocode, protected $result, protected $format, protected $language)
    {
    }

    /**
     * Execute the job.
     *
     * @param  Client  $client
     * @return void
     * @throws GuzzleException
     */
    public function handle(Client $client)
    {
        try {
            $get_data = $client->get(config('nyt.y_geocode').'geocode='.$this->geocode.'&'.'result='.$this->result.'&'.'format='.$this->format.'&'.'lang='.$this->language);
            return json_decode($get_data->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR | JSON_NUMERIC_CHECK);
        } catch (Exception $exception) {
            $this->failedClient($exception);
        }
    }

    /**
     * Fail the job from the queue.
     *
     * @param  Exception  $exception
     * @return void
     */
    public function failedClient(Exception $exception)
    {
        // @TODO
    }
}
