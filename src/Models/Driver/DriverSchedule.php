<?php
declare(strict_types=1);

namespace Src\Models\Driver;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;

/**
 * Src\Models\Driver\DriverSchedule
 *
 * @property int $driver_schedule_id
 * @property int $driver_id
 * @property int $driver_contract_id
 * @property int $working
 * @property int $accident
 * @property string $date
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read DriverContract $contract
 * @property-read Driver $driver
 * @method static Builder|DriverSchedule newModelQuery()
 * @method static Builder|DriverSchedule newQuery()
 * @method static Builder|DriverSchedule query()
 * @method static Builder|DriverSchedule whereAccident($value)
 * @method static Builder|DriverSchedule whereCreatedAt($value)
 * @method static Builder|DriverSchedule whereDate($value)
 * @method static Builder|DriverSchedule whereDeletedAt($value)
 * @method static Builder|DriverSchedule whereDriverContractId($value)
 * @method static Builder|DriverSchedule whereDriverId($value)
 * @method static Builder|DriverSchedule whereDriverScheduleId($value)
 * @method static Builder|DriverSchedule whereUpdatedAt($value)
 * @method static Builder|DriverSchedule whereWorking($value)
 * @mixin Eloquent
 * @method static Builder|ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property int $day
 * @property int $month
 * @property int $year
 * @method static Builder|DriverSchedule whereDay($value)
 * @method static Builder|DriverSchedule whereMonth($value)
 * @method static Builder|DriverSchedule whereYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 */
class DriverSchedule extends ServiceModel
{
    /**
     * @var array
     */
    protected $fillable = [
        'driver_id',
        'driver_contract_id',
        'working',
        'accident',
        'date',
        'day',
        'month',
        'year'
    ];

    /**
     * @var string
     */
    protected $primaryKey = 'driver_schedule_id';

    /**
     * @return BelongsTo
     */
    public function contract(): BelongsTo
    {
        return $this->belongsTo(DriverContract::class, 'driver_contract_id', 'driver_contract_id');
    }

    /**
     * @return BelongsTo
     */
    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class, 'driver_id', 'driver_id');
    }
}
