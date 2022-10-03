<?php

declare(strict_types=1);

namespace Src\Models\Corporate;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;
use Src\Core\Traits\Excludable;
use Src\Models\Client\Client;
use Src\Models\Client\ClientAddress;
use Src\Models\Client\ClientCall;
use Src\Models\Franchise\Franchise;
use Src\Models\Franchise\FranchiseTransaction;
use Src\Models\LegalEntity\LegalEntity;
use Src\Models\Location\Country;
use Src\Models\Order\Order;
use Src\Models\Order\OrderCorporate;
use Src\Models\Tariff\Tariff;
use Src\Scopes\CompanyPhonesScope;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;
use Znck\Eloquent\Relations\BelongsToThrough;

/**
 * Src\Models\Corporate\Company
 *
 * @property int $company_id
 * @property int $admin_corporate_id
 * @property string $name
 * @property string $email
 * @property string $actual_address
 * @property string $legal_address
 * @property string $limit
 * @property float $spent
 * @property string|null $details
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Client[] $clients
 * @property-read AdminCorporate $corporateAdmin
 * @property-read Collection|CorporateClient[] $corporateClients
 * @property-read Collection|Order[] $orders
 * @method static Builder|Company newModelQuery()
 * @method static Builder|Company newQuery()
 * @method static Builder|Company query()
 * @method static Builder|Company whereActualAddress($value)
 * @method static Builder|Company whereAdminCorporateId($value)
 * @method static Builder|Company whereCompanyId($value)
 * @method static Builder|Company whereCreatedAt($value)
 * @method static Builder|Company whereDetails($value)
 * @method static Builder|Company whereEmail($value)
 * @method static Builder|Company whereLegalAddress($value)
 * @method static Builder|Company whereLimit($value)
 * @method static Builder|Company whereName($value)
 * @method static Builder|Company whereSpent($value)
 * @method static Builder|Company whereUpdatedAt($value)
 * @mixin Eloquent
 * @property int $order_canceled_timeout
 * @property int $period Период времени
 * @property int $frequency Количество отчетов в этот период времени
 * @property string $code Внутренний код компании
 * @property string $contract_start
 * @property string $contract_end
 * @property string $contract_scan
 * @property-read int|null $addresses_count
 * @property-read int|null $clients_count
 * @property-read int|null $corporate_clients_count
 * @property-read int|null $orders_count
 * @property-read Collection|Tariff[] $tariffs
 * @property-read int|null $tariffs_count
 * @method static Builder|Company exclude($columns)
 * @method static Builder|Company whereCode($value)
 * @method static Builder|Company whereContractEnd($value)
 * @method static Builder|Company whereContractScan($value)
 * @method static Builder|Company whereContractStart($value)
 * @method static Builder|Company whereFrequency($value)
 * @method static Builder|Company whereOrderCanceledTimeout($value)
 * @method static Builder|Company wherePeriod($value)
 * @method static Builder|ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @property-read Collection|ClientCall[] $calls
 * @property-read int|null $calls_count
 * @property-read Collection|Order[] $last_orders
 * @property-read int|null $last_orders_count
 * @property-read Collection|CompanyPhone[] $phones
 * @property-read int|null $phones_count
 * @property int $franchise_id
 * @property int $entity_id
 * @property string|null $logo
 * @property-read Collection|AdminCorporate[] $corporateAdmins
 * @property-read int|null $corporate_admins_count
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|Company whereEntityId($value)
 * @method static Builder|Company whereFranchiseId($value)
 * @method static Builder|Company whereLogo($value)
 * @property string $address
 * @method static Builder|Company whereAddress($value)
 * @property-read LegalEntity $entity
 * @property-read Franchise $franchise
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property-read FranchiseTransaction|null $second_side
 * @property-read FranchiseTransaction|null $side
 * @method static Builder|ServiceModel cordDistance($latitude, $longitude)
 */
class Company extends ServiceModel
{
    use Excludable;

    /**
     * @var string
     */
    protected $table = 'companies';
    /**
     * @var string
     */
    protected $primaryKey = 'company_id';
    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'address',
        'franchise_id',
        'entity_id',
        'details',
        'order_canceled_timeout',
        'period',
        'frequency',
        'contract_start',
        'contract_end',
        'contract_scan',
        'limit',
        'spent',
        'details',
        'code'
    ];
    /**
     * @var array
     */
    protected $casts = ['car_class_ids' => 'json'];
    /**
     * @var string
     */
    protected string $map = 'Company';

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new CompanyPhonesScope());
    }

    /**
     * @return BelongsToMany
     */
    public function clients(): BelongsToMany
    {
        return $this->belongsToMany(Client::class, 'corporate_clients', 'company_id', 'client_id');
    }

    /**
     * @return BelongsToMany
     */
    public function tariffs(): BelongsToMany
    {
        return $this->belongsToMany(Tariff::class, 'company_tariff', 'company_id', 'tariff_id');
    }

    /**
     * @return HasMany
     */
    public function corporateClients(): HasMany
    {
        return $this->hasMany(CorporateClient::class, 'company_id');
    }

    /**
     * @return HasMany
     */
    public function corporateAdmins(): HasMany
    {
        return $this->hasMany(AdminCorporate::class, 'company_id', 'company_id');
    }

    /**
     * @return BelongsToMany
     */
    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'order_corporates', 'order_id', 'company_id');
    }

    public function completed()
    {
        return $this->hasManyDeepFromRelations($this->corporates(), (new OrderCorporate())->completed());
    }

    public function corporates()
    {
        return $this->hasMany(OrderCorporate::class, 'company_id', 'company_id');
    }

    /**
     * @return MorphMany
     */
    public function calls(): MorphMany
    {
        return $this->morphMany(ClientCall::class, 'callable');
    }

    /**
     * @return HasMany
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(ClientAddress::class, $this->primaryKey)->with('client');
    }

    /**
     * @return HasMany
     */
    public function phones(): HasMany
    {
        return $this->hasMany(CompanyPhone::class, $this->primaryKey, $this->primaryKey);
    }

    /**
     * @return BelongsTo
     */
    public function entity(): BelongsTo
    {
        return $this->belongsTo(LegalEntity::class, 'entity_id');
    }

    /**
     * @return HasManyDeep
     */
    public function franchise_regions(): HasManyDeep
    {
        return $this->hasManyDeepFromRelations($this->franchise(), (new Franchise())->regions());
    }

    /**
     * @return BelongsTo
     */
    public function franchise(): BelongsTo
    {
        return $this->belongsTo(Franchise::class, 'franchise_id');
    }

    /**
     * @return \Znck\Eloquent\Relations\BelongsToThrough
     */
    public function country()
    {
        return $this->belongsToThrough(Country::class, Franchise::class, 'franchise_id', '',
            [Country::class => 'country_id', Franchise::class => 'franchise_id']);
    }

    /**
     * @return MorphOne
     */
    public function side(): MorphOne
    {
        return $this->morphOne(FranchiseTransaction::class, 'side', 'side_type', 'side_id');
    }

    /**
     * @return MorphOne
     */
    public function second_side(): MorphOne
    {
        return $this->morphOne(FranchiseTransaction::class, 'second_side', 'second_side_type', 'second_side_id');
    }

    /**
     * @return HasMany
     */
    protected function order_reports(): HasMany
    {
        return $this->hasMany(CompanyReport::class, $this->primaryKey);
    }
}
