<?php

declare(strict_types=1);


namespace Src\Support\Filters;


use Illuminate\Support\Facades\Redis;

/**
 * Class ExistsRedis
 * @package Src\Filters
 */
class ExistsRedis extends BaseFilter
{

    /**
     * @return mixed
     */
    public function filter()
    {
        $this->app->extend(
            'exists_redis',
            static function ($attribute, $accept_code, $key) {
                Redis::command('select', [0]);
                $result = Redis::hmget("client_accept_codes:".$key[0], ['phone', 'accept_code']);

                if (!$result) {
                    return null;
                }

                if ($result[1] !== $accept_code) {
                    return null;
                }

                return true;
            }
        );
    }
}
