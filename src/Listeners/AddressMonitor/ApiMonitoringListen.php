<?php

declare(strict_types=1);

namespace Src\Listeners\AddressMonitor;

use GuzzleHttp\Exception\ClientException;
use Illuminate\Contracts\Queue\ShouldQueue;
use Src\Broadcasting\Broadcast\Worker\ApiMonitoringEvent as WorkerMonitoring;
use Src\Core\Enums\ConstQueue;
use Src\Events\App\AddressMonitorEvent;
use Src\Repositories\ApiMonitoring\ApiMonitoringContract;

/**
 * Class ApiMonitoringListen
 * @package Src\Jobs
 */
class ApiMonitoringListen implements ShouldQueue
{
    /**
     * @var
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
     * Create a new job instance.
     *
     * @param  ApiMonitoringContract  $apiMonitoringContract
     */
    public function __construct(protected ApiMonitoringContract $apiMonitoringContract)
    {
    }

    /**
     * Execute the job.
     *
     * @param  AddressMonitorEvent  $event
     * @return void
     */
    public function handle(AddressMonitorEvent $event): void
    {
        $this->request = $event->requestData;
        $this->response = $event->responseData;
        $this->url = $event->url;
        $this->cords = $event->cords;

        if ($this->request instanceof ClientException) {
            $this->exceptionApiHandler();
        } else {
            $this->apiRequest();
        }
    }

    protected function exceptionApiHandler(): void
    {
        $this->apiMonitoringContract->create([
            'api' => $this->request->getRequest()->getUri()->getHost(),
            'request' => $this->request->getRequest()->getUri()->getQuery(),
            'request_method' => $this->request->getRequest()->getMethod(),
            'error' => true,
            'response_code' => $this->request->getCode()
        ]);

        WorkerMonitoring::broadcast();
    }

    protected function apiRequest(): void
    {
        $this->apiMonitoringContract->create([
            'api' => $this->url,
            'request_method' => 'GET',
            'error' => false,
            'response_code' => $this->request->getStatusCode()
        ]);

        WorkerMonitoring::broadcast();
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
