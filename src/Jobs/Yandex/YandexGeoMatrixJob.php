<?php

declare(strict_types=1);

namespace Src\Jobs\Yandex;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Class YandexGeoMatrixJob
 * @package Src\Jobs\Yandex
 */
class YandexGeoMatrixJob /*implements ShouldQueue*/
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use Batchable;

    /**
     * Create a new job instance.
     *
     * @param $origins
     * @param $destinations
     * @param $mode
     */
    public function __construct(protected $origins, protected $destinations, protected $mode)
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
        $origins = 'origins='.$this->origins.'&';
        $destinations = 'destinations='.$this->destinations.'&';
        $mode = 'mode='.$this->mode;

        try {
            $get_data = $client->get(config('nyt.y_matrix').$origins.$destinations.$mode);

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
