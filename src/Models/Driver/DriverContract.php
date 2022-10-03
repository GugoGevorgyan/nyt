<?php

declare(strict_types=1);

namespace Src\Models\Driver;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;
use Src\Models\Car\Car;
use Src\Models\LegalEntity\LegalEntity;

/**
 * Src\Models\Driver\DriverContract
 *
 * @property int $driver_contract_id
 * @property int $driver_id
 * @property int $driver_type_id
 * @property int $driver_graphic_id
 * @property int $car_id
 * @property string $signing_day
 * @property string $expiration_day
 * @property string $work_start_day
 * @property int $duration
 * @property int $active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Car $car
 * @property-read Driver $driver
 * @property-read DriverGraphic $graphic
 * @property-read Collection|DriverSchedule[] $schedules
 * @property-read int|null $schedules_count
 * @property-read DriverType $type
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|DriverContract newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DriverContract newQuery()
 * @method static Builder|DriverContract onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|DriverContract query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|DriverContract whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DriverContract whereCarId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DriverContract whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DriverContract whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DriverContract whereDriverContractId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DriverContract whereDriverGraphicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DriverContract whereDriverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DriverContract whereDriverTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DriverContract whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DriverContract whereExpirationDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DriverContract whereSigningDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DriverContract whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DriverContract whereWorkStartDay($value)
 * @method static Builder|DriverContract withTrashed()
 * @method static Builder|DriverContract withoutTrashed()
 * @mixin Eloquent
 * @property int $driver_subtype_id
 * @property int $entity_id
 * @property int $signed
 * @property-read LegalEntity $entity
 * @property-read DriverSubtype $subtype
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static \Illuminate\Database\Eloquent\Builder|DriverContract whereDriverSubtypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DriverContract whereEntityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DriverContract whereSigned($value)
 * @property int|null $free_days_price
 * @property int|null $busy_days_price
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel except($values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|DriverContract whereBusyDaysPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DriverContract whereFreeDaysPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @property string|null $amount_paid
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static \Illuminate\Database\Eloquent\Builder|DriverContract whereAmountPaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DriverContract whereCashlessPercent($value)
 * @property string|null $scan
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|DriverContract whereScan($value)
 */
class DriverContract extends ServiceModel
{
    use SoftDeletes;

    protected $table = 'driver_contracts';
    /**
     * @var string
     */
    protected $primaryKey = 'driver_contract_id';
    /**
     * @var array
     */
    protected $fillable = [
        'driver_id',
        'driver_type_id',
        'driver_subtype_id',
        'driver_graphic_id',
        'car_id',
        'entity_id',
        'signing_day',
        'expiration_day',
        'work_start_day',
        'duration',
        'active',
        'signed',
        'free_days_price',
        'busy_days_price',
        'scan'
    ];
    /**
     * @var string[]
     */
    protected $casts = ['free_days_price' => 'float', 'busy_days_price' => 'float', 'amount_paid' => 'float'];


    /**
     * @return BelongsTo
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(DriverType::class, 'driver_type_id', 'driver_type_id');
    }

    /**
     * @return BelongsTo
     */
    public function subtype(): BelongsTo
    {
        return $this->belongsTo(DriverSubtype::class, 'driver_subtype_id', 'driver_subtype_id');
    }

    /**
     * @return BelongsTo
     */
    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class, 'car_id', 'car_id');
    }

    /**
     * @return BelongsTo
     */
    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class, 'driver_id', 'driver_id');
    }


    /**
     * @return BelongsTo
     */
    public function graphic(): BelongsTo
    {
        return $this->belongsTo(DriverGraphic::class, 'driver_graphic_id', 'driver_graphic_id');
    }

    /**
     * @return HasMany
     */
    public function schedules(): HasMany
    {
        return $this->hasMany(DriverSchedule::class, 'driver_contract_id', 'driver_contract_id');
    }

    /**
     * @return BelongsTo
     */
    public function entity(): BelongsTo
    {
        return $this->belongsTo(LegalEntity::class, 'entity_id', 'legal_entity_id');
    }
}
