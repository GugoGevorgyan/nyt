<?php

declare(strict_types=1);


namespace Src\Services\Worker;

use Carbon\Carbon;
use Exception;
use Hash;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\ArrayShape;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use ReflectionException;
use ServiceEntity\BaseService;
use Src\Broadcasting\Broadcast\Client\DriverLate;
use Src\Broadcasting\Broadcast\Client\OrderReset;
use Src\Broadcasting\Broadcast\Driver\ClientOrderCancel;
use Src\Broadcasting\Broadcast\Driver\CommonOrderEvent;
use Src\Broadcasting\Broadcast\Driver\WaybillAnnulled;
use Src\Broadcasting\Broadcast\Worker\DriverOrderLate;
use Src\Core\Enums\ConstDriverType;
use Src\Core\Enums\ConstOrderDistType;
use Src\Core\Enums\ConstRedis;
use Src\Core\Enums\ConstTransactionType;
use Src\Exceptions\Lexcept;
use Src\Http\Resources\Driver\PassOrderResource;
use Src\Models\Driver\Driver;
use Src\Models\Driver\DriverStatus;
use Src\Models\Order\Order;
use Src\Models\Order\OrderCommon;
use Src\Models\Order\OrderShippedStatus;
use Src\Models\Order\OrderStatus;
use Src\Models\Order\PaymentType;
use Src\Models\Role\Role;
use Src\Models\SystemUsers\SystemWorker;
use Src\Repositories\Car\CarContract;
use Src\Repositories\CarReport\CarReportContract;
use Src\Repositories\CarReportImage\CarReportImageContract;
use Src\Repositories\CarReportQuestion\CarReportQuestionContract;
use Src\Repositories\Client\ClientContract;
use Src\Repositories\Company\CompanyContract;
use Src\Repositories\CompletedOrder\CompletedOrderContract;
use Src\Repositories\CompletedOrderChange\CompletedOrderChangeContract;
use Src\Repositories\Driver\DriverContract;
use Src\Repositories\DriverContract\DriverContractContract;
use Src\Repositories\DriverGraphic\DriverGraphicContract;
use Src\Repositories\DriverType\DriverTypeContract;
use Src\Repositories\DriverWallet\DriverWalletContract;
use Src\Repositories\Franchisee\FranchiseContract;
use Src\Repositories\FranchiseTransaction\FranchiseTransactionContract;
use Src\Repositories\Menu\MenuContract;
use Src\Repositories\Order\OrderContract;
use Src\Repositories\OrderCommon\OrderCommonContract;
use Src\Repositories\OrderShippedDriver\OrderShippedDriverContract;
use Src\Repositories\Park\ParkContract;
use Src\Repositories\PaymentType\PaymentTypeContract;
use Src\Repositories\Preorder\PreorderContract;
use Src\Repositories\Role\RoleContract;
use Src\Repositories\SystemWorker\SystemWorkerContract;
use Src\Repositories\Tariff\TariffContract;
use Src\Repositories\Waybill\WaybillContract;
use Src\Repositories\WorkerDispatcher\WorkerDispatcherContract;
use Src\Repositories\WorkerOperator\WorkerOperatorContract;
use Src\Repositories\WorkerPermission\WorkerPermissionContract;
use Src\Repositories\WorkerRole\WorkerRoleContract;
use Src\Repositories\WorkerSession\WorkerSessionContract;
use Src\Services\Client\ClientServiceContract;
use Src\Services\Geocode\GeocodeServiceContract;
use Src\Services\Module\ModuleServiceContract;
use Src\Services\Order\OrderServiceContract;
use Src\Services\Payment\PaymentServiceContract;
use Src\Services\WorkerRole\WorkerRoleServiceContract;

use function is_array;

/**
 * Class ParkService
 * @package Src\Services\Park
 */
final class WorkerService extends BaseService implements WorkerServiceContract
{
    use WorkerTrait;

    /**
     * WorkerService constructor.
     * @param  SystemWorkerContract  $workerContract
     * @param  ParkContract  $parkContract
     * @param  MenuContract  $menuContract
     * @param  RoleContract  $roleContract
     * @param  WorkerRoleServiceContract  $workerRoleService
     * @param  ModuleServiceContract  $moduleService
     * @param  OrderContract  $ordersContract
     * @param  WaybillContract  $waybillContract
     * @param  DriverContract  $driverContract
     * @param  FranchiseContract  $franchiseContract
     * @param  DriverGraphicContract  $driverGraphicContract
     * @param  DriverTypeContract  $driverTypeContract
     * @param  ClientContract  $clientContract
     * @param  ClientServiceContract  $clientService
     * @param  CarReportContract  $carReportContract
     * @param  CarReportQuestionContract  $carReportQuestionContract
     * @param  CarContract  $carContract
     * @param  CompanyContract  $companyContract
     * @param  PaymentTypeContract  $paymentTypeContract
     * @param  OrderShippedDriverContract  $shippedContract
     * @param  WorkerDispatcherContract  $workerDispatcherContract
     * @param  WorkerOperatorContract  $workerOperatorContract
     * @param  WorkerPermissionContract  $workerPermissionContract
     * @param  CarReportImageContract  $carReportImageContract
     * @param  OrderContract  $orderContract
     * @param  DriverContractContract  $driverContractsContract
     * @param  FranchiseTransactionContract  $transactionContract
     * @param  DriverWalletContract  $driverWalletContract
     * @param  TariffContract  $tariffContract
     * @param  CompletedOrderContract  $completedOrderContract
     * @param  CompletedOrderChangeContract  $completedChangeContract
     * @param  PaymentServiceContract  $paymentService
     * @param  WorkerRoleContract  $workerRoleContract
     * @param  OrderCommonContract  $commonContract
     * @param  PreorderContract  $preorderContract
     */
    public function __construct(
        protected SystemWorkerContract $workerContract,
        protected ParkContract $parkContract,
        protected MenuContract $menuContract,
        protected RoleContract $roleContract,
        protected WorkerRoleServiceContract $workerRoleService,
        protected ModuleServiceContract $moduleService,
        protected OrderContract $ordersContract,
        protected WaybillContract $waybillContract,
        protected DriverContract $driverContract,
        protected FranchiseContract $franchiseContract,
        protected DriverGraphicContract $driverGraphicContract,
        protected DriverTypeContract $driverTypeContract,
        protected ClientContract $clientContract,
        protected ClientServiceContract $clientService,
        protected CarReportContract $carReportContract,
        protected CarReportQuestionContract $carReportQuestionContract,
        protected CarContract $carContract,
        protected CompanyContract $companyContract,
        protected PaymentTypeContract $paymentTypeContract,
        protected OrderShippedDriverContract $shippedContract,
        protected WorkerDispatcherContract $workerDispatcherContract,
        protected WorkerOperatorContract $workerOperatorContract,
        protected WorkerPermissionContract $workerPermissionContract,
        protected CarReportImageContract $carReportImageContract,
        protected OrderContract $orderContract,
        protected DriverContractContract $driverContractsContract,
        protected FranchiseTransactionContract $transactionContract,
        protected DriverWalletContract $driverWalletContract,
        protected TariffContract $tariffContract,
        protected CompletedOrderContract $completedOrderContract,
        protected CompletedOrderChangeContract $completedChangeContract,
        protected PaymentServiceContract $paymentService,
        protected WorkerRoleContract $workerRoleContract,
        protected OrderCommonContract $commonContract,
        protected PreorderContract $preorderContract,
        protected OrderServiceContract $orderService,
        protected GeocodeServiceContract $geoService,
        protected WorkerSessionContract $workerSessionContract,
    ) {
    }

    /**
     * @param $request
     * @return LengthAwarePaginator
     */
    public function workersPaginate($request): LengthAwarePaginator
    {
        $per_page = isset($request['per_page']) && is_numeric($request['per_page']) ? $request['per_page'] : 25;
        $page = isset($request->page) && is_numeric($request->page) ? $request->page : 1;
        $search = isset($request->search) && null != $request->search ? $request->search : null;
        $role_ids = isset($request->role_ids) && null != $request->role_ids ? explode(',', $request->role_ids) : null;

        return $this->workerContract
            ->where('franchise_id', '=', FRANCHISE_ID)
            ->when($search, fn(Builder $q) => $q
                ->where('name', '=', $search)
                ->orWhere('surname', '=', $search)
                ->orWhere('patronymic', '=', $search)
                ->orWhere('phone', '=', $search)
                ->orWhere('email', '=', $search)
                ->orWhere('nickname', '=', $search)
            )
            ->when($role_ids, fn($q) => $q->whereHas('roles', fn($q) => $q->whereIn('roles.role_id', $role_ids)))
            ->with(['roles' => fn(BelongsToMany $role_query) => $role_query->select(['roles.role_id', 'text', 'description'])])
            ->orderBy('system_worker_id', 'desc')
            ->paginate($per_page, ['*'], 'page', $page);
    }

    /**
     * @param $request
     * @return bool|mixed
     * @throws Exception
     */
    public function createSystemWorker($request)
    {
        $data = [
            'name' => $request->name,
            'surname' => $request->surname,
            'patronymic' => $request->patronymic,
            'nickname' => $request->nickname,
            'email' => $request->email,
            'phone' => $request->phone,
            'description' => $request->description,
            'password' => $request->password,
            'franchise_id' => FRANCHISE_ID
        ];

        if ($request->hasFile('photo_file')) {
            $path = storage_path('public'.DS.'system-workers');
            $data['photo'] = '/storage/system-workers/'.$this->fileUpload($request->photo_file, $path); // @todo Performance
        }

        $this->workerContract->beginTransaction(function () use ($request, $data) {
            $this->workerContract->forgetCache();
            $worker = $this->workerContract->create($data);

            if (!$worker) {
                return false;
            }

            if (!$this->createRolePermissions($worker, $request->role_permissions)) {
                return false;
            }

            if ($request->dispatcher_sub_phone_id
                && !$this->workerDispatcherContract->create([
                    'franchise_sub_phone_id' => $request->dispatcher_sub_phone_id,
                    'system_worker_id' => $worker->system_worker_id
                ])
                && $this->isDispatcher(array_keys($request->role_permissions))
            ) {
                return false;
            }

            if ($request->operator_sub_phone_id
                && !$this->workerOperatorContract->create([
                    'franchise_sub_phone_id' => $request->operator_sub_phone_id,
                    'system_worker_id' => $worker->system_worker_id
                ])
                && $this->isOperator(array_keys($request->role_permissions))
            ) {
                return false;
            }

            /*operator create event*/
            if ($this->isOperator(array_keys($request->role_permissions))) {
                $worker->load('worker_operator');
            }
        });

        return true;
    }

    /**
     * @param $request
     * @param $worker_id
     * @return bool
     * @throws Exception
     */
    public function updateWorker($request, $worker_id): bool
    {
        $data = [
            'name' => $request->name,
            'surname' => $request->surname,
            'patronymic' => $request->patronymic,
            'nickname' => $request->nickname,
            'email' => $request->email,
            'phone' => $request->phone,
            'description' => $request->description
        ];

        if ($request->hasFile('photo_file')) {
            $path = storage_path('public'.DS.'system-workers');
            $data['photo'] = '/storage/system-workers/'.$this->fileUpload($request->photo_file, $path);
        }

        $worker = $this->workerContract->find($worker_id);

        if ($request->change_password) {
            $data['password'] = $request->password;
        }

        $this->workerContract->beginTransaction();

        if (!$worker || !$this->workerContract->update($worker_id, $data)) {
            $this->workerContract->rollBack();
            return false;
        }

        if (!$this->updateWorkerRolePermissions($worker, $request->role_permissions)) {
            $this->workerContract->rollBack();
            return false;
        }

        /*call center*/
        if ($worker->worker_dispatcher && !$this->workerDispatcherContract->where('system_worker_id', '=', $worker_id)->deletes()) {
            $this->workerContract->rollBack();
            return false;
        }

        if ($request->dispatcher_sub_phone_id
            && !$this->workerDispatcherContract->create([
                'system_worker_id' => $worker_id,
                'franchise_sub_phone_id' => $request->dispatcher_sub_phone_id
            ])
            && $this->isDispatcher(array_keys($request->role_permissions))
        ) {
            $this->workerContract->rollBack();
            return false;
        }

        if ($worker->worker_operator && !$this->workerOperatorContract->where('system_worker_id', '=', $worker_id)->deletes()) {
            $this->workerContract->rollBack();
            return false;
        }

        if ($request->operator_sub_phone_id
            && !$this->workerOperatorContract->create(['system_worker_id' => $worker_id, 'franchise_sub_phone_id' => $request->operator_sub_phone_id])
            && $this->isOperator(array_keys($request->role_permissions))
        ) {
            $this->workerContract->rollBack();
            return false;
        }

        $this->workerContract->commit();

        /*operator create event*/
        if ($this->isOperator(array_keys($request->role_permissions))) {
            $worker->load('worker_operator');
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteWorkers($system_worker_ids): ?bool
    {
        try {
            foreach ($system_worker_ids as $worker_id) {
                if (!$this->deleteWorker($worker_id)) {
                    return false;
                }
            }

            return true;
        } catch (Exception) {
            return false;
        }
    }

    /**
     * @param $system_worker_id
     * @return mixed
     */
    public function deleteWorker($system_worker_id)
    {
        return $this->workerContract->where('franchise_id', '=', FRANCHISE_ID)->delete($system_worker_id) ?: null;
    }

    /**
     * @param $system_worker_id
     * @return mixed
     */
    public function getWorkerById($system_worker_id)
    {
        $worker = $this->workerContract
            ->where('franchise_id', FRANCHISE_ID)
            ->with([
                'worker_roles' => fn($query) => $query->select(['worker_role_id', 'system_worker_id', 'role_id']),
                'worker_dispatcher' => fn($query) => $query->select([
                    'system_worker_id',
                    'worker_dispatcher_id',
                    'franchise_sub_phone_id',
                    'atc_logged',
                ]),
                'worker_operator' => fn($query) => $query->select([
                    'system_worker_id',
                    'franchise_sub_phone_id',
                    'atc_logged',
                    'worker_operator_id'
                ])
            ])
            ->findBy($this->workerContract->getKeyName(), $system_worker_id);

        $worker->worker_roles->flatMap(function ($item) {
            $item->worker_permissions = $this->workerPermissionContract
                ->whereHas('permission', fn(Builder $query) => $query->whereHas('role', fn(Builder $query) => $query->where('role_id', '=', $item->role_id)))
                ->findAll(['worker_permission_id', 'system_worker_id', 'permission_id']);
        });

        return $worker;
    }

    /**
     * @return Collection
     */
    public function getFranchiseTutors(): Collection
    {
        return $this->workerContract
            ->where('franchise_id', '=', FRANCHISE_ID)
            ->findWhereHas(['roles', fn(Builder $role_query) => $role_query->where('name', '=', 'tutor_web')],
                [
                    'system_worker_id',
                    'franchise_id',
                    'is_admin',
                    'name',
                    'surname',
                    'patronymic',
                    'nickname',
                    'email',
                    'phone',
                    'description',
                    'photo',
                    'rating'
                ]
            );
    }


    /*================================================WAYBILLS============================================*/

    /**
     * @inheritDoc
     */
    public function getFranchiseWaybills(int $page = 1, int $per_page = 15, array $filters = []): LengthAwarePaginator
    {
        return $this->waybillContract
            ->withTrashed()
            ->withSum('transaction', 'amount')
            ->whereHas('car', fn($query) => $query->where('franchise_id', '=', FRANCHISE_ID))
            ->when(isset($filters['date_start']), fn(Builder $builder) => $builder
                ->whereDate('start_time', '>=', Carbon::parse($filters['date_start'])->format('Y-m-d H:i:s'))
                ->whereDate('end_time', '<=', Carbon::parse($filters['date_end'])->format('Y-m-d H:i:s'))
            )
            ->when(isset($filters['search']),
                function (Builder $builder) use ($filters) {
                    if (is_numeric($filters['search'])) {
                        return $builder->where('number', 'LIKE', '%'.$filters['search'].'%');
                    }

                    return $builder
                        ->whereHas('driver_info', fn($query) => $query
                            ->where('name', 'LIKE', '%'.$filters['search'].'%')
                            ->orWhere('surname', 'LIKE', '%'.$filters['search'].'%')
                            ->orWhere('patronymic', 'LIKE', '%'.$filters['search'].'%')
                        );
                }
            )
            ->when(isset($filters['drivers']), fn(Builder $builder) => $builder->whereIn('driver_id', explode(',', (string)$filters['drivers'])))
            ->when(isset($filters['parks']), fn(Builder $builder) => $builder
                ->whereHas('park', fn($query) => $query->whereIn('parks.park_id', explode(',', (string)$filters['parks'])))
            )
            ->with([
                'car' => fn($query) => $query->select(['car_id', 'mark', 'model', 'state_license_plate']),
                'driver_info' => fn($query) => $query->select(['drivers_info.driver_info_id', 'driver_id', 'name', 'surname', 'patronymic']),
                'car_reports' => fn($query) => $query->select(['car_report_id', 'waybill_id']),
                'transaction' => fn($query) => $query->select([
                    'franchise_transaction_id',
                    'side_id',
                    'side_type',
                    'franchise_id',
                    'amount',
                    'reason_type',
                    'reason_id'
                ]),
            ])
            ->orderBy('created_at', 'DESC')
            ->paginate($per_page, ['*'], 'page', $page);
    }

    /**
     * @param $waybill_id
     * @return Collection
     */
    public function getWaybillDetails($waybill_id): Collection
    {
        return $this->carReportContract
            ->where('waybill_id', '=', $waybill_id)
            ->findAll(['car_report_id', 'question_id', 'verified', 'comment', 'created_at']);
    }

    /**
     * @inheritDoc
     */
    public function getWaybillImages($waybill_id): Collection
    {
        return $this->carReportImageContract
            ->whereHas('report', fn($q) => $q->where('waybill_id', '=', $waybill_id))
            ->findAll(['car_report_image_id', 'report_id', 'path', 'name']);
    }

    /**
     * @inheritDoc
     */
    public function annulWaybill($waybill_id): ?string
    {
        $waybill = $this->waybillContract->withTrashed()->find($waybill_id, ['waybill_id', 'driver_id', 'deleted_at']);
        $result = null;

        if ($waybill && $waybill->deleted_at) {
            $waybill->restore();
        } else {
            $waybill->delete();
            $result = Carbon::parse($waybill->deleted_at)->format('Y-m-d H:i');
        }

        $driver = $this->driverContract->find($waybill->driver_id, ['driver_id', 'phone', 'current_franchise_id', 'car_id']);
        WaybillAnnulled::broadcast($driver, ['message' => trans('messages.waybill_annulled')]);

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function printWaybill($waybill_id)
    {
        $path_waybill = $this->waybillContract->find($waybill_id, ['waybill_id', 'waybill']);

        return $path_waybill?->waybill;
    }

    /*================================================END WAYBILLS============================================*/


    /**
     * @inheritDoc
     */
    public function getFranchiseWorkersByRoleName($roleName): Collection
    {
        return $this->workerContract
            ->where('franchise_id', '=', FRANCHISE_ID)
            ->whereHas('roles', fn($q) => $q->where('name', '=', $roleName))
            ->findAll(['system_worker_id', 'franchise_id', 'name', 'surname', 'patronymic']) ?: collect();
    }

    /**
     * @param  SystemWorker  $worker
     * @return string|null
     */
    public function stopSession(SystemWorker $worker): ?string
    {
        if (!$this->workerContract->update($worker->{$worker->getKeyName()}, ['in_session' => false])) {
            return null;
        }

        $token = get_token(128);
        $quit_time = now()->format('Y-m-d H:i:s');
        $quit_id = $worker->{$worker->getKeyName()};

        $this->workerSessionContract->updateOrCreate(['quit_worker_id', '=', $quit_id],
            ['quit_worker_id' => $quit_id, 'token' => $token, 'quit_time' => $quit_time]);

        return $token;
    }

    /**
     * @inheritDoc
     */
    public function startSession(SystemWorker $worker, string $nickname, $password, $token): ?bool
    {
        $find_nickname = $this->workerContract->where('nickname', '=', $nickname)->findFirst(['nickname', 'password', 'system_worker_id']);

        if (!Hash::check($password, $find_nickname->password)) {
            return null;
        }

        $token_worker = $worker::whereHas(
            'quit_sessions',
            static fn(Builder $quit_query) => $quit_query->where('token', '=', $token)
        )->exists();

        if (!$token_worker) {
            return null;
        }

        $worker->quit_sessions()
            ->where('token', '=', $token)
            ->update(['logged_worker_id' => $worker->{$worker->getKeyName()}, 'logged_time' => now()->format('Y-m-d H:i:s')]);

        $worker->quit_sessions()
            ->where('token', '=', $token)
            ->delete();

        $this->workerContract->update($worker->{$worker->getKeyName()}, ['in_session' => true]);

        return true;
    }

    /**
     * @inheritDoc
     */
    public function lateOrder(int $driver_id, int $late_minute = null): ?bool
    {
        $driver = $this->driverContract
            ->has('current_order')
            ->with([
                'current_order' => fn($query) => $query->select(
                    ['orders.order_id', 'client_id', 'passenger_id', 'customer_id', 'customer_type', 'franchisee']
                ),
                'current_order_shipment' => fn($query) => $query->select(
                    ['order_shipped_driver_id', 'driver_id', 'order_id', 'current', 'status_id', 'late']
                )
            ])
            ->find($driver_id, ['driver_id']);

        if (!$driver) {
            throw new Lexcept('Invalid Data', 422);
        }

        $data = [
            'order_id' => $driver->current_order->order_id,
            'text' => trans('messages.driver_order_late', ['minute' => $late_minute]),
            'minute' => $late_minute,
        ];

        if ($driver->current_order->customer_type === $this->clientContract->getMap()) {
            $this->workerContract
                ->whereHas('roles', fn($query) => $query->where('name', '=', Role::DISPATCHER_WEB))
                ->findWhereIn(['franchise_id', $driver->current_order->franchisee['ids']], ['system_worker_id', 'franchise_id'])
                ->map(fn($item) => DriverOrderLate::broadcast($item, ['text' => trans(), 'minute' => $late_minute]));

            DriverLate::broadcast(
                $this->clientService->getOrderedClientData($driver->current_order->order_id, ['client_id', 'phone']),
                $data
            );
        } else {
            $this->workerContract
                ->whereHas(
                    'roles',
                    fn($query) => $query
                        ->where('name', '=', Role::OPERATOR_WEB)
                        ->orWhere('name', '=', Role::OPERATOR_API)
                        ->orWhere('name', '=', Role::DISPATCHER_API)
                        ->orWhere('name', '=', Role::DISPATCHER_WEB)
                )
                ->findWhereIn(['franchise_id', $driver->current_order->franchisee['ids']], ['system_worker_id', 'franchise_id'])
                ->map(fn($item) => DriverOrderLate::broadcast($item, $data));
        }

        $this->shippedContract
            ->where('driver_id', '=', $driver_id)
            ->where('order_id', '=', $driver->current_order->order_id)
            ->where('order_shipped_drivers.current', '=', 1)
            ->where('order_shipped_drivers.status_id', '=', OrderShippedStatus::PRE_ACCEPTED)
            ->updateSet(['late' => $late_minute]);

        return true;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function report(string $waybill_number, array $data, array $questions, array $images): bool
    {
        $waybill = $this->waybillContract->firstWhere(['number', '=', $waybill_number], ['waybill_id', 'car_id']);
        $this->waybillContract->where('number', '=', $waybill_number)->updateSet(['comment' => $data['comment']]);

        $car = $this->carContract->find($waybill->car_id, ['car_id', 'speedometer']);

        if ($car && $car->speedometer > $data['speedometer']) {
            throw new Lexcept(trans('messages.mechanic_speedometer_invalid'), 400);
        }

        $path = storage_path('public'.DS.'mechanic-report-img'.DS);
        $web_path = '/storage/mechanic-report-img/';

        !Storage::exists($path) ? Storage::makeDirectory($path) : null;

        $signed = false;
        $verified_ids = [];

        foreach ($questions as $question_id => $question) {
            $report = $this->carReportContract->create([
                'waybill_id' => $waybill->waybill_id,
                'question_id' => $question_id,
                'verified' => $question['verify'],
                'comment' => $question['comment']
            ]);
            $question['verify'] ? $verified_ids[] = $question_id : null;
        }

        if ($images) {
            array_map(
                fn($image) => [
                    $upload = $this->fileUpload($image, $path),
                    $this->carReportImageContract->create([
                        'report_id' => $report->car_report_id,
                        'path' => $web_path,
                        'name' => $upload,
                        'ext' => Str::after($upload, '.')
                    ])
                ],
                $images
            );
        }

        $questions_sum = $this->carReportQuestionContract->findWhereIn(['question_id', $verified_ids])->sum('point');

        $this->carContract->update($waybill->car_id, ['speedometer' => $data['speedometer']]);

        if ($questions_sum >= 6) {
            $this->waybillContract->update($waybill->waybill_id, ['verified' => true, 'signed' => true]);
            $signed = true;
        } else {
            $this->waybillContract->update($waybill->waybill_id, ['verified' => true, 'signed' => false]);
        }

        return $signed;
    }

    public function getDriversOfParks($data)
    {
        $per_page = intval($data['per_page']) ?? 10;
        $page = intval($data['page']) ?? 1;
        return $this->driverContract
            ->whereHas('park')
            ->with([
                'driver_info' => fn($q) => $q->select(['driver_info_id', 'name', 'surname', 'patronymic']),
                'side' => fn($q) => $q
                    ->when(($data['date_start'] === $data['date_end']), fn($q) => $q
                        ->dateTimeFormat('franchise_transactions.created_at', $data['date_start'])
                    )
                    ->when(!($data['date_start'] === $data['date_end']), fn($q) => $q
                        ->whereDate('franchise_transactions.created_at', '>=', Carbon::parse($data['date_start'])->format('Y-m-d H:i:s.u'))
                        ->whereDate('franchise_transactions.created_at', '<=', Carbon::parse($data['date_end'])->format('Y-m-d H:i:s.u'))
                    )
                    ->select(['franchise_transaction_id', 'franchise_id', 'side_id', 'side_type', 'amount', 'type', 'franchise_transactions.created_at']),
            ])->findAll(['driver_id', 'driver_info_id'])
            ->map(function ($item) {
                $item->waybills_price = 0;
                $item->crashes_price = 0;
                $item->full_name = $item->driver_info->name.' '.$item->driver_info->surname.' '.$item->driver_info->patronymic;
                if (!empty($item['side'])) {
                    foreach ($item['side'] as $transaction) {
                        if ($transaction['type'] === ConstTransactionType::WAYBILL()->getValue()) {
                            $item->waybills_price += $transaction['amount'];
                        } elseif ($transaction['type'] === ConstTransactionType::CRASH()->getValue()) {
                            $item->crashes_price += $transaction['amount'];
                        }
                    }
                }
                $item->total_price = $item->waybills_price + $item->crashes_price;

                return $item;
            })->paginate($per_page, 'page', $page);
    }

    /**
     * @inheritDoc
     */
    public function getBookkeepingProps(): array
    {
        $payment_types = $this->paymentTypeContract->findAll(['type', 'name']);
        $driver_types = $this->driverTypeContract->findAll(['driver_type_id', 'name']);
        $companies = $this->companyContract->where('franchise_id', '=', FRANCHISE_ID)->findAll(['company_id', 'franchise_id', 'name']);
        $parks = $this->parkContract->where('franchise_id', '=', FRANCHISE_ID)->findAll();
        $drivers = $this->driverContract
            ->where('current_franchise_id', '=', FRANCHISE_ID)
            ->with(['driver_info' => fn($query) => $query->select(['driver_info_id', 'name', 'surname', 'patronymic', 'photo'])])
            ->findAll(['driver_id', 'driver_info_id', 'current_franchise_id', 'phone', 'selected_class', 'selected_option'])
            ->map(function ($item) {
                $item->full_name = $item->driver_info->name.' '.$item->driver_info->surname.' '.$item->driver_info->patronymic;
                $item->photo = $item->driver_info->photo;
                return $item;
            });

        $transaction_types = [];
        $iterate = 0;
        foreach (ConstTransactionType::getAll() as $key => $value) {
            $transaction_types[$iterate]['type'] = $value;
            $transaction_types[$iterate]['name'] = trans('words.'.Str::lower($key));
            ++$iterate;
        }
        $transaction_types = collect($transaction_types);

        return compact('driver_types', 'companies', 'payment_types', 'drivers', 'transaction_types', 'parks');
    }

    /**
     * @inheritDoc
     */
    public function bookkeepingPaginate($request): LengthAwarePaginator
    {
        $page = $request->page ?: 1;
        $per_page = $request['per_page'] ?: 10;
        $search = ($request->search && 'null' !== $request->search) ? $request->search : '';

        $payment_types = ($request->payment_types && 'null' !== $request->payment_types) ? explode(',', $request->payment_types) : [];
        $trans_types = ($request->trans_types && 'null' !== $request->trans_types) ? explode(',', $request->trans_types) : [];
        $parks = ($request->parks && 'null' !== $request->parks) ? explode(',', $request->parks) : [];
        $driver = $request->driver;
        $date_start = ($request->date_start && 'null' !== $request->date_start) ? $request->date_start : [];
        $date_end = ($request->date_end && 'null' !== $request->date_end) ? $request->date_end : [];
        $payed = $request->payed ?? false;

        $data = $this->transactionContract
            ->where('franchise_id', '=', FRANCHISE_ID)
            ->when($payed, fn(Builder $query) => $query->where('payed', '=', true))
            ->when($payment_types, fn(Builder $query) => $query->whereIn('payment_type_id', $payment_types))
            ->when($trans_types, fn(Builder $query) => $query->whereIn('type', $trans_types))
            ->when($driver,
                fn(Builder $query) => $query->whereHasMorph('side', [(new Driver())->getMap()], fn($query) => $query->where('driver_id', '=', $driver)))
            ->when($parks, fn(Builder $query) => $query->whereIn('park_id', $parks))
            ->when($date_start, fn(Builder $query) => $query
                ->whereDate('created_at', '>=', Carbon::parse($date_start))
                ->whereDate('created_at', '<=', Carbon::parse($date_end)))
            ->with([
                'park' => fn(BelongsTo $query) => $query->select(['*']),
                'worker' => fn(BelongsTo $query) => $query->select(['*']),
                'side' => fn(MorphTo $query) => $query->select(['*']),
            ])
            ->orderBy('created_at', 'desc')
            ->paginate($per_page, ['*'], 'page', $page);

        $data->sum = $data->sum('amount');

        return $data;
    }

    /**
     * @inheritDoc
     */
    public function bookkeepingTransactionInfo($transaction_id): ?object
    {
        return $this->transactionContract
            ->where('franchise_id', '=', FRANCHISE_ID)
            ->with([
                'park' => fn(BelongsTo $query) => $query->select(['park_id', 'name', 'city_id', 'address']),
                'worker' => fn(BelongsTo $query) => $query->select(['system_worker_id', 'name', 'surname', 'patronymic', 'photo']),
                'side' => fn(MorphTo $query) => $query->select(),
                'reason' => fn(MorphTo $query) => $query->select()
            ])
            ->find($transaction_id);
    }

    /**
     * @throws Lexcept
     * @throws ReflectionException
     * @throws Exception
     */
    public function workerCreateTransaction($type, $side, $sum, $input, $comment): array
    {
        $_driver = $this->driverContract
            ->with([
                'car' => fn($query) => $query->select(['car_id', 'park_id']),
                'cash' => fn($query) => $query->select(['driver_wallet_id', 'driver_id', 'balance', 'debt']),
            ])
            ->find($side, ['driver_id', 'car_id', 'current_franchise_id']);

        $side_cost = 0;
        $reason_id = null;
        $reason_type = null;

        switch ($type) {
            case ConstTransactionType::BALANCE()->getValue():
                $reason_id = $_driver->cash->driver_wallet_id;
                $reason_type = $this->driverWalletContract->getMap();

                if ($input) {
                    $balance = $sum + $_driver->cash->balance;
                    $this->driverWalletContract->where('driver_id', '=', $side)->updateSet(['balance' => $balance]);

                    $side_cost = $sum;
                    $amount = $sum;
                    $sum = 0;
                } elseif ($_driver->cash->balance > 0 && $_driver->cash->balance > $sum) {
                    $balance = $_driver->cash->balance - $sum;
                    $this->driverWalletContract->where('driver_id', '=', $side)->updateSet(['balance' => $balance]);

                    $side_cost = $sum;
                    $amount = $sum;
                    $sum = 0;
                } else {
                    return [
                        'success' => false,
                        'message' => 'У вас на балансе '.$_driver->cash->balance.' рублей'
                    ];
                }
                break;
            case ConstTransactionType::CRASH()->getValue():

                if ($input) {
                    $balance = $_driver->cash->balance;
                    $balance -= $sum;

                    $this->driverWalletContract->where('driver_id', '=', $side)
                        ->updateSet(['balance' => $balance]);
                    $amount = $sum;
                    $side_cost = 0;
                }
                break;
            case ConstTransactionType::DEBT()->getValue():
                $balance = $_driver->cash->balance;
                $balance -= $sum;

                $this->driverWalletContract->where('driver_id', '=', $side)
                    ->updateSet(['balance' => $balance]);
                $amount = $sum;
                $side_cost = 0;
                break;
            default:
        }
        $type_const = ConstTransactionType::search($type);

        $this->transactionContract->beginTransaction(fn() => [
            $this->transactionContract
                ->where('side_id', '=', $_driver->driver_id)
                ->where('side_type', '=', $this->driverContract->getMap())
                ->latest('created_at')
                ->first(['side_id', 'side_type', 'remainder', 'created_at'])
                ->remainder ?? 0,

            $this->transactionContract->create([
                'franchise_id' => $_driver->current_franchise_id,
                'park_id' => $_driver->car->park_id,
                'worker_id' => get_user_id(),
                'payment_type_id' => PaymentType::getTypeId(PaymentType::CASH),
                'side_id' => $_driver->driver_id,
                'side_type' => $_driver->getMap(),
                'reason_id' => $reason_id, // @todo
                'reason_type' => $reason_type, // @todo,
                'type' => ConstTransactionType::$type_const()->getValue(),
                'franchise_cost' => $sum,
                'side_cost' => $side_cost,
                'amount' => $amount,
                'out' => !$input,
                'remainder' => $balance,
                'payed' => true,
                'comment' => $comment
            ]),
        ]);

        return [
            'success' => true,
            'message' => 'Операция прошла успешно'
        ];
    }

    /**
     * @param $side
     * @param $date_start
     * @param $date_end
     * @return Xlsx
     * @throws ReflectionException|\PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws Lexcept
     */
    public function createPrintTransaction($side, $date_start, $date_end): Xlsx
    {
        $transactions = $this->transactionContract
            ->where('side_id', '=', $side)
            ->where('side_type', '=', $this->driverContract->getMap())
            ->when($date_start, fn($query) => $query
                ->whereDate('created_at', '>=', Carbon::parse($date_start))
                ->whereDate('created_at', '<=', Carbon::parse($date_end)))
            ->findAll([
                'franchise_transaction_id',
                'side_id',
                'side_type',
                'amount',
                'franchise_cost',
                'side_cost',
                'remainder',
                'created_at',
                'number',
                'type'
            ]);

        $driver = $this->driverContract
            ->with([
                'driver_info' => fn($query) => $query->select(['driver_info_id', 'name', 'surname', 'patronymic']),
                'active_contract' => fn($query) => $query->select(['driver_id', 'driver_type_id', 'signing_day', 'expiration_day', 'busy_days_price']),
                'car' => fn($query) => $query->select(['car_id', 'park_id'])->with(['park' => fn($query) => $query->select(['park_id', 'name'])]),
            ])
            ->find($side, ['driver_id', 'driver_info_id', 'car_id']);

        if (!$driver && $driver->active_contract) {
            throw new Lexcept('Driver not active contract', 420);
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Водитель:');
        $sheet->setCellValue('B1', $driver->driver_info->full_name);

        $sheet->setCellValue('A2', 'Автопредприятие:');
        $sheet->setCellValue('B2', $driver->car->park->name);

        $sheet->setCellValue('A3', 'Схема работы:');
        $sheet->setCellValue(
            'B3',
            'Аренда '
            .$driver->active_contract->busy_days_price
            .' '
            .trans('messages.driver_type_'.Str::lower(ConstDriverType::search($driver->active_contract->driver_type_id)))
            .' '
            .Carbon::parse($driver->active_contract->signing_day)->diffInDays(Carbon::parse($driver->active_contract->expiration_day))
            .' Дней'
        );

        $sheet->setCellValue('A4', 'Период:');
        $sheet->setCellValue('B4', $driver->active_contract->signing_day.' '.$driver->active_contract->expiration_day);

        $sheet->setCellValue('A5', 'Дата');
        $sheet->setCellValue('B5', 'Номер');
        $sheet->setCellValue('C5', 'Операция');
        $sheet->setCellValue('D5', 'Тип');
        $sheet->setCellValue('E5', 'Начислено');
        $sheet->setCellValue('F5', 'Внесено');
        $sheet->setCellValue('G5', 'Остаток');

        $counter = 0;
        foreach ($transactions as $key => $transaction) {
            $sheet->setCellValue('A'.($key + 6), Carbon::parse($transaction->created_at)->format('d.m.Y  H:i'));
            $sheet->setCellValue('B'.($key + 6), $transaction->number);
            $sheet->setCellValue(
                'C'.($key + 6),
                $transaction->comment ?? trans('words.'.Str::lower(ConstTransactionType::search($transaction->type)))
            );
            $sheet->setCellValue('D'.($key + 6), trans('words.'.Str::lower(ConstTransactionType::search($transaction->type))));
            $sheet->setCellValue('E'.($key + 6), $transaction->amount);
            $sheet->setCellValue('F'.($key + 6), $transaction->franchise_cost);
            $sheet->setCellValue('G'.($key + 6), $transaction->remainder);

            $counter++;
        }


        $sheet
            ->getStyle('A5:G'.($counter + 6))
            ->getAlignment()
            ->setHorizontal('center')
            ->setVertical('center');

        $sheet
            ->getStyle('A1:G4')
            ->getAlignment()
            ->setVertical('center');

        $sheet
            ->getStyle('A5:G'.($counter + 5))
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        for ($i = 'A'; 'H' !== $i; $i++) {
            $sheet->getColumnDimension($i)->setAutoSize(true);
        }

        for ($i = 1; $i <= $counter + 6; $i++) {
            $sheet->getRowDimension($i)->setRowHeight(30);
        }


        return new Xlsx($spreadsheet);
    }

    /**
     * @param  array  $franchise_ids
     * @return Collection
     */
    public function getCallCenterWorkers($franchise_ids): Collection
    {
        return $this->workerContract
            ->when(is_array($franchise_ids), fn(Builder $query) => $query->whereIn('franchise_id', $franchise_ids))
            ->when(!is_array($franchise_ids), fn(Builder $query) => $query->where('franchise_id', '=', $franchise_ids))
            ->has('worker_dispatcher')
            ->orHas('worker_operator')
            ->with(['worker_dispatcher', 'worker_operator'])
            ->findAll();
    }

    /**
     * @param $request
     * @return object|null
     * @throws Exception
     */
    public function updateProfile($request): ?object
    {
        $data = [
            'name' => $request->name,
            'surname' => $request->surname,
            'patronymic' => $request->patronymic,
            'nickname' => $request->nickname,
            'email' => $request->email,
            'phone' => $request->phone,
        ];

        if ($request->hasFile('photo_file')) {
            $path = storage_path('public'.DS.'system-workers');
            $data['photo'] = '/storage/system-workers/'.$this->fileUpload($request->photo_file, $path);
        }

        return $this->workerContract->beginTransaction(function () use ($request, $data) {
            $this->workerContract->forgetCache();

            if ($request->change_password) {
                $data['password'] = $request->password;
            }

            if (!$this->workerContract->where($this->workerContract->getKeyName(), '=', USER_ID)->updateSet($data)) {
                return null;
            }

            return $this->getProfileWorker(USER_ID);
        });
    }

    /**
     * @inheritDoc
     */
    public function getProfileWorker($worker_id): object
    {
        return $this->workerContract->with('roles')->find($worker_id);
    }

    /**
     * @inheritDoc
     */
    public function getSubPhone($system_worker_id)
    {
        $operator = $this->workerDispatcherContract
            ->where('system_worker_id', '=', $system_worker_id)
            ->with(['sub_phone' => fn($query) => $query->select(['franchise_sub_phone_id', 'franchise_phone_id', 'number'])])
            ->findFirst();

        return $operator->sub_phone ?? '';
    }

    /**
     * @inheritDoc
     */
    public function getOperatorSubPhone($system_worker_id): object|string
    {
        $operator = $this->workerOperatorContract
            ->whereHas('system_worker', fn($query) => $query->where('system_worker_id', '=', $system_worker_id))
            ->with('sub_phone')
            ->findFirst();

        return $operator->sub_phone ?? '';
    }

    /**
     * @param $request
     * @return LengthAwarePaginator
     */
    public function franchiseOperatorsPaginate($request): LengthAwarePaginator
    {
        $per_page = $request['per-page'] && is_numeric($request['per-page']) ? $request['per-page'] : '25';
        $page = is_numeric($request->page) ?: 1;
        $search = ($request->search && 'null' !== $request->search) ? $request->search : false;

        return $this->workerContract
            ->where('franchise_id', '=', FRANCHISE_ID)
            ->whereHas('worker_operator')
            ->when($search, fn($q) => $q->where(
                fn($q) => $q->where('name', 'LIKE', '%'.$search.'%')
                    ->orWhere('surname', 'LIKE', '%'.$search.'%')
                    ->orWhere('patronymic', 'LIKE', '%'.$search.'%')
                    ->orWhereHas('worker_operator.sub_phone', fn($q) => $q->where('number', 'LIKE', '%'.$search.'%'))
            ))
            ->with(['worker_operator.sub_phone'])
            ->orderBy('system_worker_id', 'desc')
            ->paginate($per_page, ['*'], 'page', $page);
    }

    /**
     * @inheritDoc
     */
    public function getFranchiseOperators(): Collection
    {
        return $this->workerOperatorContract
            ->whereHas('system_worker', fn($q) => $q->where('franchise_id', '=', FRANCHISE_ID))
            ->with('system_worker')
            ->findAll() ?: collect();
    }

    /**
     * @inheritdoc
     */
    public function operatorAttachOrder($request): bool|object
    {
        $order = $this->orderContract
            ->where('order_id', '=', $request['order_id'])
            ->whereHas('franchise', fn(Builder $q) => $q->where('franchise_id', '=', FRANCHISE_ID))
            ->exists();

        if (!$order) {
            return false;
        }

        return $this->orderContract->update($request['order_id'], ['operator_id' => $request->operator_id]);
    }

    public function bookkeepingCompanyOrdersReportDownload($data)
    {
        $orders = $this->bookkeepingCompanyOrdersReport($data, false);
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(30);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $sheet->getStyle(1)->getAlignment()->setVertical('center');
        $sheet->mergeCells('A1:AC1');
        $sheet->getStyle('A1')->getFont()->setSize(18)->setBold(true);
        $sheet->getRowDimension(1)->setRowHeight(40);
        $sheet->getStyle('A2')->getFont()->setBold(true);
        $sheet->getStyle('B2')->getFont()->setBold(true);
        $sheet->getStyle('C2')->getFont()->setBold(true);
        $sheet->getStyle('D2')->getFont()->setBold(true);
        $sheet->getStyle('E2')->getFont()->setBold(true);
        $sheet->setCellValue(
            'A1',
            'Отчет для компаний за период с '
            .Carbon::parse($data['date_start'])->format('d.m.Y')
            .' по '
            .Carbon::parse($data['date_end'])->format('d.m.Y')
        );
        $sheet->setCellValue('A2', ' Номер ');
        $sheet->setCellValue('B2', 'Наименование организации');
        $sheet->setCellValue('C2', 'Сумма');
        $sheet->setCellValue('D2', 'НДС (%)');
        $sheet->setCellValue('E2', 'Сумма с НДС');

        $order_cost_total = 0;

        foreach ($orders as $key => $order) {
            $sheet->setCellValue('A'.($key + 3), $order->company_id);
            $sheet->setCellValue('B'.($key + 3), $order->name);
            $sheet->setCellValue('C'.($key + 3), $order->completed_sum_cost);
            $sheet->setCellValue('D'.($key + 3), '');
            $sheet->setCellValue('E'.($key + 3), $order->completed_sum_cost);

            $order_cost_total += $order->completed_sum_cost;
        }

        $sheet->setCellValue('C'.(count($orders) + 3), $order_cost_total);
        $sheet->setCellValue('E'.(count($orders) + 3), $order_cost_total);

        return new Xlsx($spreadsheet);
    }

    /**
     * @param  array  $data
     * @param  bool  $paginate
     * @return LengthAwarePaginator|Collection
     */
    public function bookkeepingCompanyOrdersReport(array $data, bool $paginate = true): LengthAwarePaginator|Collection
    {
        $per_page = ($data['per_page'] ?? 10);
        $page = ($data['page'] ?? 1);

        $companies = $this->companyContract
            ->withoutScope()
            ->with([
                'completed' => fn($q) => $q
                    ->when(($data['date_start'] === $data['date_end']), fn($query) => $query
                        ->dateTimeFormat('completed_orders.created_at', $data['date_start'])
                    )
                    ->when(!($data['date_start'] === $data['date_end']), fn($query) => $query
                        ->where('completed_orders.created_at', '>=', Carbon::parse($data['date_start'])->format('Y-m-d H:i:s'))
                        ->where('completed_orders.created_at', '<=', Carbon::parse($data['date_end'])->format('Y-m-d H:i:s'))
                    )
                    ->select(['completed_order_id', 'cost', 'completed_orders.created_at']),
            ])
            ->findAll(['company_id', 'name'])
            ->map(function ($item) {
                $item['completed_sum_cost'] = 0;
                if ($item['completed']) {
                    foreach ($item['completed'] as $order) {
                        $item['completed_sum_cost'] += $order['cost'];
                    }
                }

                return $item;
            });

        if ($paginate) {
            return $companies->paginate($per_page, "", $page);
        }

        return $companies;
    }

    /**
     * @param  array  $data
     * @return Xlsx
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function bookkeepingCompanyOrdersDownload(array $data): Xlsx
    {
        $orders = $this->bookkeepingCompanyOrders($data, false);

        $company = $this->companyContract->find($data['company'], [$this->companyContract->getKeyName(), 'name']);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->mergeCells('A1:AC1');
        $sheet->mergeCells('A2:AC2');
        $sheet->mergeCells('A3:AC3');
        $sheet->mergeCells('A4:AC4');

        $sheet->getStyle('A1')->getFont()->setBold(true);

        $sheet->setCellValue('A1', 'Отчет по оказанным услугам');
        $sheet->setCellValue('A2', 'Наименование заказчика: '.$company->name.' ('.$company->{$company->getKeyName()}.')');
        $sheet->setCellValue(
            'A3',
            'Реестр поездок б\\н за период с '
            .Carbon::parse($data['date_start'])->format('d.m.Y')
            .' по '
            .Carbon::parse($data['date_end'])->format('d.m.Y')
        );

        $sheet->setCellValue('A5', ' Номер ');
        $sheet->setCellValue('B5', 'Дата заказа');
        $sheet->setCellValue('C5', 'Ф.И.О. пассажира');
        $sheet->setCellValue('D5', 'Дата начала поездки');
        $sheet->setCellValue('E5', 'Дата завершения поездки');
        $sheet->setCellValue('F5', 'Адрес подачи');
        $sheet->setCellValue('G5', 'Направление');
        $sheet->setCellValue('H5', 'Цена по тарифу');
        $sheet->setCellValue('I5', 'Ожидание (мин)');
        $sheet->setCellValue('J5', 'Бесплатное ожидание (мин)');
        $sheet->setCellValue('K5', 'Цена за мин. платного ожидания (руб)');
        $sheet->setCellValue('L5', 'Стоимость ожидания (руб)');
        $sheet->setCellValue('M5', 'Время поездки "внутри" (после минимала) (мин)');
        $sheet->setCellValue('N5', 'Цена за мин. поездки "внутри" (после минимала) (руб)');
        $sheet->setCellValue('O5', 'Стоимость поездки (время) "внутри" (руб)');
        $sheet->setCellValue('P5', 'Километраж "внутри" (после минимала) (км)');
        $sheet->setCellValue('Q5', 'Цена за км. поездки "внутри" (после минимала) (руб)');
        $sheet->setCellValue('R5', 'Стоимость поездки (расстояние) "внутри" (руб)');
        $sheet->setCellValue('S5', 'Общая стоимость поездки "внутри" (руб)');
        $sheet->setCellValue('T5', 'Время поездки "за" (после минимала) (мин)');
        $sheet->setCellValue('U5', 'Цена за мин. поездки "за" (после минимала) (руб)');
        $sheet->setCellValue('V5', 'Стоимость поездки (время) "за" (руб)');
        $sheet->setCellValue('W5', 'Километраж "за" (после минимала) (км)');
        $sheet->setCellValue('X5', 'Цена за км. поездки "за" (после минимала) (руб)');
        $sheet->setCellValue('Y5', 'Стоимость поездки (расстояние) "за" (руб)');
        $sheet->setCellValue('Z5', 'Общая стоимость поездки "за" (руб)');
        $sheet->setCellValue('AA5', 'Без НДС');
        $sheet->setCellValue('AB5', 'НДС (%)');
        $sheet->setCellValue('AC5', 'Общая стоимость');

        $orders_total = 0;

        $counter = 0;
        foreach ($orders as $key => $order) {
            $sheet->setCellValue('A'.($key + 6), $order->{$order->getKeyName()});
            $sheet->setCellValue('B'.($key + 6), $order->created_at);
            $sheet->setCellValue('C'.($key + 6), $order->customer->full_name);
            $sheet->setCellValue('D'.($key + 6), $order->stage->started);
            $sheet->setCellValue('E'.($key + 6), $order->stage->ended);
            $sheet->setCellValue('F'.($key + 6), $order->address_from);
            $sheet->setCellValue('G'.($key + 6), $order->address_to);
            $sheet->setCellValue('H'.($key + 6), $order->tariff->minimal_price);
            $sheet->setCellValue('I'.($key + 6), round($order->process->waiting_time / 60));
            $sheet->setCellValue('J'.($key + 6), $order->tariff->free_wait_minutes);
            $sheet->setCellValue('K'.($key + 6), $order->tariff->paid_wait_minute);
            $sheet->setCellValue('L'.($key + 6), '');
            $sheet->setCellValue('M'.($key + 6), $order->completed->in_duration);
            $sheet->setCellValue('N'.($key + 6), $order->tariff->current_tariff->price_min);
            $sheet->setCellValue('O'.($key + 6), $order->completed->in_duration_price);
            $sheet->setCellValue('P'.($key + 6), $order->completed->in_distance);
            $sheet->setCellValue('Q'.($key + 6), $order->tariff->current_tariff->price_km);
            $sheet->setCellValue('R'.($key + 6), $order->completed->in_distance_price);
            $sheet->setCellValue('S'.($key + 6), $order->completed->in_price);
            $sheet->setCellValue('T'.($key + 6), $order->completed->out_duration);
            $sheet->setCellValue('U'.($key + 6), $order->tariff->current_tariff->behind->price_min);
            $sheet->setCellValue('V'.($key + 6), $order->completed->out_duration_price);
            $sheet->setCellValue('W'.($key + 6), $order->completed->out_distance);
            $sheet->setCellValue('X'.($key + 6), $order->tariff->current_tariff->behind->price_km);
            $sheet->setCellValue('Y'.($key + 6), $order->completed->out_distance_price);
            $sheet->setCellValue('Z'.($key + 6), $order->completed->out_price);
            $sheet->setCellValue('AA'.($key + 6), $order->completed->cost);
            $sheet->setCellValue('AB'.($key + 6), 20);
            $sheet->setCellValue('AC'.($key + 6), $order->completed->cost * 1.2);

            $orders_total += $order->completed->cost * 1.2;

            $counter++;
        }

        $sheet
            ->getStyle('A5:AC'.($counter + 6))
            ->getAlignment()
            ->setHorizontal('center')
            ->setVertical('center');

        $sheet
            ->getStyle('A5:AC'.($counter + 5))
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        $sheet
            ->getStyle('AC'.($counter + 6))
            ->getFont()
            ->setBold(true);

        $sheet
            ->getStyle('AC'.($counter + 6))
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        $sheet->setCellValue('AC'.($counter + 6), 'Общая сумма: '.$orders_total);

        for ($i = 'A'; 'AD' !== $i; $i++) {
            $sheet->getColumnDimension($i)->setAutoSize(true);
        }

        for ($i = 5; $i <= $counter + 6; $i++) {
            $sheet->getRowDimension($i)->setRowHeight(30);
        }

        return new Xlsx($spreadsheet);
    }

    /**
     * @param  array  $data
     * @param  bool  $paginate
     * @return Collection|LengthAwarePaginator
     */
    public function bookkeepingCompanyOrders(array $data, bool $paginate = true): LengthAwarePaginator|Collection
    {
        $per_page = $data['per_page'] ?? 10;
        $page = $data['page'] ?? 1;

        $orders_query = $this->ordersContract
            ->where('status_id', '=', Order::ORDER_STATUS_COMPLETED)
            ->when($data['company'], fn($query) => $query
                ->whereHas('corporate', fn($query) => $query->select(['order_id', 'company_id'])
                    ->where('company_id', '=', $data['company'])))
            ->when($data['date_start'], fn($query) => $query
                ->whereDate('created_at', '>=', $data['date_start']))
            ->when($data['date_end'], fn($query) => $query
                ->whereDate('created_at', '<=', $data['date_end']))
            ->with([
                'stage' => fn($query) => $query->select(['order_id', 'accepted', 'in_placed', 'started', 'ended']),
                'customer' => fn($query) => $query->select(['*']),
                'tariff' => fn($query) => $query->select(['*'])
                    ->with([
                        'current_tariff' => fn($query) => $query
                            ->with(['behind' => fn($query) => $query->select(['*'])])
                            ->select(['*'])
                    ]),
                'process' => fn($query) => $query->select(['order_shipped_id', 'travel_time', 'waiting_time', 'pause_time']),
                'initial_data' => fn($query) => $query->select(['order_id', 'price']),
                'completed' => fn($query) => $query->select(['order_id', 'duration_price', 'distance_price', 'cost']),
                'crossing' => fn($query) => $query->select([
                    'completed_id',
                    'in_price',
                    'out_price',
                    'in_distance_price',
                    'out_distance_price',
                    'in_duration_price',
                    'out_duration_price',
                    'in_distance',
                    'out_distance',
                    'in_duration',
                    'out_duration',
                    'in_trajectory',
                    'out_trajectory',
                ])
            ]);

        if ($paginate) {
            $orders = $orders_query
                ->paginate($per_page, ['order_id', 'customer_id', 'customer_type', 'address_from', 'address_to', 'created_at'], 'page', $page);
        } else {
            $orders = $orders_query->findAll(['order_id', 'customer_id', 'customer_type', 'address_from', 'address_to', 'created_at']);
        }

        return $orders;
    }

    /**
     * @param  null  $cost
     * @throws Lexcept
     * @throws Exception
     */
    public function recalculateOrder(int $order_id, $distance, $duration, bool $cross = null, $cost = null): ?object
    {
        $driver = $this->orderContract->getCompletedOrderDriverData($order_id, ['driver_id']);
        $transaction = $this->transactionContract
            ->where('reason_id', '=', $order_id)
            ->where('reason_type', '=', $this->orderContract->getMap())
            ->findFirst(['franchise_id', 'park_id', 'payment_type_id', 'side_id', 'side_type', 'reason_id', 'reason_type', 'type']);

        if (!$transaction || !$driver) {
            throw new Lexcept('Not right order changes', 400);
        }

        $tariff = $this->tariffContract->getTariffByOrder($order_id);

        $order = $this->orderContract
            ->with([
                'process' => fn($query) => $query->select([
                    'order_process_id',
                    'order_process_id',
                    'price',
                    'total_price',
                    'calculate_price'
                ]),
                'completed' => fn($query) => $query->select([
                    'completed_order_id',
                    'order_id',
                    'cost',
                    'distance',
                    'duration',
                    'distance_price',
                    'duration_price',
                ]),
                'crossing' => fn($query) => $query->select([
                    'in_distance',
                    'out_distance',
                    'in_duration',
                    'out_duration',
                    'in_price',
                    'in_distance_price',
                    'in_duration_price',
                    'out_price',
                    'out_distance_price',
                    'out_duration_price'
                ]),
            ])
            ->find($order_id, ['order_id']);

        if (!$order && !$this->tariffReCalculate($tariff, $order, $distance, $duration, $cross, $cost)) {
            throw new Lexcept('Not right order changes by tariff destination', 400);
        }

        $completed = $this->completedOrderContract
            ->with([
                'crossing' => fn($query) => $query->select([
                    'in_distance',
                    'out_distance',
                    'in_duration',
                    'out_duration',
                    'in_price',
                    'in_distance_price',
                    'in_duration_price',
                    'out_price',
                    'out_distance_price',
                    'out_duration_price'
                ])
            ])
            ->firstWhere(['order_id', '=', $order_id], [
                'completed_order_id',
                'order_id',
                'destination_address',
                'distance',
                'duration',
                'cost',
            ]);

        $this->paymentService->workerOrderReCalc($order_id, $driver->driver_id, $completed->cost, $transaction->payment_type_id);

        return $completed;
    }

    /**
     * @param  array  $payload
     * @return mixed
     */
    /**
     * @param  array  $payload
     * @return LengthAwarePaginator
     */
    public function getClients(array $payload): LengthAwarePaginator
    {
        $per_page = $payload['per_page'] && is_numeric($payload['per_page']) ? $payload['per_page'] : '25';
        $page = is_numeric($payload['page']) ?: 1;

        return $this->clientContract
            ->has('completed_orders')
            ->withCount(['completed_orders', 'canceled_orders', 'assessed'])
            ->withSum('completed_orders', 'cost')
            ->paginate($per_page, ['*'], 'page', $page);
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function changeOrderToManuality($order_id, $worker_id)
    {
        $this->workerContract->beginTransaction(fn() => [
            $this->commonContract->updateOrCreate(['order_id', '=', $order_id], ['manual' => true, 'emergency' => true]),
            $this->orderContract->update($order_id, ['dist_type' => ConstOrderDistType::manual()->getValue()]),
        ]);

        $shipped = $this->shippedContract
            ->where('order_id', '=', $order_id)
            ->where(fn(Builder $query) => $query
                ->where('status_id', '=', OrderShippedStatus::PRE_PENDING)
                ->orWhere('status_id', '=', OrderShippedStatus::PRE_ACCEPTED)
            )
            ->findFirst('order_shipped_driver_id', 'order_id', 'status_id', 'driver_id');

        if (!$shipped) {
            return true;
        }

        $this->shippedContract->update($shipped->order_shipped_driver_id, ['current' => false, 'status_id' => OrderShippedStatus::PRE_MANUAL]);

        $driver = $this->driverContract->find($shipped->driver_id, ['driver_id', 'car_id', 'current_franchise_id', 'phone']);
        $preorder = $this->preorderContract->where('order_id', '=', $order_id)->findFirst(['preorder_id', 'order_id']);

        if ($driver) {
            $this->driverContract->update($driver->driver_id, ['current_status_id' => DriverStatus::DRIVER_IS_FREE]);

            if ($preorder) {
                $this->preorderContract->update($preorder->preorder_id, ['distribution_start' => null]);
                CommonOrderEvent::broadcast($driver, new PassOrderResource(['order_id' => $order_id]), 'delete');
            } else {
                $order = $this->orderContract->find($order_id, ['order_id', 'address_from', 'address_to']);
                ClientOrderCancel::broadcast($driver, $order);
            }
        }

        return true;
    }

    /**
     * @param $order_id
     * @param $driver_id
     * @return bool
     * @throws Exception
     */
    public function orderDriverUnpin($order_id, $driver_id): bool
    {
        $this->orderContract->beginTransaction(fn() => [
            $this->orderContract->forgetCache(),
            $this->driverContract->update($driver_id, ['current_status_id' => DriverStatus::DRIVER_IS_FREE]),
            $this->orderContract->update($order_id, ['status_id' => OrderStatus::ORDER_PENDING, 'manual' => ConstOrderDistType::manual()->getValue()]),
            $this->commonContract->updateOrCreate(['order_id', '=', $order_id], ['order_id' => $order_id, 'emergency' => true, 'manual' => true]),
            $this->shippedContract
                ->where('order_id', '=', $order_id)
                ->where('driver_id', '=', $driver_id)
                ->updateSet(['status_id' => OrderShippedStatus::PRE_UNPIN, 'current' => false]),
        ]);

        if ($this->redis()->hExists(ConstRedis::order_create_data($order_id), 'order_data')) {
            $this->redis()->hDel(ConstRedis::order_create_data($order_id), ...['order_data', 'price_data']);
        }

        $order = $this->orderContract->find($order_id, ['order_id', 'address_from', 'from_coordinates']);
        $driver = $this->driverContract->find($driver_id, ['driver_id', 'car_id', 'current_franchise_id', 'phone']);
        $client = $this->clientService->getOrderedClientData($order_id, ['client_id', 'phone']);

        ClientOrderCancel::broadcast($driver, $order);
        OrderReset::broadcast($client, ['continue' => true, 'message' => 'ok']);

        return true;
    }

    /**
     * @param  int  $order_id
     * @param  string  $time
     * @param  bool  $now
     * @param  int|null  $driver_id
     * @return mixed
     * @throws Lexcept
     */
    public function changePreorderData(int $order_id, string $time, bool $now, int $driver_id = null): bool
    {
        if ($now && $driver_id) {
            return $this->preparePreorderFirstEtap($order_id, $driver_id);
        }

        return throw new Lexcept('unsupported option in development', 500);
        if (!$now && ($driver_id && $time)) {
            return $this->preparePreorderSecondEtap($order_id, $driver_id, $time);
        }
    }

    /**
     * @throws Lexcept
     */
    public function getDriverForEditOrder(int $order_id, int $radius, int $type): Collection
    {
        $order = $this->ordersContract->find($order_id, ['order_id', 'from_coordinates', 'lat', 'lut']);

        if (!$order) {
            throw new Lexcept('Error invalid order', 500);
        }

        $cords = $order->lat ? ['lat' => $order->lat, 'lut' => $order->lut] : $order->from_coordinates;

        return $this->driverContract
            ->where('current_franchise_id', '=', FRANCHISE_ID)
            ->cordDistance($cords['lat'], $cords['lut'])
            ->when(!$radius, fn($query) => $query
                ->havingRaw('distance <= 120000')
            )
            ->when($radius, fn($query) => $query
                ->havingRaw('distance <= '.$radius * 1000)
            )
            ->when(OrderCommon::TYPE_ONLINE === $type, fn(Builder $query) => $query
                ->where('online', '=', true)
                ->where('is_ready', '=', true)
            )
            ->when(ConstDriverType::AGGREGATOR()->getValue() === $type, fn(Builder $query) => $query
                ->whereHas('type', fn($query) => $query->where('driver_types.driver_type_id', '=', ConstDriverType::AGGREGATOR()->getValue()))
            )
            ->when(ConstDriverType::TENANT()->getValue() === $type, fn(Builder $query) => $query
                ->whereHas('type', fn($query) => $query->where('driver_types.driver_type_id', '=', ConstDriverType::TENANT()->getValue()))
            )
            ->when(ConstDriverType::ROLL()->getValue() === $type, fn(Builder $query) => $query
                ->whereHas('type', fn($query) => $query->where('driver_types.driver_type_id', '=', ConstDriverType::ROLL()->getValue()))
            )
            ->when(ConstDriverType::CORPORATE()->getValue() === $type, fn(Builder $query) => $query
                ->whereHas('type', fn($query) => $query->where('driver_types.driver_type_id', '=', ConstDriverType::CORPORATE()->getValue()))
            )
            ->with([
                'driver_info' => fn(BelongsTo $query) => $query->select(['driver_info_id', 'name', 'surname', 'patronymic']),
                'car' => fn(BelongsTo $query) => $query->select(['car_id', 'mark', 'model', 'year', 'color', 'state_license_plate']),
                'status' => fn(BelongsTo $query) => $query->select(['driver_status_id', 'status', 'text', 'color']),
                'order_shipment' => fn(HasOne $query) => $query
                    ->where('order_id', '=', $order_id)
                    ->where('status_id', '=', OrderShippedStatus::PRE_ACCEPTED)
                    ->select(['driver_id', 'order_id', 'order_shipped_driver_id', 'status_id']),
            ])
            ->findAll(['driver_id', 'current_franchise_id', 'current_status_id', 'driver_info_id', 'car_id', 'phone', 'distance']);
    }

    /**
     * @inheritdoc
     * @throws ReflectionException
     */
    #[ArrayShape([
        'types' => 'array[]',
        'radius' => 'array[]'
    ])]
    public function getDriverForEditFilters(int $order_id = null): array
    {
        $types = [
            [
                'value' => ConstDriverType::CORPORATE()->getValue(),
                'text' => trans('messages.driver_type_'.strtolower(ConstDriverType::CORPORATE()->getKey()))
            ],
            ['value' => ConstDriverType::TENANT()->getValue(), 'text' => trans('messages.driver_type_'.strtolower(ConstDriverType::TENANT()->getKey()))],
            ['value' => ConstDriverType::ROLL()->getValue(), 'text' => trans('messages.driver_type_'.strtolower(ConstDriverType::ROLL()->getKey()))],
            [
                'value' => ConstDriverType::AGGREGATOR()->getValue(),
                'text' => trans('messages.driver_type_'.strtolower(ConstDriverType::AGGREGATOR()->getKey()))
            ],
            ['value' => OrderCommon::TYPE_ONLINE, 'text' => trans('words.online')],
            ['value' => OrderCommon::TYPE_ALL, 'text' => trans('words.all')],
        ];

        $distances = [
            ['value' => 5, 'text' => '5 KM'],
            ['value' => 10, 'text' => '10 KM'],
            ['value' => 15, 'text' => '15 KM'],
            ['value' => 20, 'text' => '20 KM'],
            ['value' => 25, 'text' => '25 KM'],
            ['value' => 50, 'text' => '50 KM'],
        ];

        return ['types' => $types, 'radius' => $distances];
    }

    /**
     * @throws Lexcept
     */
    public function sendOrderToDriversList(int $order_id, array $drivers, int $radius, $filter_type): bool
    {
        $common_list = $this->commonContract
            ->where('order_id', '=', $order_id)
            ->firstLatest('order_common_id', ['order_id', 'driver', 'order_common_id']);

        if ($common_list) {
            $drivers = $common_list->driver['ids'] ? array_diff($drivers, $common_list->driver['ids']) : $drivers;
        }

        $drivers = $this->driverContract->findWhereIn(['driver_id', $drivers], ['driver_id', 'car_id', 'current_franchise_id', 'phone']);

        $order = $this->ordersContract
            ->with([
                'initial_data' => fn($query) => $query->select(['order_id', 'price', 'distance', 'duration']),
                'preorder' => fn($query) => $query->select(['order_id', 'time']),
            ])
            ->find($order_id, ['order_id', 'address_from', 'comments']);

        if (!$order) {
            throw new Lexcept('Invalid order', 422);
        }

        $order_resource = new PassOrderResource([
            'order_id' => $order->order_id,
            'address_from' => $order->address_from,
            'cash' => $order->initial_data->price,
            'delivery_time' => $order->preorder ? Carbon::parse($order->preorder->time)->format('y/m/d H:i') : '0'
        ]);

        try {
            $send_drivers = [];

            foreach ($drivers as $driver) {
                CommonOrderEvent::broadcast($driver, $order_resource);
                $send_drivers[] = $driver->driver_id;
            }

            if ($send_drivers && !$this->commonContract->create([
                    'order_id' => $order_id,
                    'distance' => $radius,
                    'filter_type' => $filter_type,
                    'driver' => ['ids' => array_map('\intval', array_values($send_drivers))]
                ])
            ) {
                return false;
            }
        } catch (Exception) {
            return false;
        }

        $common_list ? $this->commonContract->update($common_list->order_common_id, ['active' => false]) : null;

        return true;
    }
}
