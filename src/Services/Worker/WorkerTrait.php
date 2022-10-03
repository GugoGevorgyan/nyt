<?php

declare(strict_types=1);


namespace Src\Services\Worker;


use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\NoReturn;
use Src\Exceptions\Lexcept;
use Src\Models\Role\Role;

/**
 * Trait WorkerTrait
 * @package Src\Services\SystemWorker
 */
trait WorkerTrait
{
    /**
     * @var string
     * @link OrderPattern
     */
    private string $pattern = 'workerPattern';

    /**
     * @param  Collection  $tariff
     * @param  object  $order
     * @param $distance
     * @param $duration
     * @param $cross
     * @param $cost
     * @return bool
     * @throws Exception
     */
    protected function tariffReCalculate(Collection $tariff, object $order, $distance, $duration, $cross, $cost): bool
    {
        $result = false;

        if ($cost) {
            if ($cost < $tariff['minimal_price']) {
                throw new Lexcept('Cost is smalled can tariff minimal price minimal price is '.$tariff['minimal_price'], 400);
            }

            $this->completedOrderContract->beginTransaction(fn() => [
                $this->completedOrderContract->forgetCache(),
                $this->completedChangeContract->forgetCache(),

                $this->completedOrderContract->update($order->completed->completed_order_id, [
                    'cost' => $cost,
                    'changed' => true
                ]),
                $this->completedChangeContract->create([
                    'completed_id' => $order->completed->completed_order_id,
                    'changer_id' => get_user_id(),
                    'old_price' => $order->completed->cost,
                    'new_price' => $cost,
                ]),
            ]);

            $result = true;
        }

        if ($cross && $tariff['tariff_behind']) {
            if ($distance) {
                if ($distance > $order->completed->out_distance) {
                    $new_out_distance_price = ($distance - $order->completed->out_distance) * $tariff['tariff_behind']['price_km'];
                    $new_out_price = $order->completed->out_price + $new_out_distance_price;
                } else {
                    $new_out_distance_price = ($order->completed->out_distance - $distance) * $tariff['tariff_behind']['price_km'];
                    $new_out_price = $order->completed->out_price - $new_out_distance_price;
                }

                if ($new_out_price < $tariff['minimal_price']) {
                    $new_cost = $tariff['minimal_price'];
                } else {
                    $new_cost = $new_out_price;
                }

                $this->rewriteCrossDistanceChanges($order, $distance, $new_cost, $new_out_price, $new_out_distance_price);
            } elseif ($duration) {
                if ($duration > $order->completed->out_duration) {
                    $new_out_duration_price = ($duration - $order->completed->out_duration) * $tariff['tariff_behind']['price_min'];
                    $new_out_price = $order->completed->out_price + $new_out_duration_price;
                } else {
                    $new_out_duration_price = ($order->completed->out_duration - $duration) * $tariff['tariff_behind']['price_min'];
                    $new_out_price = $order->completed->out_price - $new_out_duration_price;
                }

                if ($new_out_price < $tariff['minimal_price']) {
                    $new_cost = $tariff['minimal_price'];
                } else {
                    $new_cost = $new_out_price;
                }

                $this->rewriteCrossDurationChanges($order, $duration, $new_cost, $new_out_price, $new_out_duration_price);
                $result = true;
            } else {
                $result = true;
            }
        }

        if (!$cross && $tariff['tariff_region']) {
            if ($distance) {
//                if ($distance > $order->completed->distance) {
//                    $new_distance_price = ($distance - $order->completed->distance) * $tariff['tariff_region']['price_km'];
//                    $new_cost = $order->completed->cost + $new_distance_price;
//                } else {
//                    $new_distance_price = ($order->completed->distance - $distance) * $tariff['tariff_region']['price_km'];
//                    $new_cost = $order->completed->cost - $new_distance_price;
//                }

                if ($tariff['tariff_region']['merge_km_minute']) {
                    if ($tariff['tariff_region']['minimal_distance_value'] && $tariff['tariff_region']['minimal_duration_value']) {
                        if ($distance > $tariff['tariff_region']['minimal_distance_value']) {
                            $new_price = ($distance - $tariff['tariff_region']['minimal_distance_value']) * $tariff['tariff_region']['price_km'] + $tariff['minimal_price'];

                            if ($order->completed->duration > $tariff['tariff_region']['minimal_duration_value']) {
                                $new_price += ($order->completed->duration - $tariff['tariff_region']['minimal_duration_value']) * $tariff['tariff_region']['price_min'];
                            }
                        }
                    } elseif ($tariff['tariff_region']['minimal_distance_value'] && !$tariff['tariff_region']['minimal_duration_value']) {
                        $tariff_minimal = ($tariff[['tariff_region']]['minimal_distance_value'] * $tariff['tariff_region']['price_km']);
                    } elseif (!$tariff['tariff_region']['minimal_distance_value'] && $tariff['tariff_region']['minimal_duration_value']) {
                        $tariff_minimal = ($tariff[['tariff_region']]['minimal_duration_value'] * $tariff['tariff_region']['price_min']);
                    }
                }

                if ($new_cost < $tariff['minimal_price']) {
                    $new_cost = $tariff['minimal_price'];
                }

                $this->rewriteDistanceChanges($order, $new_cost, $distance);
                $result = true;
            } elseif ($duration) {
                if ($duration > $order->completed->duration) {
                    $new_duration_price = ($duration - $order->completed->duration) * $tariff['tariff_region']['price_min'];
                } else {
                    $new_duration_price = ($order->completed->duration - $duration) * $tariff['tariff_region']['price_min'];
                }

                if ($duration > $order->completed->duration) {
                    $new_cost = $order->completed->cost + $new_duration_price;
                } else {
                    $new_cost = $order->completed->cost - $new_duration_price;
                }

                if ($new_cost < $tariff['minimal_price']) {
                    $new_cost = $tariff['minimal_price'];
                }

                $this->rewriteDuration($order, $new_cost, $duration);
                $result = true;
            }
        }

        return $result;
    }

    /**
     * @param $order
     * @param  null  $distance
     * @param  null  $new_cost
     * @param  null  $new_out_price
     * @param  null  $new_out_distance_price
     * @throws Exception
     */
    private function rewriteCrossDistanceChanges($order, $distance = null, $new_cost = null, $new_out_price = null, $new_out_distance_price = null): void
    {
        $this->completedOrderContract->beginTransaction(fn() => [
            $this->completedOrderContract->forgetCache(),

            $this->completedOrderContract->update($order->completed->completed_order_id, [
                'cost' => $new_cost,
                'out_price' => $new_out_price,
                'out_distance_price' => $new_out_distance_price,
                'out_distance' => $distance,
                'changed' => true
            ]),
            $this->completedChangeContract->create([
                'completed_id' => $order->completed->completed_order_id,
                'changer_id' => get_user_id(),
                'old_distance' => $order->completed->out_distance,
                'new_distance' => $distance,
                'old_price' => $order->completed->cost,
                'new_price' => $new_cost,
            ]),
        ]);
    }

    /**
     * @throws Exception
     */
    protected function rewriteCrossDurationChanges($order, $new_cost, $new_out_price, $new_out_duration_price, $duration): void
    {
        $this->completedOrderContract->beginTransaction(fn() => [
            $this->completedOrderContract->forgetCache(),

            $this->completedOrderContract->update($order->completed->completed_order_id, [
                'cost' => $new_cost,
                'out_price' => $new_out_price,
                'out_duration_price' => $new_out_duration_price,
                'out_duration' => $duration,
                'changed' => true
            ]),
            $this->completedChangeContract->create([
                'completed_id' => $order->completed->completed_order_id,
                'changer_id' => get_user_id(),

                'old_duration' => $order->completed->out_duration,
                'new_duration' => $duration,
                'old_price' => $order->completed->cost,
                'new_price' => $new_cost,
            ]),
        ]);
    }

    /**
     * @throws Exception
     */
    private function rewriteDistanceChanges($order, $new_cost, $distance): void
    {
        $this->completedOrderContract->beginTransaction(fn() => [
            $this->completedOrderContract->forgetCache(),

            $this->completedOrderContract->update($order->completed->completed_order_id, [
                'cost' => $new_cost,
                'distance' => $distance,
                'changed' => true
            ]),
            $this->completedChangeContract->create([
                'completed_id' => $order->completed->completed_order_id,
                'changer_id' => get_user_id(),
                'old_distance' => $order->completed->distance,
                'new_distance' => $distance,
                'old_price' => $order->completed->cost,
                'new_price' => $new_cost,
            ]),
        ]);
    }

    /**
     * @param $order
     * @param $new_cost
     * @param $duration
     * @throws Exception
     */
    private function rewriteDuration($order, $new_cost, $duration): void
    {
        $this->completedOrderContract->beginTransaction(fn() => [
            $this->completedOrderContract->forgetCache(),

            $this->completedOrderContract->update($order->completed->completed_order_id, [
                'cost' => $new_cost,
                'duration' => $duration,
                'changed' => true
            ]),
            $this->completedChangeContract->create([
                'completed_id' => $order->completed->completed_order_id,
                'changer_id' => get_user_id(),
                'old_duration' => $order->completed->duration,
                'new_duration' => $duration,
                'old_price' => $order->completed->cost,
                'new_price' => $new_cost,
            ]),
        ]);
    }

    /**
     * @param $worker
     * @param $role_permissions
     * @return bool
     *
     * @info Called in transaction
     */
    #[NoReturn] protected function updateWorkerRolePermissions($worker, $role_permissions): bool
    {
        $worker_roles = [];
        $worker_permissions = [];

        $worker_id = $worker->{$worker->getKeyName()};

        foreach ((array)$role_permissions as $role => $permissions) {
            $worker_roles[$role] = ['system_worker_id' => $worker_id];

            foreach ($permissions as $permission) {
                $worker_permissions[$permission] = ['system_worker_id' => $worker_id];
            }
        }

        if (!$this->workerContract->store($worker_id, ['roles' => $worker_roles], true)) {
            return false;
        }

        if (!$this->workerContract->store($worker_id, ['permissions' => $worker_permissions], true)) {
            return false;
        }

        return true;
    }

    /**
     * @param $worker
     * @param $role_permissions
     * @return bool
     */
    protected function createRolePermissions($worker, $role_permissions): bool
    {
        try {
            foreach ((array)$role_permissions as $role => $permissions) {
                $worker_role = $this->workerRoleContract->create(['role_id' => $role, 'system_worker_id' => $worker->{$worker->getKeyName()}]);

                if (!$worker_role) {
                    continue;
                }

                $worker_permission = [];
                foreach ($permissions as $permission) {
                    if ($permission) {
                        $worker_permission[] = ['system_worker_id' => $worker->system_worker_id, 'permission_id' => $permission];
                    }
                }
                $this->workerPermissionContract->insert($worker_permission);
            }

            return true;
        } catch (Exception $exception) {
            return false;
        }
    }

    /**
     * @param $roles
     * @return bool
     */
    protected function isOperator($roles): bool
    {
        $result = false;

        $operator_roles = Role::whereIn('name', [Role::OPERATOR_API, Role::OPERATOR_WEB])
            ->get()
            ->pluck('role_id')
            ->toArray();

        foreach ($operator_roles as $operator_role) {
            if (\in_array($operator_role, $roles, true)) {
                $result = true;
                break;
            }
        }

        return $result;
    }

    /**
     * @param $roles
     * @return bool
     */
    protected function isDispatcher($roles): bool
    {
        $result = false;

        $dispatcher_roles = Role::whereIn('name', [Role::DISPATCHER_API, Role::DISPATCHER_WEB])
            ->get()
            ->pluck('role_id')
            ->toArray();

        foreach ($dispatcher_roles as $dispatcher_role) {
            if (\in_array($dispatcher_role, $roles, true)) {
                $result = true;
                break;
            }
        }

        return $result;
    }

    /**
     * @param $order_id
     * @param $driver_id
     * @return mixed
     */
    protected function preparePreorderFirstEtap($order_id, $driver_id)
    {
        $method = Str::after(__METHOD__, '::');
        return app($this->pattern)->$method($order_id, $driver_id);
    }

    /**
     * @param $order_id
     * @param $driver_id
     * @param $time
     * @return mixed
     */
    protected function preparePreorderSecondEtap($order_id, $driver_id, $time)
    {
        $method = Str::after(__METHOD__, '::');
        return app($this->pattern)->$method($order_id, $driver_id, $time);
    }
}
