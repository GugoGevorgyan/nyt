<?php

declare(strict_types=1);


namespace Src\Services\DriverSchedule;

use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use ServiceEntity\BaseService;
use Src\Repositories\Driver\DriverContract;
use Src\Repositories\DriverContract\DriverContractContract;
use Src\Repositories\DriverSchedule\DriverScheduleContract;

use function count;

/**
 * Class DriverScheduleService
 * @package Src\Services\DriverSchedule
 */
class DriverScheduleService extends BaseService implements DriverScheduleServiceContract
{
    /**
     * DriverScheduleService constructor.
     * @param  DriverContract  $driverContract
     * @param  DriverScheduleContract  $baseContract
     * @param  DriverContractContract  $driverContractContract
     */
    public function __construct(
        protected DriverContract $driverContract,
        protected DriverScheduleContract $baseContract,
        protected DriverContractContract $driverContractContract
    ) {
    }

    /**
     * @param $request
     * @return LengthAwarePaginator
     */
    public function driversPaginate($request): LengthAwarePaginator
    {
        $page = (isset($request['current_page']) && is_numeric($request['current_page']) && '' != $request['current_page']) ? (int)$request['current_page'] : 1;
        $per_page = (isset($request['per_page']) && is_numeric($request['per_page']) && '' != $request['per_page']) ? (int)$request['per_page'] : 25;
        $park = (isset($request['park']) && is_numeric($request['park']) && '' != $request['park']) ? $request['park'] : null;
        $type = (isset($request['driver_type']) && is_numeric($request['driver_type']) && '' != $request['driver_type']) ? $request['driver_type'] : null;
        $graphic = (isset($request['schedule_type']) && is_numeric($request['schedule_type']) && '' != $request['schedule_type']) ? $request['schedule_type'] : null;
        $month = (isset($request['month']) && is_numeric($request['month']) && '' != $request['month']) ? $request['month'] : date('m');
        $year = (isset($request['year']) && is_numeric($request['year']) && '' != $request['year']) ? $request['year'] : date('Y');
        $search = (isset($request['search']) && '' != $request['search']) ? $request['search'] : null;

        $from = $year.'-'.$month.'-01';
        $to = $year.'-'.$month.'-'.cal_days_in_month(CAL_GREGORIAN, (int)$month, (int)$year);

        $drivers = $this->driverContract
            ->where('current_franchise_id', '=', FRANCHISE_ID)
            ->whereHas('active_contract');

        if ($park) {
            $drivers->whereHas(
                'park',
                fn($q) => $q->where('cars.park_id', '=', $park)
            );
        }

        if ($type) {
            $drivers->whereHas(
                'active_contract',
                fn($q) => $q->where('driver_type_id', '=', $type)
            );
        }

        if ($graphic) {
            $drivers->whereHas(
                'active_contract',
                fn($q) => $q->where('driver_graphic_id', '=', $graphic)
            );
        }

        if ($search) {
            $searchWords = explode(' ', $search);

            $drivers->whereHas(
                'driver_info',
                function ($q) use ($search, $searchWords) {
                    $q->where('name', 'LIKE', '%'.$search.'%')
                        ->orWhere('surname', 'LIKE', '%'.$search.'%')
                        ->orWhere('patronymic', 'LIKE', '%'.$search.'%');

                    foreach ($searchWords as $word) {
                        $q->orWhere('name', 'LIKE', '%'.$word.'%')
                            ->orWhere('surname', 'LIKE', '%'.$word.'%')
                            ->orWhere('patronymic', 'LIKE', '%'.$word.'%');
                    }
                }
            );
        }

        $drivers->with(
            [
                'car',
                'driver_info',
                'active_contract' => fn($q) => $q->with(['type', 'subtype', 'graphic']),
                'schedules' => fn($q) => $q->whereBetween('date', [$from, $to])
            ]
        );

        return $drivers->paginate($per_page, ['*'], 'page', $page);
    }

    /**
     * @param $request
     * @return bool
     * @throws Exception
     */
    public function updateDriverSchedule($request): bool
    {
        return $this->baseContract->beginTransaction(function () use ($request) {
            $this->baseContract->forgetCache();

            $day = $this->baseContract->whereHas('driver', fn($q) => $q->where('current_franchise_id', '=', FRANCHISE_ID))
                ->where('driver_id', '=', $request->driver_id)
                ->where('date', '=', $request->date)
                ->exists();

            if (!$day) {
                return false;
            }

            $day_update = $this->baseContract->whereHas('driver', fn($q) => $q->where('current_franchise_id', '=', FRANCHISE_ID))
                ->where('driver_id', '=', $request->driver_id)
                ->where('date', '=', $request->date)
                ->updateSet(['working' => 0]);

            if (!$day_update) {
                return false;
            }

            $oldDelete = $this->baseContract
                ->whereHas('driver', fn($q) => $q->where('current_franchise_id', '=', FRANCHISE_ID))
                ->where('driver_id', '=', $request->driver_id)
                ->where('date', '>', $request->date)
                ->deletes();

            if (!$oldDelete) {
                return false;
            }

            $contract = $this->driverContractContract
                ->where('driver_id', '=', $request->driver_id)
                ->where('active', '=', 1)
                ->findFirst();

            if (!$this->createDriverSchedule($contract, date("Y-m-d", strtotime("+1 day", strtotime($request->date))))) {
                return false;
            }

            return true;
        });
    }

    /**
     * @param $contract
     * @param $date
     * @return bool
     */
    public function createDriverSchedule($contract, $date): bool
    {
        try {
            $contract->load(['graphic' => fn($query) => $query->select(['driver_graphic_id', 'week'])]);
            $week_day = 0;
            $week = $contract->graphic->week;

            while (strtotime($date) <= strtotime($contract->expiration_day)) {
                $week_day = $week_day === count($week['values']) ? 0 : $week_day;
                $working = false;

                if (strtotime($date) >= strtotime($contract->work_start_day)) {
                    $working = $week['values'][$week_day];
                    $week_day++;
                }

                $schedule_data = [
                    'driver_id' => $contract->driver_id,
                    'driver_contract_id' => $contract->driver_contract_id,
                    'working' => $working,
                    'date' => $date,
                    'day' => date('j', strtotime($date)),
                    'month' => date('n', strtotime($date)),
                    'year' => date('Y', strtotime($date)),
                ];

                if (!$this->baseContract->create($schedule_data)) {
                    break;
                }

                $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
            }

            return true;
        } catch (Exception $exception) {
            return false;
        }
    }
}
