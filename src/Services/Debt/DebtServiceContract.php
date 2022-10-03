<?php

namespace Src\Services\Debt;

use ServiceEntity\Contract\BaseContract;

/**
 *
 */
interface DebtServiceContract extends BaseContract
{
    /**
     * @param $payload
     * @return mixed
     */
    public function getPenalties($payload);


    /**
     * @param $id
     * @param $value
     * @return mixed
     */
    public function toPay($id, $value): mixed;


}
