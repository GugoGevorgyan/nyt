<?php

declare(strict_types=1);

namespace Src\Listeners\Worker;

use Illuminate\Database\Eloquent\Builder;
use Src\Broadcasting\Broadcast\Worker\WaybillCreateEvent;
use Src\Core\Enums\ConstQueue;
use Src\Events\Waybill\WaybillCreate;
use Src\Http\Resources\Worker\Waybill\WaybillData;
use Src\Models\Role\Role;
use Src\Repositories\SystemWorker\SystemWorkerContract;
use Src\Repositories\Waybill\WaybillContract;

/**
 * Class WaybillListCreateListener
 * @package Src\Listeners\Worker
 */
class WaybillListCreateListener
{
    /**
     * Create the event listener.
     *
     * @param  WaybillContract  $waybillContract
     * @param  SystemWorkerContract  $workerContract
     */
    public function __construct(protected WaybillContract $waybillContract, protected SystemWorkerContract $workerContract)
    {
    }

    /**
     * Handle the event.
     *
     * @param  WaybillCreate  $event
     * @return void
     */
    public function handle(WaybillCreate $event): void
    {
        $waybill = $this->waybillContract
            ->with([
                'car' => fn($query) => $query->select(['car_id', 'mark', 'model', 'state_license_plate', 'franchise_id']),
                'driver_info' => fn($query) => $query->select(['drivers_info.driver_info_id', 'driver_id', 'name', 'surname', 'patronymic']),
                'car_reports' => fn($query) => $query->select(['car_report_id', 'waybill_id'])
            ])
            ->find($event->waybill->waybill_id);

        $workers = $this->workerContract
            ->where('franchise_id', '=', $waybill->car->franchise_id)
            ->whereHas('roles', fn(Builder $query) => $query
                ->where('name', '=', Role::MECHANIC_WEB)
                ->orWhere('name', '=', Role::MECHANIC_API)
            )
            ->findAll(['system_worker_id', 'franchise_id']);

        foreach ($workers as $worker) {
            WaybillCreateEvent::broadcast($worker, new WaybillData($waybill));
        }
    }

    /**
     * @return string
     */
    public function viaQueue(): string
    {
        return ConstQueue::OBSERVER()->getValue();
    }
}
