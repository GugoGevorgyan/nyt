<?php

declare(strict_types=1);

namespace Src\Models\Driver;


use Illuminate\Database\Eloquent\Relations\BelongsTo;
use ServiceEntity\Models\ServiceModel;

/**
 * Class DriverLock
 *
 * @package Src\Models\Driver
 * @property int $driver_lock_id
 * @property int $driver_id
 * @property bool $locked
 * @property int $lock_count
 * @property \Illuminate\Support\Carbon|null $start
 * @property \Illuminate\Support\Carbon|null $end
 * @property \Illuminate\Support\Carbon $created_at
 * @property-read \Src\Models\Driver\Driver $driver
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, int $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel except(array $values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static \Illuminate\Database\Eloquent\Builder|DriverLock newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DriverLock newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DriverLock query()
 * @method static \Illuminate\Database\Eloquent\Builder|DriverLock whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DriverLock whereDriverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DriverLock whereDriverLockId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DriverLock whereEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DriverLock whereLockCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DriverLock whereLocked($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DriverLock whereStart($value)
 * @mixin \Eloquent
 */
class DriverLock extends ServiceModel
{


    public $timestamps = false;
    /**
     * @var string
     */
    protected $table = 'drivers_lock';
    /**
     * @var string
     */
    protected $primaryKey = 'driver_lock_id';
    /**
     * @var string[]
     */
    protected $fillable = ['driver_id', 'locked', 'lock_count', 'start', 'end'];
    /**
     * @var string[]
     */
    protected $casts = ['locked' => 'bool'];
    /**
     * @var string[]
     */
    protected $dates = ['start', 'end', 'created_at'];

    /**
     * @return BelongsTo
     */
    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }
}
