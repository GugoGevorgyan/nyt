<?php

declare(strict_types=1);

namespace Src\Models\Franchise;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;
use Src\Core\Traits\HasModules;
use Src\Models\Corporate\AdminCorporate;
use Src\Models\Corporate\Company;
use Src\Models\Driver\Driver;
use Src\Models\LegalEntity\LegalEntity;
use Src\Models\Location\City;
use Src\Models\Location\Country;
use Src\Models\Location\Region;
use Src\Models\Order\Order;
use Src\Models\Park;
use Src\Models\Role\FranchiseRole;
use Src\Models\Role\Role;
use Src\Models\SystemUsers\SuperAdmin;
use Src\Models\SystemUsers\SystemWorker;
use Src\Models\Views\Image;

/**
 * Class Franchise
 *
 * @package Src\Models\Franchise
 * @property int $franchise_id
 * @property int|null $creator_admin_id
 * @property int|null $owner_admin_id
 * @property string|null $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Image $logo
 * @property-read Collection|Module[] $modules
 * @property-read SystemWorker $owner
 * @property-read Collection|Park[] $parks
 * @property-read SuperAdmin|null $superAdminCreator
 * @property-read Collection|SystemWorker[] $systemWorkers
 * @method static Builder|Franchise newModelQuery()
 * @method static Builder|Franchise newQuery()
 * @method static Builder|Franchise query()
 * @method static Builder|Franchise whereCreatedAt($value)
 * @method static Builder|Franchise whereCreatorAdminId($value)
 * @method static Builder|Franchise whereFranchiseId($value)
 * @method static Builder|Franchise whereName($value)
 * @method static Builder|Franchise whereOwnerAdminId($value)
 * @method static Builder|Franchise whereUpdatedAt($value)
 * @mixin Eloquent
 * @property string $owner_name
 * @property string $owner_email
 * @property-read Collection|Driver[] $drivers
 * @property-read Collection|Region[] $region
 * @property-read Collection|SystemWorker[] $system_workers
 * @method static Builder|Franchise whereOwnerEmail($value)
 * @method static Builder|Franchise whereOwnerName($value)
 * @property string|null $company_type
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $tax_code
 * @property string|null $contract_exp_date
 * @property string|null $contract_code
 * @property string|null $contract_scan
 * @property string|null $director
 * @property Carbon|null $deleted_at
 * @property-read Collection|SystemWorker[] $admins
 * @property-read int|null $admins_count
 * @property-read Collection|Company[] $companies
 * @property-read int|null $companies_count
 * @property-read Collection|AdminCorporate[] $corporateAdmins
 * @property-read int|null $corporate_admins_count
 * @property-read int|null $drivers_count
 * @property-read Collection|FranchiseModule[] $franchise_module
 * @property-read int|null $franchise_module_count
 * @property-read int|null $modules_count
 * @property-read Collection|Order[] $orders
 * @property-read int|null $orders_count
 * @property-read int|null $parks_count
 * @property-read Collection|Region[] $regions
 * @property-read int|null $regions_count
 * @property-read int|null $system_workers_count
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|Franchise onlyTrashed()
 * @method static bool|null restore()
 * @method static Builder|Franchise whereCompanyType($value)
 * @method static Builder|Franchise whereContractCode($value)
 * @method static Builder|Franchise whereContractExpDate($value)
 * @method static Builder|Franchise whereContractScan($value)
 * @method static Builder|Franchise whereDeletedAt($value)
 * @method static Builder|Franchise whereDirector($value)
 * @method static Builder|Franchise whereEmail($value)
 * @method static Builder|Franchise whereLogo($value)
 * @method static Builder|Franchise wherePhone($value)
 * @method static Builder|Franchise whereTaxCode($value)
 * @method static \Illuminate\Database\Query\Builder|Franchise withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Franchise withoutTrashed()
 * @property int $entity_id
 * @property int $country_id
 * @property int $region_id
 * @property int $city_id
 * @property string $address
 * @property string $zip_code
 * @property string $director_name
 * @property string $director_surname
 * @property string $director_patronymic
 * @property-read LegalEntity $entity
 * @property-read City $reg_city
 * @method static Builder|ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|Franchise whereAddress($value)
 * @method static Builder|Franchise whereCityId($value)
 * @method static Builder|Franchise whereCountryId($value)
 * @method static Builder|Franchise whereDirectorName($value)
 * @method static Builder|Franchise whereDirectorPatronymic($value)
 * @method static Builder|Franchise whereDirectorSurname($value)
 * @method static Builder|Franchise whereEntityId($value)
 * @method static Builder|Franchise whereRegionId($value)
 * @method static Builder|Franchise whereZipCode($value)
 * @property-read Collection|FranchisePhone[] $phones
 * @property-read int|null $phones_count
 * @property-read Collection|City[] $cities
 * @property-read int|null $cities_count
 * @property-read Collection|LegalEntity[] $entities
 * @property-read int|null $entities_count
 * @method static Builder|ServiceModel except($values = [])
 * @property-read Collection|SystemWorker[] $dispatchers
 * @property-read int|null $dispatchers_count
 * @property-read Country $country
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property-read Collection|FranchiseRegion[] $franchise_region
 * @property-read int|null $franchise_region_count
 * @property-read Collection|FranchiseModule[] $module_role_ids
 * @property-read int|null $module_role_ids_count
 * @property-read Collection|LegalEntity[] $related_entities
 * @property-read int|null $related_entities_count
 * @property-read Collection|FranchiseCity[] $franchise_cities
 * @property-read int|null $franchise_cities_count
 * @property-read Collection|FranchiseModule[] $franchise_modules
 * @property-read int|null $franchise_modules_count
 * @property-read Collection|FranchiseRegion[] $franchise_regions
 * @property-read int|null $franchise_regions_count
 * @property-read Collection|FranchiseRole[] $franchise_roles
 * @property-read int|null $franchise_roles_count
 * @property-read Collection|FranchiseRegion[] $region_city_ids
 * @property-read int|null $region_city_ids_count
 * @property-read Collection|Role[] $roles
 * @property-read int|null $roles_count
 * @property-read Collection|SystemWorker[] $parkManagers
 * @property-read int|null $park_managers_count
 * @property-read FranchiseOption|null $option
 * @property string|null $text
 * @method static Builder|Franchise whereText($value)
 * @property-read int|null $order_bils_count
 * @property-read Collection|FranchiseTransaction[] $order_bils
 * @method static Builder|ServiceModel cordDistance($latitude, $longitude)
 */
class Franchise extends ServiceModel
{
    use HasModules;
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'franchisee';
    /**
     * @var string
     */
    protected $primaryKey = 'franchise_id';
    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'text',
        'phone',
        'email',
        'logo',
        'entity_id',
        'country_id',
        'address',
        'zip_code'
    ];

    /**
     * @param $phone
     */
    public function setPhoneAttribute($phone): void
    {
        $this->attributes['phone'] = preg_replace('/[\D]/', '', $phone);
    }

    /**
     * @return BelongsTo
     */
    public function superAdminCreator(): BelongsTo
    {
        return $this->belongsTo(SuperAdmin::class, 'creator_admin_id');
    }

    /**
     * @return HasMany
     */
    public function parks(): HasMany
    {
        return $this->hasMany(Park::class, 'franchise_id');
    }

    /**
     * @return HasMany
     */
    public function system_workers(): HasMany
    {
        return $this->hasMany(SystemWorker::class, 'system_worker_id');
    }

    /**
     * @return HasMany
     */
    public function drivers(): HasMany
    {
        return $this->hasMany(Driver::class, 'current_franchise_id', 'franchise_id');
    }

    /**
     * @return HasMany
     */
    public function admins(): HasMany
    {
        return $this->hasMany(SystemWorker::class, 'franchise_id')->where('is_admin', true);
    }

    /**
     * @return BelongsToMany
     */
    public function modules(): BelongsToMany
    {
        return $this->belongsToMany(Module::class, 'franchise_module', 'franchise_id', 'module_id');
    }

    /**
     * @return HasMany
     */
    public function franchise_modules(): HasMany
    {
        return $this->hasMany(FranchiseModule::class, 'franchise_id', 'franchise_id');
    }

    /**
     * @return HasMany
     */
    public function franchise_roles(): HasMany
    {
        return $this->hasMany(FranchiseRole::class, 'franchise_id', 'franchise_id');
    }

    /**
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'franchise_role', 'franchise_id', 'role_id');
    }

    /**
     * @return HasMany
     */
    public function module_role_ids(): HasMany
    {
        return $this->hasMany(FranchiseModule::class, 'franchise_id')
            ->select('franchise_module_id', 'franchise_id', 'module_id')
            ->with([
                'franchise_roles' => fn($q) => $q->select('role_id', 'franchise_module_id')
            ]);
    }

    /**
     * @return BelongsTo
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id', 'country_id');
    }

    /**
     * @return BelongsToMany
     */
    public function regions(): BelongsToMany
    {
        return $this->belongsToMany(Region::class, 'franchise_region', $this->primaryKey, 'region_id');
    }

    /**
     * @return BelongsToMany
     */
    public function cities(): BelongsToMany
    {
        return $this->belongsToMany(City::class, 'franchise_city', $this->primaryKey, 'city_id');
    }

    /**
     * @return HasMany
     */
    public function franchise_regions(): HasMany
    {
        return $this->hasMany(FranchiseRegion::class, $this->primaryKey, 'franchise_id');
    }

    /**
     * @return HasMany
     */
    public function franchise_cities(): HasMany
    {
        return $this->hasMany(FranchiseCity::class, $this->primaryKey, 'franchise_id');
    }

    /**
     * @return HasMany
     */
    public function region_city_ids(): HasMany
    {
        return $this->hasMany(FranchiseRegion::class, 'franchise_id')
            ->select('franchise_region_id', 'franchise_id', 'region_id')
            ->with([
                'franchise_cities' => function ($q) {
                    return $q->select('city_id', 'franchise_region_id');
                }
            ]);
    }

    /**
     * @return HasManyThrough
     */
    public function companies(): HasManyThrough
    {
        return $this->hasManyThrough(Company::class, AdminCorporate::class, 'franchise_id', 'admin_corporate_id', 'franchise_id', 'admin_corporate_id');
    }

    /**
     * @return HasMany
     */
    public function corporateAdmins(): HasMany
    {
        return $this->hasMany(AdminCorporate::class, 'franchise_id');
    }

    public function parkManagers(): HasMany
    {
        return $this->hasMany(SystemWorker::class, 'franchise_id', 'franchise_id')
            ->whereHas(
                'worker_roles',
                function ($q) {
                    return $q->where('name', '=', 'park_manager_web');
                }
            );
    }

    /**
     * @return HasMany
     */
    public function dispatchers(): HasMany
    {
        return $this->hasMany(SystemWorker::class, 'franchise_id')
            ->whereHas('worker_dispatcher')
            ->with('worker_dispatcher');
    }

    /**
     * @return HasMany
     */
    public function orders(): HasMany
    {
        return $this->hasManyJson(Order::class, 'franchisee->ids');
    }

    /**
     * @return BelongsTo
     */
    public function reg_city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'registration_city_id', 'city_id');
    }

    /**
     * @return BelongsTo
     */
    public function entity(): BelongsTo
    {
        return $this->belongsTo(LegalEntity::class, 'entity_id', 'legal_entity_id');
    }

    /**
     * @return BelongsToMany
     */
    public function related_entities(): BelongsToMany
    {
        return $this->belongsToMany(LegalEntity::class, 'franchise_entities', 'franchise_id', 'franchise_id');
    }

    /**
     * @return HasMany
     */
    public function phones(): HasMany
    {
        return $this->hasMany(FranchisePhone::class, 'franchise_id', 'franchise_id');
    }

    /**
     * @return HasOne
     */
    public function option(): HasOne
    {
        return $this->hasOne(FranchiseOption::class, $this->primaryKey);
    }

    /**
     * @return HasMany
     */
    public function order_bils(): HasMany
    {
        return $this->hasMany(FranchiseTransaction::class, $this->primaryKey, $this->primaryKey);
    }
}
