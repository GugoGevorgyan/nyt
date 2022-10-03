<?php

declare(strict_types=1);

namespace Src\Support\Filters;

/**
 * Class NonExists
 */
class NonExists extends BaseFilter
{

    /**
     * @return mixed
     */
    public function filter()
    {
        $this->app->extend(
            'not_exists',
            static function ($attribute, $value, $parameter) {
                return !\DB::table($parameter[0])->where($parameter[1], '=', $value)->exists();
            }
        );
    }
}
