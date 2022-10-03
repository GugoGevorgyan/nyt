<?php

declare(strict_types=1);

namespace Src\Http\Controllers\SystemWorker;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;
use Src\Http\Controllers\Controller;
use Src\Http\Requests\SystemWorker\PenaltyRequest;
use Src\Http\Resources\BaseResource;
use Src\Http\Resources\PaginateResource;
use Src\Http\Resources\Penalty\PenaltyResource;
use Src\Services\Debt\DebtService;

class PenaltyController extends Controller
{
    public function __construct(protected DebtService $debtService)
    {
    }

    /**
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('system-worker.penalties.index');
    }

    /**
     * @param PenaltyRequest $request
     * @return BaseResource|PaginateResource
     */
    public function pager(PenaltyRequest $request): PaginateResource|BaseResource
    {
        $penalties = $this->debtService->getPenalties($request->validated());

        return (new PaginateResource($penalties))->collectionClass(PenaltyResource::class);
    }

    /**
     * @param $debt_id
     * @param $value
     * @return Application|ResponseFactory|Response
     */
    public function payDebtToFirm($debt_id, $value)
    {
        $update = $this->debtService->toPay($debt_id,$value);

        if ($update) {
            return response(trans('messages.penalty_firm_paid_value_updated'),200);
        }

        return response('Server Error',500);

    }
}
