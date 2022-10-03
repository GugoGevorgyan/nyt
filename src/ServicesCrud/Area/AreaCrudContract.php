<?php

declare(strict_types=1);


namespace Src\ServicesCrud\Area;

use Illuminate\Support\Collection;
use ServiceEntity\Contract\BaseContract;

/**
 * Interface AreaCrudContract
 * @package Src\ServicesCrud\Area
 */
interface AreaCrudContract extends BaseContract
{
    /**
     * @param  array  $data
     * @param $area_id
     * @return bool|object
     */
    public function update(array $data, $area_id): object|bool;

    /**
     * @param  array  $data
     * @return null|object
     */
    public function create(array $data): ?object;

    /**
     * @param $area_id
     * @return bool|object
     */
    public function delete($area_id): bool|object;
}
