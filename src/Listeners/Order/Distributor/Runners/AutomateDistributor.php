<?php

declare(strict_types=1);

namespace Src\Listeners\Order\Distributor\Runners;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Src\Core\Contracts\Realizing;
use Src\Listeners\Order\Distributor\Contracts\DecorateInterface;
use Src\Models\Driver\Driver;

/**
 *
 */
class AutomateDistributor implements DecorateInterface
{
    /**
     * @var Realizing|null
     */
    protected Realizing|null $app = null;

    /**
     * @param  Realizing  $app
     * @return static
     */
    public static function decorate(Realizing $app): self
    {
        return (clone app(self::class))->setApp($app);
    }

    /**
     * @param $app
     * @return $this
     */
    public function setApp($app): static
    {
        $this->app = $app;

        return $this;
    }

    /**
     * @param ...$params
     */
    public function search(...$params): void
    {
        $favorite = $this->getFavoriteDrivers();

        if ($favorite && $favorite->favorite_drivers_count >= 1) {
            $this->favoriteDrivers($favorite->favoriteDrivers);
        } else {
            $this->app->twisting();
        }
    }

    /**
     * @param $fv_drivers
     * @return bool
     */
    protected function favoriteDrivers($fv_drivers): bool
    {
        $filtered_driver = $this->app->getDrivers($fv_drivers, (string)config('nyt.favorite_driver_search_distance'), 30);

        if ($filtered_driver) {
            $this->app->broadcastDriverReceives(
                $filtered_driver,
                $filtered_driver->road_distance,
                $filtered_driver->road_duration,
                $filtered_driver->road_point
            );

            if (!$this->app->setShippedOrders($filtered_driver)) {
                $this->app->twisting();
            }
        }

        $this->app->twisting();
        return true;
    }

    /**
     * @return object|null
     */
    public function getFavoriteDrivers(): ?object
    {
        return $this->app->clientContract
            ->where($this->app->clientContract->getKeyName(), '=', $this->app->payload->order->client_id)
            ->with([
                'favoriteDrivers' => fn(BelongsToMany $fv_driver_query) => $fv_driver_query
                    ->whereHas('status', fn(Builder $current_status_query) => $current_status_query->where('status', Driver::CURRENT_STATUS_IS_FREE))
                    ->where('online', 1)
                    ->where('is_ready', 1)
            ])
            ->withCount('favoriteDrivers')
            ->findFirst();
    }

    public function annul(): void
    {
        // TODO: Implement annul() method.
    }
}
