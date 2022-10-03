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
use Src\Models\SystemUsers\WorkerDispatcher;
use Src\Models\SystemUsers\WorkerOperator;

/**
 * Src\Models\Franchise\FranchiseSubPhone
 *
 * @property int $franchise_sub_phone_id
 * @property int|null $franchise_phone_id
 * @property string $number
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|FranchiseSubPhone newModelQuery()
 * @method static Builder|FranchiseSubPhone newQuery()
 * @method static Builder|FranchiseSubPhone query()
 * @method static Builder|FranchiseSubPhone whereCreatedAt($value)
 * @method static Builder|FranchiseSubPhone whereFranchisePhoneId($value)
 * @method static Builder|FranchiseSubPhone whereFranchiseSubPhoneId($value)
 * @method static Builder|FranchiseSubPhone whereNumber($value)
 * @method static Builder|FranchiseSubPhone whereUpdatedAt($value)
 * @mixin Eloquent
 * @property-read WorkerOperator $activeWorker
 * @property string $password
 * @property-read Collection|WorkerOperator[] $atc_logged_operators
 * @property-read int|null $atc_logged_operators_count
 * @property-read Collection|WorkerOperator[] $operators
 * @property-read int|null $operators_count
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|FranchiseSubPhone wherePassword($value)
 * @property-read Collection|WorkerDispatcher[] $atc_logged_dispatchers
 * @property-read int|null $atc_logged_dispatchers_count
 * @property-read Collection|WorkerDispatcher[] $dispatchers
 * @property-read int|null $dispatchers_count
 * @property-read FranchisePhone|null $franchisePhone
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
class FranchiseSubPhone extends ServiceModel
{
    /**
     * @var string
     */
    protected $table = 'franchise_sub_phones';

    /**
     * @var string
     */
    protected $primaryKey = 'franchise_sub_phone_id';

    /**
     * @var string[]
     */
    protected $fillable = ['franchise_phone_id', 'number', 'password'];

    /**
     * @return HasMany
     */
    public function operators(): HasMany
    {
        return $this->hasMany(WorkerOperator::class, 'franchise_sub_phone_id', 'franchise_sub_phone_id');
    }

    /**
     * @return BelongsTo
     */
    public function franchisePhone(): BelongsTo
    {
        return $this->belongsTo(FranchisePhone::class, 'franchise_phone_id', 'franchise_phone_id');
    }

    /**
     * @return HasMany
     */
    public function dispatchers(): HasMany
    {
        return $this->hasMany(WorkerDispatcher::class, 'franchise_sub_phone_id', 'franchise_sub_phone_id');
    }

    /**
     * @return HasMany
     */
    public function atc_logged_operators(): HasMany
    {
        return $this->hasMany(WorkerOperator::class, 'franchise_sub_phone_id', 'franchise_sub_phone_id')
            ->where('atc_logged', '=', 1);
    }

    /**
     * @return HasMany
     */
    public function atc_logged_dispatchers(): HasMany
    {
        return $this->hasMany(WorkerDispatcher::class, 'franchise_sub_phone_id', 'franchise_sub_phone_id')
            ->where('atc_logged', '=', 1);
    }

}
