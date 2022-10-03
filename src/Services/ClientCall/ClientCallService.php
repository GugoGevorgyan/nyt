<?php

declare(strict_types=1);


namespace Src\Services\ClientCall;

use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use ServiceEntity\BaseService;
use Src\Http\Resources\Atc\AtcCallResource;
use Src\Http\Resources\Atc\AtcErrorResource;
use Src\Repositories\Client\ClientContract;
use Src\Repositories\ClientCall\ClientCallContract;
use Src\Repositories\Driver\DriverContract;
use Src\Repositories\FranchisePhone\FranchisePhoneContract;
use Src\Repositories\FranchiseSubPhone\FranchiseSubPhoneContract;
use Src\Services\Client\ClientServiceContract;
use Src\Services\Order\OrderServiceContract;

use function count;

/**
 * Class ClientCallService
 * @package Src\Services\ClientCall
 */
class ClientCallService extends BaseService implements ClientCallServiceContract
{
    /**
     * ClientCallService constructor.
     * @param  ClientCallContract  $baseContract
     * @param  ClientContract  $clientContract
     * @param  FranchisePhoneContract  $franchisePhoneContract
     * @param  ClientServiceContract  $clientService
     * @param  FranchiseSubPhoneContract  $franchiseSubPhoneContract
     * @param  DriverContract  $driverContract
     * @param  OrderServiceContract  $orderServiceContract
     */
    public function __construct(
        protected ClientCallContract $baseContract,
        protected ClientContract $clientContract,
        protected FranchisePhoneContract $franchisePhoneContract,
        protected ClientServiceContract $clientService,
        protected FranchiseSubPhoneContract $franchiseSubPhoneContract,
        protected DriverContract $driverContract,
        protected OrderServiceContract $orderServiceContract
    ) {
    }

    /**
     * @param $request
     * @return JsonResponse|object|AtcCallResource
     */
    public function callReceivingData($request)
    {
        /*get franchise phone*/
        $franchisePhone = $this->franchisePhoneValues($request->BNumber);

        if (!$franchisePhone) {
            return (new AtcErrorResource(['message' => 'Oops! Something went wrong.']))->response()->setStatusCode(500);
        }

        /*get or create client*/
        $client = $this->getCaller($request->cellNumber);
        if (!$client) {
            return (new AtcErrorResource(['message' => 'Oops! Something went wrong.']))->response()->setStatusCode(500);
        }

        /*create call*/
        $call = $this->createCall($client, $franchisePhone, $request->cellNumber);

        if (!$call) {
            return (new AtcErrorResource(['message' => 'Oops! Something went wrong.']))->response()->setStatusCode(500);
        }

        /*check caller is driver*/
        $driver = $this->clientCallDriver($request->cellNumber, $request->BNumber);

        if ($driver) {
            return new AtcCallResource(
                [
                    'type' => 'driver',
                    'extension' => 101
                ]
            );
        }

        /*find recent operator sub phone*/
        $lastCall = $this->clientRecentlyCall($request->cellNumber, $request->BNumber);

        if ($lastCall) {
            return new AtcCallResource(
                [
                    'type' => 'normal',
                    'extension' => $lastCall->franchiseSubPhone->number
                ]
            );
        }


//        /*find available sub phone*/
//        $readyOperators = $this->workerOperatorService->getOperatorsByStatus('ready')->load('sub_phone');
//        $availableSubPhoneNumber = count($readyOperators) > 0 ? $readyOperators->first()->sub_phone->number : null;
//
//        return new AtcCallResource([
//            'type' => 'normal',
//            'extension' => $availableSubPhoneNumber
//        ]);

        return new AtcCallResource(
            [
                'type' => 'normal',
                'extension' => null
            ]
        );
    }

    /**
     * @param $number
     * @return FranchisePhoneContract
     */
    public function franchisePhoneValues($number)
    {
        return $this->franchisePhoneContract->where('number', '=', $number)->findFirst();
    }

    public function getCaller($clientPhone)
    {
        $client = $this->clientContract->where('phone', '=', $clientPhone)->findFirst();
        return $client ?: $this->clientService->createClient($clientPhone);
    }

    /**
     * @param $client
     * @param $franchisePhone
     * @param $cellNumber
     * @return mixed
     */
    public function createCall($client, $franchisePhone, $cellNumber)
    {
        return $this->baseContract->create(
            [
                'franchise_id' => $franchisePhone->franchise_id,
                'franchise_phone_id' => $franchisePhone->franchise_phone_id,
                'client_phone' => $cellNumber,
                'client_id' => $client->client_id
            ]
        );
    }

    /**
     * @param $clientPhone
     * @param $franchisePhone
     * @return mixed
     */
    public function clientCallDriver($clientPhone, $franchisePhone)
    {
        return $this->driverContract
            ->whereHas(
                'current_franchise',
                function ($q) use ($franchisePhone) {
                    $q->whereHas(
                        'phones',
                        function ($q) use ($franchisePhone) {
                            $q->where('number', '=', $franchisePhone);
                        }
                    );
                }
            )
            ->where('phone', '=', $clientPhone)
            ->findFirst();
    }

    /**
     * @param $clientPhone
     * @param $franchisePhone
     * @return Builder|Model|mixed|object|null
     */
    public function clientRecentlyCall($clientPhone, $franchisePhone)
    {
        return $this->baseContract
            ->whereHas(
                'franchisePhone',
                function ($q) use ($franchisePhone) {
                    return $q->where('number', $franchisePhone);
                }
            )
            ->where('client_phone', '=', $clientPhone)
            ->where('call_start', '>', Carbon::now()->subMinutes(10))
            ->with(['franchiseSubPhone'])
            ->latest()
            ->first();
    }

    /**
     * @param $cellNumber
     * @param $subPhone
     * @return array|mixed|null
     */
    public function connectWorker($cellNumber, $subPhone): ?array
    {
        $workerable = $this->getWorkerable($subPhone);

        $call = $this->baseContract
            ->where('client_phone', '=', $cellNumber)
            ->where('franchise_sub_phone_id', '=', null)
            ->latest()
            ->first();

        if (!$call) {
            return null;
        }

        $subPhoneObj = $this->getSubPhoneByNumber($subPhone);

        if (!$subPhoneObj) {
            return null;
        }

        $call->update(
            [
                'call_start' => Carbon::now()->format('Y-m-d H:i:s.u'),
                'system_worker_id' => user()->system_worker_id,
                'workerable_id' => $workerable['id'],
                'workerable_type' => $workerable['type'],
                'franchise_sub_phone_id' => $subPhoneObj->franchise_sub_phone_id,
                'answered' => true
            ]
        );

        return $this->callClientValues($call);
    }

    /**
     * @param $subPhone
     * @return array|null
     */
    protected function getWorkerable($subPhone): ?array
    {
        $worker = $this->getWorkerBySubPhone($subPhone);

        if ($worker['dispatcher']) {
            $workerable_id = $worker['dispatcher']->worker_dispatcher_id;
            $workerable_type = $worker['dispatcher']->getMap();
        } elseif ($worker['operator']) {
            $workerable_id = $worker['operator']->worker_operator_id;
            $workerable_type = $worker['operator']->getMap();
        } else {
            return null;
        }

        return ['id' => $workerable_id, 'type' => $workerable_type];
    }

    /**
     * @param $subPhone
     * @return array|null
     */
    protected function getWorkerBySubPhone($subPhone): ?array
    {
        $worker = $this->franchiseSubPhoneContract
            ->where('number', '=', $subPhone)
            ->whereHas('franchisePhone', fn($q) => $q->where('franchise_id', '=', FRANCHISE_ID))
            ->where(fn($q) => $q->whereHas('operators', fn($q) => $q->where('system_worker_id', '=', auth()->user()->system_worker_id))
                ->orWhereHas('dispatchers', fn($q) => $q->where('system_worker_id', '=', auth()->user()->system_worker_id)))
            ->with(
                [
                    'dispatchers' => fn($q) => $q->where('system_worker_id', '=', auth()->user()->system_worker_id),
                    'operators' => fn($q) => $q->where('system_worker_id', '=', auth()->user()->system_worker_id),
                ]
            )
            ->findFirst();

        if (!$worker) {
            return null;
        }

        return [
            'dispatcher' => count($worker->dispatchers) ? $worker->dispatchers[0] : null,
            'operator' => count($worker->operators) ? $worker->operators[0] : null
        ];
    }

    /**
     * @param $number
     * @return mixed
     */
    public function getSubPhoneByNumber($number)
    {
        return $this->franchiseSubPhoneContract
            ->where('number', '=', $number)
            ->whereHas(
                'franchisePhone',
                function ($q) {
                    return $q->where('franchise_id', '=', FRANCHISE_ID);
                }
            )
            ->findFirst();
    }

    /**
     * @param $call
     * @return array
     */
    protected function callClientValues($call)
    {
        $client = $this->clientContract->find($call->client_id);
        $companies = $client->corporateCompanies()->get();
        $orders = $this->orderServiceContract->getLastOrders($client->client_id);

        return compact('call', 'client', 'companies', 'orders');
    }

    /**
     * @param $cellNumber
     * @param $subPhone
     * @return object|null
     */
    public function callStart($cellNumber, $subPhone): ?object
    {
        $subNumberObj = $this->getSubPhoneByNumber($subPhone);
        $workerable = $this->getWorkerable($subPhone);

        /*get or create client*/
        $client = $this->getCaller($cellNumber);

        if (!$client) {
            return null;
        }

        /*create call*/
        return $this->baseContract->create(
            [
                'franchise_id' => FRANCHISE_ID,
                'franchise_phone_id' => $subNumberObj->franchise_phone_id,
                'franchise_sub_phone_id' => $subNumberObj->franchise_sub_phone_id,
                'system_worker_id' => auth()->user()->system_worker_id,
                'workerable_id' => $workerable['id'],
                'workerable_type' => $workerable['type'],
                'client_phone' => $cellNumber,
                'client_id' => $client->client_id,
                'incoming' => 0
            ]
        );
    }

    /**
     * @param $call_id
     * @return mixed
     */
    public function callAnswered($call_id)
    {
        return $this->baseContract->update($call_id, [
            'call_start' => Carbon::now()->format('Y-m-d H:i:s.u'),
            'answered' => true
        ]);
    }

    /**
     * @param $cellNumber
     * @param $subPhone
     * @return bool|mixed|object|null
     */
    public function callEnd($cellNumber, $subPhone)
    {
        $call = $this->baseContract
            ->where('client_phone', '=', $cellNumber)
            ->whereHas('franchiseSubPhone', fn($q) => $q->where('number', '=', $subPhone))
            ->where('call_end', '=', null)
            ->with('workerable')
            ->firstLatest();

        if (!$call) {
            return null;
        }

        $duration = $call->call_start ? strtotime(Carbon::now()->format('Y-m-d H:i:s.u')) - strtotime($call->call_start) : 0;

        return $this->baseContract->update($call->client_call_id, [
            'call_end' => Carbon::now()->format('Y-m-d H:i:s.u'),
            'call_duration' => $duration
        ]);
    }

    /**
     * @inheritDoc
     */
    public function getWorkerCalls($subPhone, $days): Collection
    {
        $sub_phone = $this->getSubPhoneByNumber($subPhone);

        return $this->baseContract
            ->where('system_worker_id', '=', auth()->user()->system_worker_id)
            ->when($sub_phone, fn($query) => $query->where('franchise_sub_phone_id', '=', $sub_phone->franchise_sub_phone_id))
            ->where('created_at', '>', Carbon::now()->subDays($days))
            ->with('client')
            ->orderBy('created_at', 'desc')
            ->findAll([
                'client_call_id',
                'franchise_id',
                'franchise_phone_id',
                'franchise_sub_phone_id',
                'system_worker_id',
                'client_id',
                'workerable_id',
                'workerable_type',
                'client_phone',
                'call_start',
                'call_end',
                'call_duration',
                'incoming',
                'answered',
            ]);
    }

    /**
     * @inheritDoc
     */
    public function atcLogged($request): bool // @todo fix for cache query
    {
        $subPhone = $this->getWorkerBySubPhone($request->subPhone);

        if (!$subPhone) {
            return false;
        }

        if ($subPhone['dispatcher']) {
            return $subPhone['dispatcher']->update(['atc_logged' => $request->logged]);
        }

        if ($subPhone['operator']) {
            return $subPhone['operator']->update(['atc_logged' => $request->logged]);
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function dispatcherCallsPaginate($request): LengthAwarePaginator
    {
        $per_page = $request['per-page'] && is_numeric($request['per-page']) ? $request['per-page'] : '25';
        $page = $request->page && is_numeric($request->page) ?: 1;
        $search = ($request->search && 'null' !== $request->search) ? $request->search : false;

        return $this->baseContract
            ->where('franchise_id', '=', FRANCHISE_ID)
            ->when($search, fn($q) => $q->where(
                fn($q) => $q
                    ->where('client_phone', 'LIKE', '%'.$search.'%')
                    ->orWhereHas(
                        'system_worker', fn($q) => $q->where('name', 'LIKE', '%'.$search.'%')
                        ->orWhere('surname', 'LIKE', '%'.$search.'%')
                        ->orWhere('patronymic', 'LIKE', '%'.$search.'%')
                    )
            ))
            ->with(['system_worker', 'client', 'franchisePhone', 'franchiseSubPhone'])
            ->orderBy('client_call_id', 'desc')
            ->paginate($per_page, ['*'], 'page', $page);
    }
}
