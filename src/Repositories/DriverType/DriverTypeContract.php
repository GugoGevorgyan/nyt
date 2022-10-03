<?php

declare(strict_types=1);


namespace Src\Repositories\DriverType;


use Illuminate\Support\Collection;
use Repository\Contracts\BaseRepositoryContract;

/**
 * Interface DriverTypeContract
 * @package Src\Repositories\DriverType
 */
interface DriverTypeContract extends BaseRepositoryContract
{
    /**
     * @return Collection
     */
    public function getDriverTypes(): Collection;
}
