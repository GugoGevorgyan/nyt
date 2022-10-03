<?php
declare(strict_types=1);


namespace Src\Support\Filters;

use Src\Repositories\Driver\DriverContract;
use Arr;
use Hash;
use Illuminate\Contracts\Foundation\Application;

/**
 * Class HasExistsDriver
 * @package Src\Filters
 */
class HasExistsDriver extends BaseFilter
{
    /**
     * @var DriverContract|Application|mixed
     */
    protected $driverContract;

    /**
     * CheckClientHasPhone constructor.
     * @param $app
     */
    public function __construct($app)
    {
        $this->app = $app;
        $this->driverContract = app(DriverContract::class);
    }

    /**
     * @return mixed
     */
    public function filter()
    {
        $this->app->extend('has_exists_driver', function ($attribute, $login, $parameter) {
            $has_exists = $this->driverContract->findBy(
                $this->driverContract->createModel()->username,
                $login,
                ['password']
            );

            if (!$has_exists) {
                return false;
            }

            if (!Hash::check($parameter['1'], $has_exists->password)) {
                return false;
            }

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
