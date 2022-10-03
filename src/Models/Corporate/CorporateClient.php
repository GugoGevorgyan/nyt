<?php

declare(strict_types=1);

namespace Src\Models\Corporate;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;
use Src\Core\Traits\Excludable;
use Src\Models\Car\CarClass;
use Src\Models\Client\Client;
use Src\Models\Client\ClientAddress;
use Src\Models\Order\CanceledOrder;
use Src\Models\Order\Order;
use Src\Models\Order\OrderStatus;
use Src\Scopes\CorporateClientScope;
use Staudenmeir\EloquentJsonRelations\Relations\BelongsToJson;
use Znck\Eloquent\Relations\BelongsToThrough;

/**
 * Src\Models\Corporate\CorporateClient
 *
 * @property int $corporate_client_id
 * @property int|null $client_id
 * @property int|null $company_id
 * @property array|null $car_ids
 * @property int|null $allow_weekends
 * @property int|null $allow_order
 * @property int $limit
 * @property float $spent
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Client|null $client
 * @property-read Company|null $company
 * @method static Builder|CorporateClient exclude($columns)
 * @method static Builder|CorporateClient newModelQuery()
 * @method static Builder|CorporateClient newQuery()
 * @method static Builder|CorporateClient query()
 * @method static Builder|CorporateClient whereAllowOrder($value)
 * @method static Builder|CorporateClient whereAllowWeekends($value)
 * @method static Builder|CorporateClient whereCarIds($value)
 * @method static Builder|CorporateClient whereClientId($value)
 * @method static Builder|CorporateClient whereCompanyId($value)
 * @method static Builder|CorporateClient whereCorporateClientId($value)
 * @method static Builder|CorporateClient whereCreatedAt($value)
 * @method static Builder|CorporateClient whereLimit($value)
 * @method static Builder|CorporateClient whereSpent($value)
 * @method static Builder|CorporateClient whereUpdatedAt($value)
 * @mixin Eloquent
 * @method static Builder|ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @property string|null $email
 * @property string|null $patronymic
 * @property array|null $car_classes_ids
 * @property-read Collection|CanceledOrder[] $canceled_orders
 * @property-read int|null $canceled_orders_count
 * @property-read CanceledOrder|null $current_canceled_order
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|CorporateClient whereCarClassesIds($value)
 * @method static Builder|CorporateClient whereEmail($value)
 * @method static Builder|CorporateClient wherePatronymic($value)
 * @property string|null $name
 * @property string|null $surname
 * @method static Builder|CorporateClient whereName($value)
 * @method static Builder|CorporateClient whereSurname($value)
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property-read Collection|ClientAddress[] $client_addresses
 * @property-read int|null $client_addresses_count
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 */
class CorporateClient extends ServiceModel
{
    use Excludable;

    /**
     * @var string
     */
    protected $table = 'corporate_clients';
    /**
     * @var string
     */
    protected $primaryKey = 'corporate_client_id';
    /**
     * @var array
     */
    protected $fillable = [
        'address',
        'name',
        'surname',
        'patronymic',
        'car_classes_ids',
        'allow_weekends',
        'allow_order',
        'limit',
        'spent',
        'client_id',
        'company_id'
    ];
    /**
     * @var array
     */
    protected $casts = ['car_classes_ids' => 'json'];


    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();

        static::addGlobalScope(new CorporateClientScope());
    }

    /**
     * @return BelongsToJson
     */
    public function carClasses(): BelongsToJson
    {
        return $this->belongsToJson(CarClass::class, 'car_class_id', 'car_classes_ids->ids');
    }

    /**
     * @return HasMany
     */
    public function client_addresses(): HasMany
    {
        return $this->hasMany(ClientAddress::class, 'client_id', 'client_id');
    }

    /**
     * @return BelongsTo
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id', 'client_id');
    }

    /**
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id', 'company_id');
    }

    /**
     * @return MorphMany
     */
    public function canceled_orders(): MorphMany
    {
        return $this->morphMany(CanceledOrder::class, 'cancelable');
    }

    /**
     * @return MorphOne
     */
    public function current_canceled_order(): MorphOne
    {
        return $this->morphOne(CanceledOrder::class, 'cancelable');
    }

    /**
     * @return BelongsToThrough
     */
    public function current_order(): BelongsToThrough
    {
        return $this
            ->belongsToThrough(Order::class, Client::class, null, '', [Order::class => 'client_id', Client::class => 'client_id'])
            ->where('orders.status_id', '!=', OrderStatus::getStatusId(OrderStatus::ORDER_COMPLETED))
            ->where('orders.status_id', '!=', OrderStatus::getStatusId(OrderStatus::ORDER_CANCELED));
    }
}
