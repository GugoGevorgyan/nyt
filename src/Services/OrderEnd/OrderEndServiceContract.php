<?php

declare(strict_types=1);


namespace Src\Services\OrderEnd;


use Exception;
use Illuminate\Support\Collection;
use ServiceEntity\Contract\BaseContract;

/**
 * Interface OrderEndServiceContract
 * @package Src\Services\OrderEnd
 */
interface OrderEndServiceContract extends BaseContract
{
    /**
     * @param $driver_id
     * @param $order_id
     * @param $hash
     * @return object|null
     */
    public function driverShippedOrderReject($driver_id, $order_id, $hash): ?object;

    /**
     * @return Collection
     */
    public function getFeedbackOptions(): Collection;

    /**
     * @param $client_id
     * @return mixed
     */
    public function clientCancelOrder($client_id);

    /**
     * @param  int  $client_id
     * @param  bool  $cancel
     * @return mixed
     */
    public function clientAcceptCancel(int $client_id, bool $cancel = false);

    /**
     * @param $order_id
     * @param $client_id
     * @return bool
     */
    public function companyOrderEnd($order_id, $client_id): bool;

    /**
     * @param $client_id
     * @param $aborted_order_id
     * @param  array  $feedback
     * @return bool
     */
    public function createClientAbortedFeedback($client_id, $aborted_order_id, array $feedback = []): bool;

    /**
     * @param $client_id
     * @param $completed_order_id
     * @param  array  $feedback
     * @param  bool|null  $favorite
     * @return bool
     */
    public function clientCompletedFeedback($client_id, $completed_order_id, array $feedback, bool $favorite = null): bool;

    /**
     * @param $driver_id
     * @param $completed_order_id
     * @param  int  $assessment
     * @param  int|null  $option
     * @param  string|null  $text
     * @return bool
     */
    public function driverCompletedFeedback(
        $driver_id,
        $completed_order_id,
        int $assessment,
        int $option = null,
        string $text = null,
        $slip_number = null
    ): bool;

    /**
     * @param  int  $assessment
     * @param  bool  $client
     * @param  bool  $completed
     * @return Collection|null
     */
    public function getFeedbackByAssessment(int $assessment, bool $client = true, bool $completed = true): ?Collection;

    /**
     * @param $order_id
     * @param $cords
     * @param $order_shipment
     * @param  Collection  $price
     * @return void
     * @throws Exception
     */
    public function driverOrderEndStatuses($order_id): void;

    /**
     * @param $payload
     * @return Collection
     */
    public function orderEndreCalculate($payload): Collection;

    /**
     * @param $driver_id
     * @param $order_id
     * @return mixed
     */
    public function getCompletedOrderId($driver_id, $order_id);

    /**
     * @param $order_id
     * @return Collection
     */
    public function slipMaster(int $order_id): Collection;

    /**
     * @param $order_id
     * @return mixed
     */
    public function callCenterCancelOrder($order_id);

    /**
     * @param $order_id
     * @return bool
     */
    public function orderEndStatuses($order_id): bool;
}
