<?php

declare(strict_types=1);


namespace Src\Services\DriverContract;

use Barryvdh\DomPDF\Facade as PDF;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use ServiceEntity\BaseService;
use Src\Exceptions\Lexcept;
use Src\Repositories\Car\CarContract;
use Src\Repositories\Driver\DriverContract;
use Src\Repositories\DriverContract\DriverContractContract;
use Src\Repositories\DriverSchedule\DriverScheduleContract;
use Src\Repositories\DriverType\DriverTypeContract;
use Src\Repositories\DriverWallet\DriverWalletContract;
use Src\Services\Driver\DriverServiceContract;
use Src\Services\DriverSchedule\DriverScheduleServiceContract;

/**
 * Class DriverContractService
 * @package Src\Services\DriverContract
 */
class DriverContractService extends BaseService implements DriverContractServiceContract
{
    /**
     * DriverContractService constructor.
     * @param  DriverContractContract  $baseContract
     * @param  DriverContract  $driverContract
     * @param  DriverScheduleContract  $driverScheduleContract
     * @param  DriverServiceContract  $driverService
     * @param  DriverScheduleServiceContract  $driverScheduleService
     * @param  CarContract  $carContract
     * @param  DriverWalletContract  $driverCashContract
     * @param  DriverTypeContract  $driverTypeContract
     */
    public function __construct(
        protected DriverContractContract $baseContract,
        protected DriverContract $driverContract,
        protected DriverScheduleContract $driverScheduleContract,
        protected DriverServiceContract $driverService,
        protected DriverScheduleServiceContract $driverScheduleService,
        protected CarContract $carContract,
        protected DriverWalletContract $driverCashContract,
        protected DriverTypeContract $driverTypeContract
    ) {
    }

    /**
     * @param $request
     * @return array|false
     * @throws Lexcept
     */
    public function createContractFile($request)
    {
        $contract = $this->baseContract
            ->where('driver_id', '=', $request->driver_id)
            ->with([
                'driver.driver_info',
                'driver.driver_info.region',
                'driver.driver_info.city',
                'driver.entity',
                'type',
                'subtype',
                'graphic',
                'car'
            ])
            ->find($request->contract_id);

        $car = $this->carContract
            ->with(['entity' => fn($q) => $q->with(['type', 'city'])])
            ->find($request->car_id, ['car_id', 'park_id', 'entity_id', 'current_driver_id', 'franchise_id', 'status_id']);

        if (!$contract || !$car) {
            return false;
        }

        $duration = date_diff(date_create(date('Y-m-d')), date_create(date($contract->expiration_day)));
        $duration = $duration->format('%a');
        $updateData = ['signing_day' => f_now(), 'duration' => $duration, 'car_id' => $car->car_id, 'entity_id' => $car->entity_id];

        if (!$this->baseContract->where('driver_id', '=', $request->driver_id)->updateSet($updateData)) {
            return false;
        }

        $worker = auth()->user();

        $data = [
            'contract' => $contract,
            'driver' => $contract->driver,
            'info' => $contract->driver->driver_info,
            'worker' => $worker,
            'car' => $car,
            'entity' => $car->entity,
            'driverEntity' => $contract->driver->entity
        ];

        $file_name = Str::random(12).'_'.$data['contract']->driver_contract_id.'.pdf';
        $save_path = storage_path('public'.DS.'drivers'.DS.'contracts'.DS.$file_name);

        $pdf = $this->distributeByContractType($contract->subtype->value, $data);

        $pdf->getDomPDF()->set_option("enable_php", true);

        if (!$pdf->save($save_path)) {
            return null;
        }

        $web_path = '/storage/drivers/contracts/'.$file_name;
        $this->baseContract->update($request->contract_id, ['scan' => $web_path]);

        return ['contract' => $contract->driver_contract_id, 'file' => $web_path];
    }

    /**
     * @param  string  $contract_sub_type
     * @param  array  $data
     * @return PDF|null
     */
    protected function distributeByContractType(string $contract_sub_type, array $data): ?\Barryvdh\DomPDF\PDF
    {
        switch ($contract_sub_type) {
            case 'aggregator_without_sole_proprietorship':
                $pdf = PDF::loadView('system-worker.driver-contract-files.aggregator-without-sole-proprietorship', $data);
                break;

            case 'aggregator_individual_entrepreneur':
                $pdf = PDF::loadView('system-worker.driver-contract-files.aggregator-individual-entrepreneur', $data);
                break;

            case 'aggregator_self_employed':
                $pdf = PDF::loadView('system-worker.driver-contract-files.aggregator-self-employed', $data);
                break;

            case 'tenant_without_sole_proprietorship':
                $pdf = PDF::loadView('system-worker.driver-contract-files.tenant-without-sole-proprietorship', $data);
                break;

            case 'tenant_individual_entrepreneur':
                $pdf = PDF::loadView('system-worker.driver-contract-files.tenant-individual-entrepreneur', $data);
                break;

            case 'tenant_self_employed':
                $pdf = PDF::loadView('system-worker.driver-contract-files.tenant-self-employed', $data);
                break;

            case 'corporate_without_sole_proprietorship':
                $pdf = PDF::loadView('system-worker.driver-contract-files.corporate-without-sole-proprietorship', $data);
                break;

            case 'corporate_individual_entrepreneur':
                $pdf = PDF::loadView('system-worker.driver-contract-files.corporate-individual-entrepreneur', $data);
                break;

            case 'corporate_self_employed':
                $pdf = PDF::loadView('system-worker.driver-contract-files.corporate-self-employed', $data);
                break;

            case 'will_tell_without_sole_proprietorship':
                $pdf = PDF::loadView('system-worker.driver-contract-files.will-tell-without-sole-proprietorship', $data);
                break;

            case 'will_tell_individual_entrepreneur':
                $pdf = PDF::loadView('system-worker.driver-contract-files.will-tell-individual-entrepreneur', $data);
                break;

            case 'will_tell_self_employed':
                $pdf = PDF::loadView('system-worker.driver-contract-files.will-tell-self-employed', $data);
                break;

            default:
                return null;
        }

        return $pdf;
    }

    /**
     * @param $contract_id
     * @return bool|mixed
     * @throws Lexcept
     */
    public function signContract($contract_id): bool
    {
        $contract = $this->baseContract->with(['driver' => fn($query) => $query->select(['driver_id'])])->find($contract_id);

        if (!$contract) {
            throw new Lexcept("'Contract doesn't", 422);
        }

        /*update contract and driver*/
        $driverData = ['car_id' => $contract->car->car_id, 'selected_class' => $contract->car->class];

        $contract_edit = $this->baseContract->where('driver_contract_id', '=', $contract_id)->updateSet(['signed' => true, 'active' => true]);
        $driver_edit = $this->driverContract->where('driver_id', '=', $contract->driver->driver_id)->updateSet($driverData);
        $car_edit = $this->carContract->update($contract->car->car_id, ['current_driver_id' => $contract->driver->driver_id]);

        if (!$contract_edit || !$driver_edit || !$car_edit) {
            throw new Lexcept('contract error', 422);
        }

        $this->driverCashContract->updateOrCreate(['driver_id', '=', $contract->driver->driver_id], ['driver_id' => $contract->driver->driver_id]);

        /*create schedule*/
        return $this->createScheduleByContract($contract);
    }

    /**
     * @param $contract
     * @return bool
     */
    protected function createScheduleByContract($contract): bool
    {
        return $this->driverScheduleService->createDriverSchedule($contract, $contract->signing_day);
    }

    /**
     * @param $data
     * @return mixed
     * @throws Exception
     */
    public function createUnsignedContract($data): ?object
    {
        $contract_data = [
            'driver_id' => $data['driver_id'],
            'driver_type_id' => $data['type_id'],
            'driver_subtype_id' => $data['subtype_id'],
            'driver_graphic_id' => $data['graphic_id'],
            'free_days_price' => $data['free_days_price'],
            'busy_days_price' => $data['busy_days_price'],
            'car_id' => $data['car_id'],
            'entity_id' => $data['entity_id'],
            'expiration_day' => $data['expiration_day'],
            'work_start_day' => $data['work_start_day'],
            'active' => false,
            'signed' => false,
        ];

        $contract = $this->baseContract->create($contract_data);

        if (!$contract) {
            return false;
        }

        return $contract;
    }

    /**
     * @param $request
     * @return LengthAwarePaginator
     */
    public function driverContractsPaginate($request): LengthAwarePaginator
    {
        $per_page = $request['per-page'] ?: 10;
        $page = $request->page ?: 1;
        $search = ($request->search && 'null' !== $request->search) ? $request->search : '';

        $contracts = (isset($request->active) && is_numeric($request->active)) ?
            $this->baseContract->where('active', '=', $request->active) :
            $this->baseContract;

        return $contracts
            ->whereHas('driver', fn($q) => $q->where('current_franchise_id', '=', FRANCHISE_ID)->withTrashed())
            ->where('signed', '=', 1)
            ->where(fn($q) => $q->whereHas('driver',
                fn($q) => $q->where('nickname', 'LIKE', '%'.$search.'%')->orWhereHas('driver_info',
                    fn($q) => $q->where('name', 'LIKE', '%'.$search.'%')
                        ->orWhere('surname', 'LIKE', '%'.$search.'%')
                        ->orWhere('patronymic', 'LIKE', '%'.$search.'%')
                ))
                ->orWhereHas('type', fn($q) => $q->where('type', 'LIKE', '%'.$search.'%'))
                ->orWhereHas('car', fn($q) => $q->where('garage_number', 'LIKE', '%'.$search.'%'))
                ->orWhereHas('graphic', fn($q) => $q->where('name', 'LIKE', '%'.$search.'%'))
                ->orWhere('signing_day', 'LIKE', '%'.$search.'%')
                ->orWhere('expiration_day', 'LIKE', '%'.$search.'%')
                ->orWhere('duration', 'LIKE', '%'.$search.'%')
            )
            ->with([
                'graphic',
                'type',
                'car' => fn($q) => $q->with('park', 'classes'),
                'driver' => fn($q) => $q->withTrashed()->with('driver_info')
            ])
            ->orderBy('driver_contract_id', 'desc')
            ->paginate($per_page, ['*'], 'page', $page);
    }

    /**
     * @param $request
     * @return LengthAwarePaginator
     */
    public function unsignedDriversPaginate($request): LengthAwarePaginator
    {
        $per_page = $request['per-page'] ?: 25;
        $page = $request->page ?: 1;
        $search = ($request->search && 'null' !== $request->search) ? $request->search : '';

        return $this->driverContract
            ->whereDoesntHave('contracts', fn($q) => $q->where('active', '=', 1))
            ->where('car_id', '<>', null)
            ->where('current_franchise_id', '=', FRANCHISE_ID)
            ->where(fn($q) => $q->where('nickname', 'LIKE', '%'.$search.'%')->orWhereHas('type',
                fn($q) => $q->where('type', 'LIKE', '%'.$search.'%')))
            ->with(
                [
                    'car',
                    'type',
                    'subtype',
                    'driver_info',
                    'contracts' => fn($q) => $q->where('signed', '=', 1)
                ]
            )
            ->orderBy('driver_id', 'desc')
            ->paginate($per_page, ['*'], 'page', $page);
    }

    /**
     * @param $request
     * @return mixed|null
     */
    public function terminateContract($request)
    {
        $contract = $this->baseContract
            ->whereHas('driver', fn($q) => $q->where('current_franchise_id', '=', FRANCHISE_ID))
            ->where('driver_contract_id', '=', $request->contract)
            ->findFirst(['driver_contract_id', 'driver_id']);

        if (!$contract) {
            return null;
        }

        $this->baseContract->delete($contract->driver_contract_id);
        $this->driverContract->where('driver_id', '=', $contract->driver_id)->updateSet(['car_id' => null]);
        $this->carContract->where('current_driver_id', '=', $contract->driver_id)->updateSet(['current_driver_id' => null]);

        return $contract;
    }

    /**
     * @throws Lexcept
     * @inheritDoc
     */
    public function downloadContract(int $contract_id): ?string
    {
        $file = $this->baseContract->find($contract_id, ['driver_contract_id', 'scan']);

        if (!$file) {
            throw new Lexcept('Not Contract for download');
        }

        return $file->scan;
    }

    /**
     * @param array $data
     * @return bool
     */
    public function updateDriverContractPrice(array $data): ?object
    {
       return $this->baseContract->update($data['driver_contract_id'], $data);
    }
}
