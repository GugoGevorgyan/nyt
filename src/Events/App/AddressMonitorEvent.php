<?php

declare(strict_types=1);

namespace Src\Events\App;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Src\Listeners\AddressMonitor\AddressDataMonitorListen;
use Src\Listeners\AddressMonitor\ApiMonitoringListen;

/**
 * Class AddressMonitorEvent
 * @package Src\Events
 */
class AddressMonitorEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * @var
     */
    public $requestData;
    /**
     * @var
     */
    public $responseData;
    /**
     * @var array
     */
    public array $cords;
    /**
     * @var string
     */
    public string $url;

    /**
     * Create a new event instance.
     * @param $request_data
     * @param $response_data
     * @param $cords
     * @param $url
     * @link ApiMonitoringListen
     * @link AddressDataMonitorListen
     */
    public function __construct($request_data, $response_data, $cords, $url)
    {
        $this->requestData = $request_data;
        $this->responseData = $response_data;
        $this->cords = $cords;
        $this->url = $url;
    }
}
