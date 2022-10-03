<?php
declare(strict_types=1);


namespace Src\Support\Filters;


use Src\Services\Auth\AuthServiceContract;
use Illuminate\Support\Arr;

/**
 * Class HasExistsSystemWorker
 * @package Src\Filters
 */
class HasExistsSystemWorker extends BaseFilter
{
    /**
     * @return mixed
     */
    public function filter()
    {
        $authContract = app(AuthServiceContract::class);

        $this->app->extend('has_exists_system_worker', function ($attribute, $login, $parameter) use ($authContract) {

            $has_exists = $authContract->hasExistsWorker($login, $parameter[1]);

            if (!empty($parameter) && 'be' === Arr::first($parameter) && !$has_exists) {
                return false;
            }

            if (!empty($parameter) && 'not_be' === Arr::first($parameter) && $has_exists) {
                return false;
            }

            return true;
        });
    }
}
