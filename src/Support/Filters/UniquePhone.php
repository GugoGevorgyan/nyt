<?php
declare(strict_types=1);


namespace Src\Support\Filters;


use DB;

/**
 * Class ExistsPhone
 * @package Src\Filters
 */
class UniquePhone extends BaseFilter
{
    /**
     * @return mixed
     */
    public function filter()
    {
        $this->app->extend('unique_phone', static function ($attribute, $phone, $key) {

            [$table, $field] = $key;
            $phone = preg_replace('/[\D]/', '', $phone);

            return !DB::table($table)->where($field, '=', $phone)->exists();
        });
    }
}
