<?php

declare(strict_types=1);

namespace Src\Models\Driver;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;

/**
 * Class DriverCoordinate
 *
 * @package Src\Models
 * @property int $driver_coordinate_id
 * @property int|null $driver_id
 * @property string|null $current_latitude
 * @property string|null $current_longitude
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Driver $driver
 * @method static Builder|DriverCoordinate newModelQuery()
 * @method static Builder|DriverCoordinate newQuery()
 * @method static Builder|DriverCoordinate query()
 * @method static Builder|DriverCoordinate whereCreatedAt($value)
 * @method static Builder|DriverCoordinate whereCurrentLatitude($value)
 * @method static Builder|DriverCoordinate whereCurrentLongitude($value)
 * @method static Builder|DriverCoordinate whereDriverCoordinateId($value)
 * @method static Builder|DriverCoordinate whereDriverId($value)
 * @method static Builder|DriverCoordinate whereUpdatedAt($value)
 * @mixin Eloquent
 * @property string|null $coordinates
 * @method static Builder|DriverCoordinate whereCoordinates($value)
 * @property Carbon $date
 * @method static Builder|DriverCoordinate whereDate($value)
 * @method static Builder|ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 */
class DriverCoordinate extends ServiceModel
{
    /**
     * @var bool
     */
    public $timestamps = false;
    /**
     * @var string
     */
    protected $table = 'driver_coordinates';
    /**
     * @var string
     */
    protected $primaryKey = 'driver_coordinate_id';
    /**
     * @var array
     */
    protected $fillable = ['coordinates', 'driver_id', 'date'];
    /**
     * @var array
     */
    protected $casts = ['coordinates' => 'array'];
    /**
     * @var array
     */
    protected $dates = ['date'];

    /**
     * @return BelongsTo
     */
    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class, 'driver_id', 'driver_id');
    }
}
