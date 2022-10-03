<?php

declare(strict_types=1);


namespace Src\Core\Enums;

use Src\Core\Contracts\Enum;

/**
 * Class ConstRedis
 * @package Src\Core\Enums
 */
class ConstRedis extends Enum
{
    private const ACCEPT_CODE = 'accept_codes:';
    private const ORDER_PAUSE_TIME = 'order_pause_time:';
    private const ORDER_CREATE_DATA = 'order_create_data:';
    private const ORDER_CALC_DATA = 'order_calc_data:';
    private const ORDER_CALC_RESPONSE = 'order_calc_response:';
    private const ORDER_CALC_REQUEST = 'order_calc_request:';
    private const ORDER_PROCESS_ROAD = 'order_process_road:';
    private const DRIVER_PREORDER_QUESTION = 'driver_preorder_question:';

    /**
     * @param $type
     * @param $phone
     * @return string
     */
    public static function accept_code($type, $phone): string
    {
        return new self(self::ACCEPT_CODE)."$type.$phone";
    }

    /**
     * @param $order_id
     * @return string
     */
    public static function order_pause_time($order_id): string
    {
        return new self(self::ORDER_PAUSE_TIME).$order_id;
    }

    /**
     * @param  null  $order_id
     * @return ConstRedis|string
     */
    public static function order_create_data($order_id = null): ConstRedis|string
    {
        if ($order_id) {
            return new self(self::ORDER_CREATE_DATA).$order_id;
        }

        return new self(self::ORDER_CREATE_DATA);
    }

    /**
     * @param  null  $order_id
     * @return ConstRedis|string
     */
    public static function order_calc_data($order_id = null): ConstRedis|string
    {
        if ($order_id) {
            return new self(self::ORDER_CALC_DATA).$order_id;
        }

        return new self(self::ORDER_CALC_DATA);
    }

    /**
     * @param  null  $user_id
     * @return ConstRedis|string
     */
    public static function order_calc_request($user_id = null): ConstRedis|string
    {
        if ($user_id) {
            return new self(self::ORDER_CALC_REQUEST).$user_id;
        }

        return new self(self::ORDER_CALC_REQUEST);
    }

    /**
     * @param  null  $user_id
     * @return ConstRedis|string
     */
    public static function order_calc_response($user_id = null): ConstRedis|string
    {
        if ($user_id) {
            return new self(self::ORDER_CALC_RESPONSE).$user_id;
        }

        return new self(self::ORDER_CALC_RESPONSE);
    }

    /**
     * @param  null  $user_id
     * @return ConstRedis|string
     */
    public static function order_process_road($user_id = null): ConstRedis|string
    {
        if ($user_id) {
            return new self(self::ORDER_PROCESS_ROAD).$user_id;
        }

        return new self(self::ORDER_PROCESS_ROAD);
    }

    /**
     * @param  null  $driver_id
     * @return ConstRedis|string
     */
    public static function driver_preorder_question($driver_id = null): ConstRedis|string
    {
        if ($driver_id) {
            return new self(self::DRIVER_PREORDER_QUESTION).$driver_id;
        }

        return new self(self::DRIVER_PREORDER_QUESTION);
    }
}
