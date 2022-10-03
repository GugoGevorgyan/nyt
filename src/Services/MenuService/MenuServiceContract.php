<?php
declare(strict_types=1);


namespace Src\Services\MenuService;


use Illuminate\Support\Collection;
use ServiceEntity\Contract\BaseContract;

/**
 * Interface MenuServiceContract
 * @package Src\Services\MenuService
 */
interface MenuServiceContract extends BaseContract
{
    /**
     * @return Collection|null
     */
    public function getMenu(): ?Collection;
}
