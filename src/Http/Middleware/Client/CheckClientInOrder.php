<?php

declare(strict_types=1);

namespace Src\Http\Middleware\Client;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Src\Models\Order\Order;
use Src\Repositories\Client\ClientContract;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Http\Request;

/**
 * Class CheckClientInOrder
 * @package Src\Http\Middleware\ClientMessage
 */
class CheckClientInOrder
{
    /**
     * @var ClientContract
     */
    protected $clientContract;

    /**
     * CheckClientInOrder constructor.
     * @param  ClientContract  $clientContract
     */
    public function __construct(ClientContract $clientContract)
    {
        $this->clientContract = $clientContract;
    }

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $client_in_order = $this->clientContract->where($this->clientContract->getKeyName(), user()->{user()->getKeyName()})
            ->with([
                'orders' =>
                    static function (HasMany $order_query) {
                        $order_query
                            ->whereNotNull('status_id')
                            ->whereHas('status', static function (Builder $status_query) {
                                $status_query
                                    ->where('status', '!=', Order::ORDER_STATUS_CANCELED)
                                    ->where('status', '!=', Order::ORDER_STATUS_COMPLETED);
                            })
                            ->select(['order_id', 'client_id', 'status_id']);
                    }
            ])
            ->findFirst(['client_id']);

        if ($client_in_order->orders->count() > 0) {
            return response(['message' => 'You are already in order'], 412);
        }

        return $next($request);
    }
}
