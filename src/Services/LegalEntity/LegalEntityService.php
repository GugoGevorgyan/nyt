<?php

declare(strict_types=1);


namespace Src\Services\LegalEntity;

use Exception;
use Illuminate\Support\Collection;
use JsonException;
use ServiceEntity\BaseService;
use Src\Repositories\Franchisee\FranchiseContract;
use Src\Repositories\FranchiseEntity\FranchiseEntityContract;
use Src\Repositories\LegalEntity\LegalEntityContract;
use Src\Repositories\LegalEntityBank\LegalEntityBankContract;
use Src\Repositories\LegalEntityType\LegalEntityTypeContract;

/**
 * Class LegalEntityService
 * @package Src\Services\LegalEntity
 */
class LegalEntityService extends BaseService implements LegalEntityServiceContract
{
    /**
     * LegalEntityService constructor.
     * @param  LegalEntityContract  $legalEntityContract
     * @param  FranchiseEntityContract  $franchiseEntityContract
     * @param  LegalEntityBankContract  $entityBankContract
     * @param  FranchiseContract  $franchiseContract
     * @param  LegalEntityTypeContract  $entityTypeContract
     */
    public function __construct(
        protected LegalEntityContract $legalEntityContract,
        protected FranchiseEntityContract $franchiseEntityContract,
        protected LegalEntityBankContract $entityBankContract,
        protected FranchiseContract $franchiseContract,
        protected LegalEntityTypeContract $entityTypeContract
    ) {
    }

    /**
     * @inheritDoc
     */
    public function franchiseEntitiesPaginate($request)
    {
        $per_page = isset($request['per-page']) && $request['per-page'] && is_numeric($request['per-page']) ? $request['per-page'] : 25;
        $page = isset($request['page']) && $request['page'] && is_numeric($request['page']) ? $request['page'] : 1;
        $type = isset($request['type']) && $request['type'] && is_numeric($request['type']) ? $request['type'] : null;
        $search = (isset($request['search']) && $request['search'] && 'null' !== $request['search']) ? $request['search'] : '';

        $franchise = $this->franchiseContract->find(FRANCHISE_ID);

        $query = $this->legalEntityContract
            ->whereHas('franchises', fn($q) => $q->where('franchisee.franchise_id', '=', FRANCHISE_ID))
            ->when($type, fn($query) => $query->where('type_id', '=', $type))
            ->when($search, fn($query) => $query->where('name', 'LIKE', '%'.$search.'%')->orWhere('email', 'LIKE', '%'.$search.'%'))
            ->with(['type', 'country', 'region', 'city'])
            ->orderBy('legal_entity_id', 'desc')
            ->paginate($per_page, ['*'], 'page', $page);

        $query->map(fn($entity) => $entity['main'] = $entity->legal_entity_id === $franchise->franchise_id);

        return $query;
    }

    /**
     * @param $data
     * @param $entity_id
     * @return mixed
     */
    public function franchiseUpdateEntity($data, $entity_id)
    {
        $entity = $this->legalEntityContract
            ->whereHas('franchises', fn($q) => $q->where('franchisee.franchise_id', '=', FRANCHISE_ID))
            ->find($entity_id);

        return $entity ? $entity->update($data) : false;
    }

    /**
     * @param $entity_id
     * @return false|mixed
     */
    public function franchiseDestroyEntity($entity_id)
    {
        $entity = $this->legalEntityContract
            ->whereHas('franchises', fn($q) => $q->where('franchisee.franchise_id', '=', FRANCHISE_ID))
            ->whereDoesntHave('franchise', fn($q) => $q->where('franchisee.franchise_id', '=', FRANCHISE_ID))
            ->find($entity_id);

        return $entity ? $entity->delete() : false;
    }

    /**
     * @param $data
     * @return bool|mixed
     * @throws Exception
     */
    public function franchiseCreateEntity($data): bool
    {
        return $this->legalEntityContract->beginTransaction(function () use ($data) {
            $this->legalEntityContract->forgetCache();
            $this->franchiseEntityContract->forgetCache();

            $entity = $this->legalEntityContract->create($data);

            if (!$entity) {
                return false;
            }

            $this->franchiseEntityContract->create(['franchise_id' => FRANCHISE_ID, 'legal_entity_id' => $entity->legal_entity_id]);

            if (!$this->createEntityBanks($data['new_banks'], $entity)) {
                return false;
            }

            return true;
        });
    }

    /**
     * @param $banks
     * @param $entity
     * @return bool
     */
    protected function createEntityBanks($banks, $entity): bool
    {
        try {
            foreach ($banks as $iValue) {
                $iValue['entity_id'] = $entity['legal_entity_id'];
                if ($iValue && !$this->entityBankContract->create($iValue)) {
                    return false;
                }
            }
            return true;
        } catch (Exception $exception) {
            return false;
        }
    }

    /**
     * @return array|Collection|mixed
     */
    public function getAllEntities()
    {
        return $this->legalEntityContract->findAll() ?? collect();
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function adminSuperCreateEntity(array $data): ?object
    {
        return $this->legalEntityContract->beginTransaction(function () use ($data) {
            $this->legalEntityContract->forgetCache();
            $entity = $this->legalEntityContract->create($data);

            if (!$entity) {
                return null;
            }

            $banks = $this->createEntityBanks($data['new_banks'], $entity);

            if (!$banks) {
                return null;
            }

            return $entity;
        });
    }

    /**
     * @param $entity_id
     * @return mixed
     */
    public function franchiseGetEntity($entity_id)
    {
        return $this->legalEntityContract
            ->whereHas('franchises', function ($q) {
                return $q->where('franchisee.franchise_id', '=', FRANCHISE_ID);
            })
            ->with('banks')
            ->find($entity_id);
    }

    /**
     * @param $data
     * @return false|mixed
     * @throws JsonException
     */
    public function entityBankCreate($data)
    {
        $bank = $this->entityBankContract->create($data);

        return $bank ? decode($bank) : false;
    }

    /**
     * @param $data
     * @param $bank_id
     * @return false|mixed
     * @throws JsonException
     */
    public function entityBankUpdate($data, $bank_id)
    {
        $bank = $this->entityBankContract->find($bank_id);

        if (!$bank) {
            return false;
        }

        if (!$this->entityBankContract->update($bank_id, $data)) {
            return false;
        }

        return decode($bank);
    }

    /**
     * @param $bank_id
     * @return bool
     */
    public function entityBankDestroy($bank_id): bool
    {
        $bank = $this->entityBankContract->find($bank_id);

        if (!$bank) {
            return false;
        }

        return $bank->delete();
    }

    /**
     * @return Collection
     */
    public function getAllEntityTypes(): Collection
    {
        return $this->entityTypeContract->findAll();
    }
}
