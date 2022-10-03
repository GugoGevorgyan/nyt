<?php

declare(strict_types=1);

namespace Src\Http\Controllers\Terminal;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Src\Http\Controllers\Controller;
use Src\Http\Requests\Terminal\PayWaybillRequest;
use Src\Http\Requests\terminal\SelectedWaybillDaysRequest;
use Src\Http\Requests\Terminal\UploadWaybillRequest;
use Src\Http\Resources\Terminal\PayWaybillResource;
use Src\Services\Terminal\TerminalServiceContract;

/**
 * Class WaybillController
 * @package Src\Http\Controllers\Terminal
 */
class WaybillController extends Controller
{
    /**
     * @var TerminalServiceContract
     */
    protected TerminalServiceContract $terminalService;

    /**
     * WaybillController constructor.
     * @param  TerminalServiceContract  $terminalService
     */
    public function __construct(TerminalServiceContract $terminalService)
    {
        $this->terminalService = $terminalService;
    }

    /**
     * @param PayWaybillRequest $request
     * @return AnonymousResourceCollection|Application|Response|ResponseFactory
     */
    public function payWaybill(PayWaybillRequest $request): Response|AnonymousResourceCollection|Application|ResponseFactory
    {
        $result = $this->terminalService->payWaybill(get_user_id(), $request->cash, $request->deposit, $request->days);

        if (!$result) {
            return response(['message' => 'Server error'], 500);
        }

        return PayWaybillResource::collection($result['waybills']);
    }

    /**
     * @param  UploadWaybillRequest  $request
     * @return Application|ResponseFactory|Response
     */
    public function upload(UploadWaybillRequest $request): Response|Application|ResponseFactory
    {
        $upload = $this->terminalService->uploadWaybill($request->transaction_id, $request->file('waybill'));

        if (!$upload) {
            return response(['message' => 'error'], 500);
        }

        return response(['message' => 'Uploaded', 'status' => 'OK']);
    }

    /**
     * @param  SelectedWaybillDaysRequest  $request
     * @return array
     */
    public function getSelectedWaybillDaysPrice(SelectedWaybillDaysRequest $request)
    {
       return $this->terminalService->selectedWaybillDaysPrice($request->user()->{$request->user()->getKeyName()}, $request->days);
    }
}
