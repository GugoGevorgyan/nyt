<?php

declare(strict_types=1);

namespace Src\Models\Franchise;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;

/**
 * Src\Models\Franchise\FranchisePhone
 *
 * @property int $franchise_phone_id
 * @property int|null $franchise_id
 * @property string $number
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|FranchisePhone newModelQuery()
 * @method static Builder|FranchisePhone newQuery()
 * @method static Builder|FranchisePhone query()
 * @method static Builder|FranchisePhone whereCreatedAt($value)
 * @method static Builder|FranchisePhone whereFranchiseId($value)
 * @method static Builder|FranchisePhone whereFranchisePhoneId($value)
 * @method static Builder|FranchisePhone whereNumber($value)
 * @method static Builder|FranchisePhone whereUpdatedAt($value)
 * @mixin Eloquent
 * @property-read Collection|FranchiseSubPhone[] $subPhones
 * @property-read int|null $sub_phones_count
 * @method static Builder|ServiceModel except($values = [])
 * @property-read Franchise|null $franchise
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
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
 */
class FranchisePhone extends ServiceModel
{
    /**
     * @var string
     */
    protected $table = 'franchise_phones';
    /**
     * @var string
     */
    protected $primaryKey = 'franchise_phone_id';
    /**
     * @var string[]
     */
    protected $fillable = ['franchise_id', 'number'];

    /**
     * @param $number
     */
    public function setNumberAttribute($number): void
    {
        $this->attributes['number'] = preg_replace('/[\D]/', '', $number);
    }

    /**
     * @return HasMany
     */
    public function subPhones(): HasMany
    {
        return $this->hasMany(FranchiseSubPhone::class, 'franchise_phone_id', 'franchise_phone_id');
    }

    /**
     * @return BelongsTo
     */
    public function franchise(): BelongsTo
    {
        return $this->belongsTo(Franchise::class, 'franchise_id', 'franchise_id');
    }
}
