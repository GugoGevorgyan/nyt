<?php

declare(strict_types=1);

namespace Src\Models;

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
use Src\Models\Car\Car;
use Src\Models\Driver\Driver;
use Src\Models\Franchise\Franchise;
use Src\Models\LegalEntity\LegalEntity;
use Src\Models\LegalEntity\LegalEntityType;
use Src\Models\Location\City;
use Src\Models\Location\Region;
use Src\Models\Order\Order;
use Src\Models\Role\Role;
use Src\Models\SystemUsers\SystemWorker;
use Src\Models\Terminal\Terminal;
use Src\Models\Terminal\Waybill;
use Znck\Eloquent\Relations\BelongsToThrough;

/**
 * Class Park
 *
 * @package Src\Models
 * @property int $park_id
 * @property int $entity_id
 * @property string|null $name
 * @property int|null $city_id
 * @property string|null $address
 * @property string|null $image
 * @property int|null $owner_id
 * @property int|null $franchise_id
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Car[] $cars
 * @property-read int|null $cars_count
 * @property-read City|null $city
 * @property-read Collection|Driver[] $drivers
 * @property-read int|null $drivers_count
 * @property-read LegalEntity $entity
 * @property-read Franchise $franchise
 * @property-read Collection|Order[] $orders
 * @property-read int|null $orders_count
 * @property-read SystemWorker|null $parkManager
 * @property-read Terminal|null $terminal
 * @property-read Collection|Waybill[] $waybills
 * @property-read int|null $waybills_count
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|Park newModelQuery()
 * @method static Builder|Park newQuery()
 * @method static \Illuminate\Database\Query\Builder|Park onlyTrashed()
 * @method static Builder|Park query()
 * @method static Builder|Park whereAddress($value)
 * @method static Builder|Park whereCityId($value)
 * @method static Builder|Park whereCreatedAt($value)
 * @method static Builder|Park whereDeletedAt($value)
 * @method static Builder|Park whereEntityId($value)
 * @method static Builder|Park whereFranchiseId($value)
 * @method static Builder|Park whereImage($value)
 * @method static Builder|Park whereName($value)
 * @method static Builder|Park whereOwnerId($value)
 * @method static Builder|Park whereParkId($value)
 * @method static Builder|Park whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Park withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Park withoutTrashed()
 * @mixin Eloquent
 * @property-read SystemWorker $manager
 * @property int|null $manager_id
 * @method static Builder|Park whereManagerId($value)
 * @method static Builder|ServiceModel cordDistance($latitude, $longitude)
 */
class Park extends ServiceModel
{
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'parks';
    /**
     * @var string
     */
    protected $primaryKey = 'park_id';
    /**
     * @var array
     */
    protected $fillable = ['manager_id', 'franchise_id', 'name', 'city_id', 'address', 'entity_id'];

    /**
     * @return HasMany
     */
    public function cars(): HasMany
    {
        return $this->hasMany(Car::class, 'park_id');
    }

    /**
     * @return HasMany
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, $this->primaryKey, 'order_id');
    }

    /**
     * @return BelongsTo
     */
    public function franchise(): BelongsTo
    {
        return $this->belongsTo(Franchise::class, $this->primaryKey, 'franchise_id');
    }

    /**
     * @return HasOne
     */
    public function terminal(): HasOne
    {
        return $this->hasOne(Terminal::class, 'terminal_id');
    }

    /**
     * @return BelongsTo
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id', 'city_id');
    }

    /**
     * @return BelongsToThrough
     */
    public function region(): BelongsToThrough
    {
        return $this->belongsToThrough(Region::class, City::class, 'city_id');
    }

    /**
     * @return BelongsTo
     */
    public function manager(): BelongsTo
    {
        return $this->belongsTo(SystemWorker::class, 'manager_id', 'system_worker_id');
    }

    /**
     * @return BelongsToMany
     */
    public function mechanics(): BelongsToMany
    {
        return $this
            ->belongsToMany(SystemWorker::class, 'park_mechanic', 'park_id', 'mechanic_id')
            ->where('franchise_id', FRANCHISE_ID)
            ->orWhereHas('roles', fn(Builder $role_query) => $role_query
                ->where('name', Role::MECHANIC_WEB)
                ->orWhere('name', Role::MECHANIC_API)
            );
    }

    /**
     * @return HasManyThrough
     */
    public function drivers(): HasManyThrough
    {
        return $this->hasManyThrough(Driver::class, Car::class, 'park_id', 'car_id', 'park_id', 'car_id');
    }

    /**
     * @return HasManyThrough
     */
    public function waybills(): HasManyThrough
    {
        return $this->hasManyThrough(Waybill::class, Car::class, 'park_id', 'car_id', 'park_id', 'car_id');
    }

    /**
     * @return BelongsTo
     */
    public function entity(): BelongsTo
    {
        return $this->belongsTo(LegalEntity::class, 'entity_id', 'legal_entity_id');
    }

    /**
     * @return BelongsToThrough
     */
    public function entity_type(): BelongsToThrough
    {
        return $this->belongsToThrough(LegalEntityType::class, LegalEntity::class, null, '',
            [LegalEntityType::class => 'legal_entity_id', LegalEntity::class => 'type_id']);
    }
}
