<?php

declare(strict_types=1);

namespace Src\Http\Controllers\Terminal;

use Collective\Annotations\Routing\Annotations\Annotations\Get;
use Collective\Annotations\Routing\Annotations\Annotations\Post;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Src\Http\Controllers\Controller;
use Src\Http\Requests\Terminal\PayDebtOffRequest;
use Src\Http\Resources\Terminal\GetDebtResource;
use Src\Http\Resources\Terminal\PayDebtOffResource;
use Src\Services\Terminal\TerminalServiceContract;

/**
 * Class DebtController
 * @package Src\Http\Controllers\Terminal
 */
class DebtController extends Controller
{
    /**
     * @var TerminalServiceContract
     */
    protected TerminalServiceContract $terminalService;

    /**
     * DebtController constructor.
     * @param  TerminalServiceContract  $terminalService
     */
    public function __construct(TerminalServiceContract $terminalService)
    {
        $this->terminalService = $terminalService;
    }

    /**
     * @Get("get_debts", as="app/terminal", where={""}, no_prefix="true", name="terminal_get_debts")
     *
     * @param  Request  $request
     * @return GetDebtResource
     */
    public function getDebts(Request $request): GetDebtResource
    {
        $debt = $this->terminalService->getDriverDebt($request->user()->{$request->user()->getKeyName()});

        return new GetDebtResource($debt);
    }

    /**
     * @Post("pay_debt_off", as="app/terminal", where={""}, no_prefix="true", name="terminal_debt_off")
     *
     * @param  PayDebtOffRequest  $request
     * @return Application|ResponseFactory|Response|PayDebtOffResource
     */
    public function payDebt(PayDebtOffRequest $request)
    {
        $debt = $this->terminalService->payDebt(user()->{user()->getKeyName()}, $request->cash, $request->deposit);

        if (!$debt) {
            return response(['message' => 'You are not debt']);
        }

        return new PayDebtOffResource($debt);
    }
}
