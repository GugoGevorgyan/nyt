<?php

declare(strict_types=1);

namespace Src\Http\Controllers\Terminal;

use Src\Http\Controllers\Controller;
use Src\Http\Requests\Terminal\AddedCasheRequest;
use Src\Http\Requests\Terminal\PayBalanceRequest;
use Src\Http\Resources\Terminal\AddCashResource;
use Src\Http\Resources\Terminal\PayBalanceResource;
use Src\Services\Terminal\TerminalServiceContract;

/**
 * Class CashController
 * @package Src\Http\Controllers\Terminal
 */
class CashController extends Controller
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
     * @param  AddedCasheRequest  $request
     * @return AddCashResource
     */
    public function addedCash(AddedCasheRequest $request): AddCashResource
    {
        $check = $this->terminalService->addedCurrentCash(get_user_id(), $request->cash, $request->type);

        return new AddCashResource($check);
    }

    /**
     * @param  PayBalanceRequest  $request
     * @return PayBalanceResource
     */
    public function payBalance(PayBalanceRequest $request): PayBalanceResource
    {
        $resource = $this->terminalService->payBalance(get_user_id(), $request->cash);

        return new PayBalanceResource($resource);
    }

}
