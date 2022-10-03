<?php

declare(strict_types=1);


namespace Src\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

/**
 * Class TariffScope
 *
 * @package Src\Scopes
 */
class TariffScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  Builder $builder
     * @param  Model   $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $builder
            ->where('status', '=', 1)
            ->whereDate('date_from', '<=', now())
            ->whereDate('date_to', '>=', now());
    }
}
