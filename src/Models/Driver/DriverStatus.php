<?php

declare(strict_types=1);

namespace Src\Models\Driver;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;

/**
 * Class DriverStatus
 *
 * @package Src\Models\Driver
 * @property int $driver_status_id
 * @property string $name
 * @property int $value
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Driver[] $drivers
 * @property-read int|null $drivers_count
 * @method static Builder|DriverStatus newModelQuery()
 * @method static Builder|DriverStatus newQuery()
 * @method static Builder|DriverStatus query()
 * @method static Builder|ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|DriverStatus whereCreatedAt($value)
 * @method static Builder|DriverStatus whereDriverStatusId($value)
 * @method static Builder|DriverStatus whereName($value)
 * @method static Builder|DriverStatus whereUpdatedAt($value)
 * @method static Builder|DriverStatus whereValue($value)
 * @mixin Eloquent
 * @property int $status
 * @method static Builder|DriverStatus whereStatus($value)
 * @property string $text
 * @property string $color
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|DriverStatus whereColor($value)
 * @method static Builder|DriverStatus whereText($value)
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static Builder|ServiceModel cordDistance($latitude, $longitude)
 */
class DriverStatus extends ServiceModel
{
    /**
     *
     */
    public const DRIVER_IS_FREE = 1;
    /**
     *
     */
    public const DRIVER_ON_ACCEPT = 2;
    /**
     *
     */
    public const DRIVER_ON_WAY = 3;
    /**
     *
     */
    public const DRIVER_IN_PLACE = 4;
    /**
     *
     */
    public const DRIVER_IN_ORDER = 5;
    /**
     *
     */
    public const DRIVER_SLIP_NUMBER = 6;


    /**
     * @var string
     */
    protected $table = 'driver_statuses';
    /**
     * @var string
     */
    protected $primaryKey = 'driver_status_id';
    /**
     * @var array
     */
    protected $fillable = ['name', 'status'];

    /**
     * @return HasMany
     */
    public function drivers(): HasMany
    {
        return $this->hasMany(Driver::class, 'current_status_id');
    }
}
