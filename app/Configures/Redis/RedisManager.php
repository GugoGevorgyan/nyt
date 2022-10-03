<?php

declare(strict_types=1);

namespace App\Configures\Redis;

use App\Configures\Redis\Connectors\RedisConnector;
use Closure;
use Illuminate\Contracts\Redis\Connector;
use Illuminate\Redis\Connectors\PredisConnector;

/**
 *
 */
class RedisManager extends \Illuminate\Redis\RedisManager
{

    /**
     * Subscribe to a set of given channels for messages.
     *
     * @param  array|string  $channels
     * @param  Closure  $callback
     * @param  string  $method
     * @return void
     */
    public function createSubscription($channels, Closure $callback, $method = 'subscribe'): void
    {
        //
    }

    /**
     * Get the connector instance for the current driver.
     *
     * @return RedisConnector|Connector|PredisConnector
     */
    protected function connector(): RedisConnector|Connector|PredisConnector
    {
        $customCreator = $this->customCreators[$this->driver] ?? null;

        if ($customCreator) {
            return $customCreator();
        }

        switch ($this->driver) {
            case 'predis':
                return new PredisConnector();
            case 'phpredis':
                return new RedisConnector();
            default:
        }
    }
}
