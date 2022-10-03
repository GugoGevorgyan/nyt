<?php

declare(strict_types=1);

namespace Src\Jobs\OrderProcessing;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\Async\Pool;
use Src\Broadcasting\Broadcast\Driver\CommonOrderEvent;
use Src\Exceptions\Order\OrderCanceledInSearchDriverException;
use Src\Http\Resources\Driver\PassOrderResource;
use Src\Listeners\Order\Distributor\Contracts\CommonContract;
use Src\Listeners\Order\Distributor\Traits\OrderDistDrivers;
use Src\Repositories\CanceledOrder\CanceledOrderContract;
use Src\Repositories\Client\ClientContract;
use Src\Repositories\Driver\DriverContract;
use Src\Repositories\Order\OrderContract;
use Src\Repositories\OrderCommon\OrderCommonContract;
use Src\Repositories\OrderShippedDriver\OrderShippedDriverContract;
use Src\Services\Client\ClientServiceContract;
use Src\Services\Order\OrderServiceContract;
use stdClass;

/**
 * @method static dispatch(int $order_id, int $rejected_driver_id = null)
 */
class CommonListTikTak implements ShouldQueue, CommonContract
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use OrderDistDrivers;

    /**
     * @var object|null
     */
    public ?object $payload;
    /**
     * @var Carbon|null
     */
    public ?Carbon $timer = null;
    /**
     * @var int|null
     */
    public ?int $driverType = null;

    /**
     * @var OrderContract|null
     */
    public ?OrderContract $orderContract = null;
    /**
     * @var OrderCommonContract|null
     */
    public ?OrderCommonContract $commonContract = null;
    /**
     * @var ClientContract|null
     */
    public ?ClientContract $clientContract = null;
    /**
     * @var ClientServiceContract|null
     */
    public ?ClientServiceContract $clientService = null;
    /**
     * @var OrderServiceContract|null
     */
    public ?OrderServiceContract $orderService = null;
    /**
     * @var DriverContract|null
     */
    public ?DriverContract $driverContract = null;
    /**
     * @var OrderShippedDriverContract|null
     */
    public ?OrderShippedDriverContract $shippedContract = null;
    /**
     * @var CanceledOrderContract|null
     */
    public ?CanceledOrderContract $canceledOrderContract = null;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(protected $order_id, protected $rejected_driver_id = null)
    {
        $this->timer = now();
    }

    /**
     * Execute the job.
     *
     * @param  OrderContract  $orderContract
     * @param  OrderShippedDriverContract  $shippedContract
     * @param  OrderServiceContract  $orderService
     * @param  ClientContract  $clientContract
     * @param  OrderCommonContract  $commonContract
     * @param  CanceledOrderContract  $canceledOrderContract
     * @param  DriverContract  $driverContract
     * @param  ClientServiceContract  $clientService
     * @return void
     * @throws OrderCanceledInSearchDriverException
     */
    public function handle(
        OrderContract $orderContract,
        OrderShippedDriverContract $shippedContract,
        OrderServiceContract $orderService,
        ClientContract $clientContract,
        OrderCommonContract $commonContract,
        CanceledOrderContract $canceledOrderContract,
        DriverContract $driverContract,
        ClientServiceContract $clientService,
    ): void {
        $this->orderContract = $orderContract;
        $this->driverContract = $driverContract;
        $this->shippedContract = $shippedContract;
        $this->orderService = $orderService;
        $this->clientContract = $clientContract;
        $this->commonContract = $commonContract;
        $this->canceledOrderContract = $canceledOrderContract;
        $this->clientService = $clientService;
        $this->payload = new stdClass();

        $this->commonList();
    }

    /**
     *
     * @throws OrderCanceledInSearchDriverException
     */
    public function commonList(): void
    {
        $this->payload->order = $this->orderContract->find($this->order_id);

        $drivers = $this->getPreOrderDrivers(60, 1, 7000, 15, $this->rejected_driver_id ? [$this->rejected_driver_id] : []);

        $pool = Pool::create();
        foreach ($drivers as $driver) {
            $pool
                ->add(fn() => $this->orderService->createDriverCommonList($driver->driver_id, $this->payload->order->order_id))
                ->then(fn($output) => $output ? CommonOrderEvent::broadcast($driver, new PassOrderResource($output)) : null);
        }
        $pool->wait();

        $passed = now();

        $ids = $drivers->map(fn($items) => $items->driver_id)->all();

        $common = $this->commonContract->create([
            'order_id' => $this->payload->order->order_id,
            'driver' => ['ids' => $ids],
            'emergency' => $ids ? 0 : 1
        ]);

        if ($ids) {
            $this->tikTakCommon($common, $drivers, $passed);
        } else {
            $this->orderService->autoDispatch($this->order_id);
        }
    }

    /**
     * @param $common
     * @param $drivers
     * @param $passed
     * @throws OrderCanceledInSearchDriverException
     */
    public function tikTakCommon($common, $drivers, $passed): void
    {
        $accept = false;
        $canceled = false;

        while ($this->timer->diffInSeconds(now()) < config('nyt.common_order_timeout')) {
            $accept = (bool)$this->commonContract->where('order_id', '=', $this->order_id)->firstLatest('created_at', ['accept']);
            $canceled = $this->orderService->orderHasCanceled($this->payload->order->order_id, true);

            if ($accept) {
                break;
            }

            if ($canceled) {
                break;
            }

            usleep(500000);
        }

        if (!$accept && !$canceled) {
            $this->orderService->autoDispatch($this->order_id);
        }
    }
}
