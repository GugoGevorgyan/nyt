<?php

declare(strict_types=1);


namespace Src\Services\Franchise;

use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use ServiceEntity\BaseService;
use Src\Repositories\FranchiseCity\FranchiseCityContract;
use Src\Repositories\Franchisee\FranchiseContract;
use Src\Repositories\FranchiseEntity\FranchiseEntityContract;
use Src\Repositories\FranchiseeOption\FranchiseOptionContract;
use Src\Repositories\FranchiseModule\FranchiseModuleContract;
use Src\Repositories\FranchisePhone\FranchisePhoneContract;
use Src\Repositories\FranchiseRegion\FranchiseRegionContract;
use Src\Repositories\FranchiseRole\FranchiseRoleContract;
use Src\Repositories\FranchiseSubPhone\FranchiseSubPhoneContract;
use Src\Repositories\LegalEntity\LegalEntityContract;
use Src\Repositories\Module\ModuleContract;
use Src\Repositories\Permission\PermissionContract;
use Src\Repositories\Role\RoleContract;
use Src\Repositories\SystemWorker\SystemWorkerContract;
use Src\Repositories\WorkerPermission\WorkerPermissionContract;
use Src\Repositories\WorkerRole\WorkerRoleContract;

use function count;
use function in_array;

/**
 * Class FranchiseService
 * @package Src\Services\Franchise
 */
class FranchiseService extends BaseService implements FranchiseServiceContract
{
    /**
     * FranchiseService constructor.
     * @param  FranchiseContract  $franchiseContract
     * @param  ModuleContract  $moduleContract
     * @param  SystemWorkerContract  $systemWorkerContract
     * @param  RoleContract  $roleContract
     * @param  LegalEntityContract  $entityContract
     * @param  WorkerRoleContract  $workerRoleContract
     * @param  PermissionContract  $permissionContract
     * @param  FranchiseModuleContract  $franchiseModuleContract
     * @param  FranchiseRegionContract  $franchiseRegionContract
     * @param  FranchiseCityContract  $franchiseCityContract
     * @param  FranchisePhoneContract  $franchisePhoneContract
     * @param  FranchiseSubPhoneContract  $franchiseSubPhoneContract
     * @param  FranchiseEntityContract  $franchiseEntityContract
     * @param  FranchiseRoleContract  $franchiseRoleContract
     * @param  FranchiseOptionContract  $franchiseOptionContract
     * @param  WorkerPermissionContract  $workerPermissionContract
     */
    public function __construct(
        protected FranchiseContract $franchiseContract,
        protected ModuleContract $moduleContract,
        protected SystemWorkerContract $systemWorkerContract,
        protected RoleContract $roleContract,
        protected LegalEntityContract $entityContract,
        protected WorkerRoleContract $workerRoleContract,
        protected PermissionContract $permissionContract,
        protected FranchiseModuleContract $franchiseModuleContract,
        protected FranchiseRegionContract $franchiseRegionContract,
        protected FranchiseCityContract $franchiseCityContract,
        protected FranchisePhoneContract $franchisePhoneContract,
        protected FranchiseSubPhoneContract $franchiseSubPhoneContract,
        protected FranchiseEntityContract $franchiseEntityContract,
        protected FranchiseRoleContract $franchiseRoleContract,
        protected FranchiseOptionContract $franchiseOptionContract,
        protected WorkerPermissionContract $workerPermissionContract
    ) {
    }

    /*franchise crud*/

    /**
     * @param $request
     * @return bool
     * @throws Exception
     */
    public function storeFranchise($request): bool
    {
        return $this->franchiseContract->beginTransaction(function () use ($request) {
            $this->franchiseContract->forgetCache();

            //  Create franchise
            $data = $this->prepareFranchiseData($request);
            $franchise = $this->franchiseContract->create($data);

            if (!$franchise) {
                return false;
            }

            //  Sync Franchise entities
            if (!$this->syncFranchiseEntities($franchise)) {
                return false;
            }

            //  Create franchise phones
            if (!$this->createFranchisePhones($request, $franchise)) {
                return false;
            }

            //  Create franchise regions
            if (!$this->createFranchiseRegionCities($request->regions_cities, $franchise)) {
                return false;
            }

            //  Create franchise modules
            if (!$this->createFranchiseModuleRoles($request->module_roles, $franchise)) {
                return false;
            }

            //  Create franchise options
            if (!$this->createFranchiseOptions($franchise, $request->option)) {
                return false;
            }

            //  Create franchise admin
            $admin = $this->createFranchiseAdminsStore($request, $franchise);

            if (!$admin) {
                return false;
            }

            return true;
        });
    }

    /**
     * @param $request
     * @return array
     */
    protected function prepareFranchiseData($request): array
    {
        $data = [
            'name' => $request['name'],
            'email' => $request['email'],
            'phone' => $request['phone'],
            'text' => $request['text'],
            'address' => $request['address'],
            'zip_code' => $request['zip_code'],
            'country_id' => $request['country_id'],
            'entity_id' => $request['entity_id']
        ];

        if ($request->hasFile('file')) {
            $data['logo'] = '/storage/franchise/logo/'.$this->fileUpload($request['file'], 'storage/franchise/logo');
        }

        return $data;
    }

    /**
     * @param $franchise
     * @return bool
     */
    protected function syncFranchiseEntities($franchise): bool
    {
        try {
            $this->franchiseEntityContract->updateOrCreate(
                ['legal_entity_id', '=', $franchise->entity_id, 'franchise_id', '=', $franchise->franchise_id],
                ['legal_entity_id' => $franchise->entity_id, 'franchise_id' => $franchise->franchise_id]
            );
            return true;
        } catch (Exception $exception) {
            return false;
        }
    }

    /**
     * @param $request
     * @param $franchise
     * @return bool
     */
    protected function createFranchisePhones($request, $franchise): bool
    {
        try {
            if ($request->call_center_phones && count($request->call_center_phones)) {
                foreach ($request->call_center_phones as $item) {
                    if ($item) {
                        $this->createPhone($item, $franchise);
                    }
                }
            }
            return true;
        } catch (Exception $exception) {
            return false;
        }
    }

    /**
     * @param $phoneValues
     * @param $franchise
     */
    protected function createPhone($phoneValues, $franchise): void
    {
        $franchise_phone = $this->franchisePhoneContract->create(['franchise_id' => $franchise->franchise_id, 'number' => $phoneValues['number']]);

        foreach ($phoneValues['sub_phones'] as $subPhone) {
            $this->createSubPhone($franchise_phone, $subPhone);
        }
    }

    /**
     * @param $phone
     * @param $subPhone
     */
    protected function createSubPhone($phone, $subPhone): void
    {
        $this->franchiseSubPhoneContract->create(
            [
                'franchise_phone_id' => $phone->franchise_phone_id,
                'number' => $subPhone['number'],
                'password' => $subPhone['password']
            ]
        );
    }

    /**
     * @param $regions_cities
     * @param $franchise
     * @return bool
     */
    protected function createFranchiseRegionCities($regions_cities, $franchise): bool
    {
        try {
            foreach ($regions_cities as $region_id => $city_ids) {
                $franchise_region = $this->franchiseRegionContract
                    ->updateOrCreate(
                        ['region_id', '=', $region_id, 'franchise_id', '=', $franchise->franchise_id],
                        ['region_id' => $region_id, 'franchise_id' => $franchise->franchise_id]
                    );

                foreach ($city_ids as $city_id) {
                    if ($city_id) {
                        $this->franchiseCityContract->updateOrCreate(
                            ['city_id', '=', $city_id, 'franchise_id', '=', $franchise->franchise_id],
                            [
                                'franchise_region_id' => $franchise_region->franchise_region_id,
                                'city_id' => $city_id,
                                'franchise_id' => $franchise->franchise_id
                            ]
                        );
                    }
                }
            }
            return true;
        } catch (Exception $exception) {
            return false;
        }
    }

    /**
     * @param $module_roles
     * @param $franchise
     * @return bool
     * @throws Exception
     */
    protected function createFranchiseModuleRoles($module_roles, $franchise): bool
    {
        try {
            foreach ((array)$module_roles as $module => $roles) {
                $franchise_module = $this->franchiseModuleContract->create(['franchise_id' => $franchise->franchise_id, 'module_id' => $module]);

                foreach ($roles as $role) {
                    if ($role) {
                        $this->franchiseRoleContract->updateOrCreate(
                            ['role_id', '=', $role, 'franchise_id', '=', $franchise->franchise_id],
                            [
                                'franchise_module_id' => $franchise_module->franchise_module_id,
                                'franchise_id' => $franchise->franchise_id,
                                'role_id' => $role
                            ]
                        );
                    }
                }
            }

            return true;
        } catch (Exception $exception) {
            return false;
        }
    }

    /**
     * @param $franchise
     * @param  array|null  $options
     * @return bool
     */
    protected function createFranchiseOptions($franchise, ?array $options = null): bool
    {
        $options = (array)$options;

        $assessment = [];
        $rating = [];
        $waybill_max_days = [];

        foreach ($options as $key => $option) {
            $assessment[$key] = $option['default_assessment'];
            $rating[$key] = $option['default_rating'];
            $waybill_max_days[$key] = $option['waybill_max_days'];
        }

        try {
            $this->franchiseOptionContract->create([
                'franchise_id' => $franchise->franchise_id,
                'default_assessment' => $assessment,
                'default_rating' => $rating,
                'waybill_max_days' => $waybill_max_days,
                'order_cancel_before' => 3
            ]);

            return true;
        } catch (Exception $exception) {
            return false;
        }
    }

    /**
     * @param $request
     * @param $franchise
     * @return bool
     */
    public function createFranchiseAdminsStore($request, $franchise): bool
    {
        try {
            foreach ($request['new_admins'] as $value) {
                $value['franchise_id'] = $franchise->franchise_id;

                if (!$this->adminStore($value, $request['module_roles'])) {
                    return false;
                }
            }
            return true;
        } catch (Exception $exception) {
            return false;
        }
    }

    /**
     * @param $values
     * @param $module_roles
     * @return object|null
     */
    protected function adminStore($values, $module_roles): ?object
    {
        $adminData = [
            'is_admin' => true,
            'franchise_id' => $values['franchise_id'],
            'name' => $values['name'],
            'surname' => $values['surname'],
            'patronymic' => $values['patronymic'],
            'nickname' => $values['nickname'],
            'email' => $values['email'],
            'phone' => $values['phone'],
            'password' => $values['password'],
        ];

        try {
            $admin = $this->systemWorkerContract->create($adminData);

            foreach ((array)$module_roles as $roles) {
                foreach ($roles as $role) {
                    $role = $this->workerRoleContract->create(['system_worker_id' => $admin->system_worker_id, 'role_id' => $role]);
                    $permissions = $this->permissionContract->where('role_id', '=', $role)->findAll();

                    foreach ($permissions as $permission) {
                        $this->workerPermissionContract->create([
                            'system_worker_id' => $admin->system_worker_id,
                            'permission_id' => $permission->permission_id
                        ]);
                    }
                }
            }

            return $admin;
        } catch (Exception $exception) {
            return null;
        }
    }

    /**
     * @inheritDoc
     * @noinspection MultipleReturnStatementsInspection
     * @throws Exception
     */
    public function updateFranchise($request, $franchise_id): bool
    {
        $franchise = $this->franchiseContract->find($franchise_id);
        $data = $this->prepareFranchiseData($request);

        return $this->franchiseContract->beginTransaction(function () use ($franchise, $data, $request, $franchise_id) {
            $this->franchiseContract->forgetCache();

            if (!$this->franchiseContract->update($franchise_id, $data)) {
                return false;
            }

            if (!$this->syncFranchiseEntities($franchise)) {
                return false;
            }

            if (!$this->updateFranchisePhones($request, $franchise)) {
                return false;
            }

            if (!$this->syncFranchiseRegion($request->regions_cities, $franchise)) {
                return false;
            }

            //  Update modules and roles
            if (!$this->syncFranchiseModules($request->module_roles, $franchise)) {
                return false;
            }

            if (!$this->updateFranchiseOptions($franchise, $request->option)) {
                return false;
            }

            return true;
        });
    }

    /**
     * @param $request
     * @param $franchise
     * @return bool
     */
    protected function updateFranchisePhones($request, $franchise): bool
    {
        try {
            if ($request->call_center_phones && count($request->call_center_phones)) {
                foreach ($request->call_center_phones as $phoneValues) {
                    if ($phoneValues) {
                        $phoneValues['franchise_phone_id']
                            ? $this->updatePhone($phoneValues, $franchise)
                            : $this->createPhone($phoneValues, $franchise);
                    }
                }
            }

            return true;
        } catch (Exception $exception) {
            return false;
        }
    }

    /**
     * @param $phoneValues
     * @param $franchise
     */
    protected function updatePhone($phoneValues, $franchise): void
    {
        $franchise_phone = $franchise->phones()->where('franchise_phone_id', '=', $phoneValues['franchise_phone_id'])->first();
        $this->franchisePhoneContract->where('franchise_phone_id', '=', $phoneValues['franchise_phone_id'])->updateSet($phoneValues);

        foreach ($phoneValues['sub_phones'] as $subPhone) {
            $subPhone['franchise_sub_phone_id']
                ? $this->updateSubPhone($subPhone)
                : $this->createSubPhone($franchise_phone, $subPhone);
        }
    }

    /**
     * @param $subPhone
     */
    protected function updateSubPhone($subPhone): void
    {
        $this->franchiseSubPhoneContract->where('franchise_phone_id', '=', $subPhone['franchise_phone_id'])->updateSet($subPhone);
    }

    /**
     * @param $regions_cities
     * @param $franchise
     * @return bool
     */
    protected function syncFranchiseRegion($regions_cities, $franchise): bool
    {
        $this->franchiseRegionContract->where('franchise_id', '=', $franchise->franchise_id)->deletes();

        return $this->createFranchiseRegionCities($regions_cities, $franchise);
    }

    /**
     * @param $module_roles
     * @param $franchise
     * @return bool
     * @throws Exception
     */
    protected function syncFranchiseModules($module_roles, $franchise): bool
    {
        $this->franchiseModuleContract->where('franchise_id', '=', $franchise->franchise_id)->deletes();

        return $this->createFranchiseModuleRoles($module_roles, $franchise);
    }

    /**
     * @param $franchise
     * @param  array|null  $options
     * @return bool
     */
    protected function updateFranchiseOptions($franchise, ?array $options = null): bool
    {
        $options = (array)$options;

        $assessment = [];
        $rating = [];
        $waybill_max_days = [];

        foreach ($options as $key => $option) {
            $assessment[$key] = $option['default_assessment'];
            $rating[$key] = $option['default_rating'];
            $waybill_max_days[$key] = $option['waybill_max_days'];
        }

        try {
            $this->franchiseOptionContract
                ->where('franchise_id', '=', $franchise->franchise_id)
                ->updateSet([
                    'default_assessment' => $assessment,
                    'default_rating' => $rating,
                    'waybill_max_days' => $waybill_max_days
                ]);

            return true;
        } catch (Exception $exception) {
            return false;
        }
    }

    /**
     * @param $id
     * @return mixed|void
     */
    public function deleteFranchise($id)
    {
        return $this->franchiseContract->delete($id);
    }

    /**
     * @param $franchise_phone_id
     * @return bool
     */
    public function deleteFranchisePhone($franchise_phone_id): bool
    {
        try {
            $phone = $this->franchisePhoneContract->find($franchise_phone_id);
            if (!$phone) {
                return false;
            }
            $phone->delete();
            return true;
        } catch (Exception $exception) {
            return false;
        }
    }


    /*franchise region*/

    /**
     * @param $franchise_sub_phone_id
     * @return bool
     */
    public function deleteFranchiseSubPhone($franchise_sub_phone_id): bool
    {
        try {
            $subPhone = $this->franchiseSubPhoneContract->find($franchise_sub_phone_id);
            if (!$subPhone) {
                return false;
            }
            $subPhone->delete();
            return true;
        } catch (Exception $exception) {
            return false;
        }
    }

    /**
     * @param $entity
     * @return mixed
     */
    public function createFranchiseEntityStore($entity)
    {
        return $this->entityContract->create($entity);
    }

    /**
     * @param $request
     * @return false|mixed
     * @throws Exception
     */
    public function createFranchiseAdmin($request)
    {
        $this->systemWorkerContract->beginTransaction();
        $this->systemWorkerContract->forgetCache();
        $module_roles = $this->franchiseModuleRoleIds($request['franchise_id']);
        $admin = $this->adminStore($request, $module_roles);

        if (!$admin) {
            $this->systemWorkerContract->rollBack();
            return false;
        }

        $this->systemWorkerContract->commit();
        return $admin;
    }

    /**
     * @param $franchise_id
     * @return array
     */
    protected function franchiseModuleRoleIds($franchise_id): array
    {
        $module_roles = $this->franchiseModuleContract->with('franchise_roles')->where('franchise_id', '=', $franchise_id)->findAll();
        $result = [];

        foreach ($module_roles as $module) {
            $result[$module['module_id']] = $module['franchise_roles']->pluck('role_id')->toArray();
        }

        return $result;
    }


    /*franchise entity*/

    /**
     * @param $request
     * @param $system_worker_id
     * @return false|mixed
     */
    public function updateFranchiseAdmin($request, $system_worker_id)
    {
        $admin = $this->systemWorkerContract->find($system_worker_id);
        $data = [
            'name' => $request->name,
            'surname' => $request->surname,
            'patronymic' => $request->patronymic,
            'nickname' => $request->nickname,
            'email' => $request->email,
            'phone' => $request->phone
        ];

        if ($request->change_password) {
            $data['password'] = $request->password;
        }

        if (!$admin->update($data)) {
            return false;
        }

        return $admin;
    }


    /*franchise admin crud*/

    /**
     * @param $system_worker_id
     * @return mixed
     */
    public function deleteFranchiseAdmin($system_worker_id)
    {
        return $this->systemWorkerContract->find($system_worker_id);
    }

    /**
     * @param $franchise_id
     * @return Collection
     */
    public function getFranchiseModuleRoles($franchise_id): Collection
    {
        // @todo fix critical performance
        return $this->franchiseModuleContract
            ->where('franchise_id', '=', $franchise_id)
            ->with(['module', 'franchise_roles.role'])
            ->findAll();
    }

    /**
     * @inheritDoc
     */
    public function getFranchisePhoneCode(): ?string
    {
        $franchise = $this->franchiseContract
            ->with(['country' => fn($query) => $query->select(['country_id', 'phone_code', 'phone_mask'])])
            ->find(FRANCHISE_ID, ['franchise_id', 'country_id']);

        if ($franchise && $franchise->country) {
            return $franchise->country->phone_code;
        }

        return null;
    }

    /**
     * @param $franchise_id
     * @return array
     */
    public function getEditFranchise($franchise_id): array
    {
        $data = $this->franchiseContract
            ->with([
                'phones.subPhones',
                'module_role_ids',
                'region_city_ids',
                'option',
                'admins' => fn($q) => $q->orderBy('system_worker_id', 'desc'),
            ])
            ->where('franchise_id', '=', $franchise_id)
            ->findFirst()
            ->toArray();

        if ($data['option']) {
            $data["dispatching_minute"] = $data['option']["dispatching_minute"];
            $data["order_cancel_before"] = $data['option']["order_cancel_before"];
            $data['option'] = collect($data['option']['default_assessment'])->mapWithKeys(function ($assessment_item, $key) use ($data) {
                return [
                    $key => [
                        'default_assessment' => $assessment_item,
                        'default_rating' => $data['option']['default_rating'][$key],
                        'waybill_max_days' => $data['option']['waybill_max_days'][$key]
                    ]
                ];
            });
        } else {
            $data['option'] = [];
        }

        return $data;
    }

    /**
     * @param $request
     * @return LengthAwarePaginator
     */
    public function adminPaginate($request): LengthAwarePaginator
    {
        $per_page = isset($request['per_page']) && is_numeric($request['per_page']) ? $request['per_page'] : 25;
        $page = isset($request->page) && is_numeric($request->page) ? $request->page : 1;
        $search = isset($request->search) && null != $request->search ? $request->search : null;

        return $this->franchiseContract->with(['modules', 'admins', 'regions'])
            ->where(fn($q) => $q->where('name', 'LIKE', '%'.$search.'%'))
            ->orderBy('franchise_id', 'DESC')
            ->paginate($per_page, ['*'], 'page', (int)$page);
    }

    /**
     * @inheritDoc
     */
    public function getFranchiseRegion(): array
    {
        $franchise = $this->franchiseContract
            ->where('franchise_id', '=', FRANCHISE_ID)
            ->with([
                'regions' => fn($query) => $query->select(['regions.region_id', 'regions.name']),
                'cities' => fn($query) => $query->select(['cities.city_id', 'cities.region_id', 'cities.name'])
            ])
            ->findFirst();

        return $franchise ? [$franchise->regions, $franchise->cities] : [];
    }

    /**
     * @return array|Collection
     */
    public function getFranchiseEntitiesIe()
    {
        return $this->entityContract
            ->whereHas('type', fn($q) => $q->where('value', '=', 'individual_entrepreneur'))
            ->whereHas('franchises', fn($q) => $q->where('franchisee.franchise_id', '=', FRANCHISE_ID))
            ->findAll()
            ?: [];
    }

    /**
     * @return array|Collection
     */
    public function getFranchiseEntitiesNotIe()
    {
        return $this->entityContract
            ->whereHas('type', fn($q) => $q->where('value', '<>', 'individual_entrepreneur'))
            ->whereHas('franchises', fn($q) => $q->where('franchisee.franchise_id', '=', FRANCHISE_ID))
            ->findAll()
            ?: [];
    }

    /**
     * @return Collection
     */
    public function getFranchiseEntities(): Collection
    {
        return $this->entityContract
            ->whereHas('franchises', fn(Builder $q) => $q->where('franchisee.franchise_id', '=', FRANCHISE_ID))
            ->findAll(['legal_entity_id', 'type_id', 'country_id', 'city_id', 'region_id', 'name', 'zip_code', 'address', 'phone', 'email'])
            ?: collect();
    }

    /**
     * @return Collection
     */
    public function getFranchiseRoles(): Collection
    {
        return $this->roleContract
            ->whereHas('franchises', fn($q) => $q->where('franchisee.franchise_id', '=', FRANCHISE_ID))
            ->findAll();
    }

    /**
     * @param $franchise_id
     * @return Collection
     */
    public function getFranchiseSubPhones($franchise_id): Collection
    {
        return $this->franchiseSubPhoneContract
            ->whereHas('franchisePhone', fn($q) => $q->where('franchise_id', '=', $franchise_id))
            ->findAll();
    }

    /**
     * @param $franchise
     * @return bool
     */
    protected function franchisePermissionUpdated($franchise): bool
    {
        try {
            $this->updatedWorkerRoles($franchise);
            return true;
        } catch (Exception $exception) {
            return false;
        }
    }

    /**
     * @param $franchise
     * @return bool
     */
    protected function updatedWorkerRoles($franchise): bool
    {
        try {
            $workers = $franchise->system_workers()->get();
            $module_roles = $franchise->module_role_ids()->get()->toArray();

            $franchise_roles = [];
            foreach ($module_roles as $module) {
                if ($module['roles_id']) {
                    $franchise_roles = array_merge($franchise_roles, $module['roles_id']['ids']);
                }
            }


            foreach ($workers as $worker) {
                $deleteRoles = [];
                $worker_roles = $worker->worker_role_ids()->get();

                foreach ($worker_roles as $role) {
                    if (!in_array($role->role_id, $franchise_roles, true)) {
                        $deleteRoles[] = $role->role_id;
                    }
                }

                $worker->roles()->detach($deleteRoles);
            }
            return true;
        } catch (Exception $exception) {
            return false;
        }
    }

    /**
     * @param $franchise
     * @return bool
     */
    protected function franchiseLocationUpdated($franchise): bool
    {
        try {
            $this->updatedLocationPark($franchise);
            return true;
        } catch (Exception $exception) {
            return false;
        }
    }

    /**
     * @param $franchise
     */
    protected function updatedLocationPark($franchise): void
    {
        $parks = $franchise->parks()->with('region')->get();
        $regions = $franchise->region_city_ids()->get();

        foreach ($parks as $park) {
            $find = false;

            foreach ($regions as $region) {
                if (($region->region_id === $park->region->region_id) && $region->cities_id) {
                    foreach ($region->cities_id['ids'] as $city_id) {
                        if ($park->city_id === $city_id) {
                            $find = true;
                        }
                    }
                }
            }

            if (!$find) {
                $park->delete();
            }
        }
    }
}
