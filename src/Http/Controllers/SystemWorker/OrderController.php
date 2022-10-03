<?php

namespace Src\Http\Controllers\SystemWorker;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Src\Http\Controllers\Controller;
use Src\Services\Order\OrderServiceContract;

class OrderController extends Controller
{
    /**
     * OrderController constructor.
     * @param  OrderServiceContract  $orderServiceContract
     */
    public function __construct(protected OrderServiceContract $orderServiceContract)
    {
    }

    /**
     * @param $order_id
     * @return Application|ResponseFactory|Response
     */
    public function info($order_id): Response|Application|ResponseFactory
    {
        return response($this->orderServiceContract->viewOrderInfo($order_id));
    }
}
