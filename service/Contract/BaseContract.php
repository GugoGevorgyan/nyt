<?php

declare(strict_types=1);

namespace ServiceEntity\Contract;

use Eloquent;
use Illuminate\Redis\Connections\Connection;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Redis;
use Spatie\Async\Pool;

/**
 * Interface BaseServiceInterface
 * @return Eloquent;
 * @package ServiceEntityStory\Contract
 * @method getRoleNameById(string $class, $id)
 */
interface BaseContract
{
    /**
     * @param $contract
     * @param $attribute
     * @param $value
     * @param $password
     * @return mixed
     */
    public function hasPerson($contract, $attribute, $value, $password);

    /**
     * @return Pool
     */
    public function poolAsync(): Pool;

    /**
     * @return Pool
     */
    public function pool(): Pool;

    /**
     * @param  object  $relations
     * @return array|null
     */
    public function getRelationAttributes(object $relations): ?array;

    /**
     * @param  string  $connection
     * @return Redis|Connection
     */
    public function redis(string $connection = 'app'): Connection;

    /**
     * @param  Worksheet  $sheet
     * @param  array  $coordinates
     * @param $value
     */
    public function setSheetCellsValue(Worksheet $sheet, array $coordinates, $value): void;

    /**
     * @param  int  $order_id
     * @return bool
     */
    public function autoDispatch(int $order_id): bool;
}
