<?php

declare(strict_types=1);

namespace Src\Models\Order;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;
use Src\Models\Corporate\Company;
use Src\Models\Driver\Driver;

/**
 * Src\Models\Order\OrderCorporate
 *
 * @property int $order_corporate_id
 * @property int $order_id
 * @property int $company_id
 * @property int $driver_id
 * @property int $slip_number
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|OrderCorporate newModelQuery()
 * @method static Builder|OrderCorporate newQuery()
 * @method static Builder|OrderCorporate query()
 * @method static Builder|OrderCorporate whereCompanyId($value)
 * @method static Builder|OrderCorporate whereCreatedAt($value)
 * @method static Builder|OrderCorporate whereDriverId($value)
 * @method static Builder|OrderCorporate whereOrderCorporateId($value)
 * @method static Builder|OrderCorporate whereOrderId($value)
 * @method static Builder|OrderCorporate whereSlipNumber($value)
 * @method static Builder|OrderCorporate whereUpdatedAt($value)
 * @mixin Eloquent
 * @property string|null $deleted_at
 * @property-read \Src\Models\Corporate\Company $company
 * @property-read \Src\Models\Driver\Driver $driver
 * @property-read \Src\Models\Order\Order $order
 * @method static \Illuminate\Database\Eloquent\Builder|\ServiceEntity\Models\ServiceModel except($values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Order\OrderCorporate whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel comparison($geometryColumn, $geometry, $relationship)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel contains($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel crosses($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel disjoint($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel distanceExcludingSelf($geometryColumn, $geometry, $distance)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel distanceSphere($geometryColumn, $geometry, $distance)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel distanceSphereExcludingSelf($geometryColumn, $geometry, $distance)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel distanceSphereValue($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel distanceValue($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel doesTouch($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel equals($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel intersects($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel orderByDistance($geometryColumn, $geometry, $direction = 'asc')
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel orderByDistanceSphere($geometryColumn, $geometry, $direction = 'asc')
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel orderBySpatial($geometryColumn, $geometry, $orderFunction, $direction = 'asc')
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel overlaps($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel within($geometryColumn, $polygon)
 * @property-read \Illuminate\Database\Eloquent\Collection|\Src\Models\Order\CompletedOrder[] $completed
 * @property-read int|null $completed_count
 */
class OrderCorporate extends ServiceModel
{
    /**
     * @var string
     */
    protected $table = 'order_corporates';

    /**
     * @var string
     */
    protected $primaryKey = 'order_corporate_id';

    /**
     * @var string[]
     */
    protected $fillable = ['order_id', 'company_id', 'driver_id', 'slip_number'];

    /**
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * @return BelongsTo
     */
    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    /**
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class,'company_id');
    }

    public function completed(): HasMany
    {
        return $this->hasMany(CompletedOrder::class,'order_id','order_id');
    }
}
