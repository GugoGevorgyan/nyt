<?php

declare(strict_types=1);


namespace Src\Services\LegalEntity;


use Illuminate\Support\Collection;
use ServiceEntity\Contract\BaseContract;

/**
 * Interface LegalEntityServiceContract
 * @package Src\Services\LegalEntity
 */
interface LegalEntityServiceContract extends BaseContract
{
    /**
     * @return mixed
     */
    public function getAllEntities();

    /**
     * @param $request
     * @return mixed
     */
    public function franchiseEntitiesPaginate($request);

    /**
     * @param $entity_id
     * @return mixed
     */
    public function franchiseGetEntity($entity_id);

    /**
     * @param $data
     * @return mixed
     */
    public function franchiseCreateEntity($data);

    /**
     * @param $data
     * @param $entity_id
     * @return mixed
     */
    public function franchiseUpdateEntity($data, $entity_id);

    /**
     * @param $entity_id
     * @return mixed
     */
    public function franchiseDestroyEntity($entity_id);

    /**
     * @param  array  $data
     * @return object|null
     */
    public function adminSuperCreateEntity(array $data): ?object;

    /**
     * @param $data
     * @return mixed
     */
    public function entityBankCreate($data);

    /**
     * @param $data
     * @param $bank_id
     * @return mixed
     */
    public function entityBankUpdate($data, $bank_id);

    /**
     * @param $bank_id
     * @return mixed
     */
    public function entityBankDestroy($bank_id);

    /**
     * @return mixed
     */
    public function getAllEntityTypes(): Collection;
}
