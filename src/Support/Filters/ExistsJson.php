<?php
declare(strict_types=1);


namespace Src\Support\Filters;


use DB;
use Illuminate\Support\Arr;

/**
 * Class ExistsJson
 * @package Src\Filters
 */
class ExistsJson extends BaseFilter
{
    /**
     * @return mixed
     */
    public function filter()
    {
        $this->app->extend('exists_json', static function ($attribute, $value, $parameters, $validator) {

            $db = explode('.', Arr::first($parameters), 2);

            return DB::table($db[0])->whereJsonContains($db[1], $value)->exists() ?: null;
        });
    }
}
