<?php

declare(strict_types=1);

namespace App\Configures\Redis\Connectors;

use Illuminate\Redis\Connectors\PhpRedisConnector;
use Illuminate\Support\Facades\Redis as RedisFacade;
use LogicException;
use Redis;

use function constant;
use function defined;
use function extension_loaded;

/**
 *
 */
class RedisConnector extends PhpRedisConnector
{

    /**
     * Create the Redis client instance.
     *
     * @param  array  $config
     * @return Redis
     *
     * @throws LogicException
     */
    protected function createClient(array $config): Redis
    {
        return tap(new Redis(), function ($client) use ($config) {
            if ($client instanceof RedisFacade) {
                throw new LogicException(
                    extension_loaded('redis')
                        ? 'Please remove or rename the Redis facade alias in your "app" configuration file in order to avoid collision with the PHP Redis extension.'
                        : 'Please make sure the PHP Redis extension is installed and enabled.'
                );
            }

            $this->establishConnection($client, $config);

            if (!empty($config['password'])) {
                $client->auth($config['password']);
            }

            if (isset($config['database'])) {
                $client->select((int)$config['database']);
            }

            if (!empty($config['prefix'])) {
                $client->setOption(Redis::OPT_PREFIX, $config['prefix']);
            }

            if (!empty($config['read_timeout'])) {
                $client->setOption(Redis::OPT_READ_TIMEOUT, $config['read_timeout']);
            }

            if (!empty($config['scan'])) {
                $client->setOption(Redis::OPT_SCAN, $config['scan']);
            }

            if (!empty($config['name'])) {
                $client->client('SETNAME', $config['name']);
            }

            if (!empty($config['serializer'])) {
                $serializer = 'Redis::SERIALIZER_'.strtoupper($config['serializer']);
                if (defined($serializer)) {
                    $serializer_const = constant($serializer);
                    $client->setOption(Redis::OPT_SERIALIZER, $serializer_const);
                }
            }
        });
    }
}
