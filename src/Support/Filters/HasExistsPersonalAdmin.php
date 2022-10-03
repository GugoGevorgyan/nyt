<?php
declare(strict_types=1);


namespace Src\Support\Filters;

use Illuminate\Support\Arr;
use Src\Services\Auth\AuthServiceContract;

/**
 * Class HasExistsSystemWorker
 * @package Src\Filters
 */
class HasExistsPersonalAdmin extends BaseFilter
{
    /**
     * @return mixed
     */
    public function filter()
    {
        $authContract = app(AuthServiceContract::class);

        $this->app->extend(
            'has_exists_personal_admin',
            static function ($attribute, $login, $parameter) use ($authContract) {
                $has_exists = $authContract->hasExistsPersonalAdmin($login, $parameter[1]);
                if (!empty($parameter) && 'be' === Arr::first($parameter) && !$has_exists) {
                    return false;
                }

                if (!empty($parameter) && 'not_be' === Arr::first($parameter) && $has_exists) {
                    return false;
                }

                return true;
            }
        );
    }
}
