<?php

declare(strict_types=1);


namespace Src\Repositories\DriverGraphic;


use Illuminate\Support\Collection;
use Repository\Contracts\BaseRepositoryContract;

/**
 * Interface DriverGraphicContract
 * @package Src\Repositories\DriverGraphic
 */
interface DriverGraphicContract extends BaseRepositoryContract
{
    /**
     * @return Collection|mixed
     */
    public function getGraphics();
}
