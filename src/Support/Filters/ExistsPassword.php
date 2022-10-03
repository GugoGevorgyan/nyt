<?php
declare(strict_types=1);


namespace Src\Support\Filters;


use DB;
use Hash;
use Illuminate\Support\Arr;

/**
 * Class ExistsPassword
 * @package Src\Filters
 */
class ExistsPassword extends BaseFilter
{
    public function filter()
    {
        $this->app->extend('exists_password', static function ($attribute, $value, $parameters, $validator) {
            $db = explode('.', Arr::first($parameters), 2);
            $password = DB::table($db[0])->where('system_worker_id', get_user_id())->first(['password']);

            return Hash::check($value, $password->password);
        });
    }
}
