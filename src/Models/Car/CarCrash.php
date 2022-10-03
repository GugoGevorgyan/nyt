<?php

declare(strict_types=1);

namespace Src\Models\Car;

use Eloquent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;
use Src\Models\Driver\Driver;
use Src\Models\Franchise\FranchiseTransaction;

/**
 * Class CarCrash
 *
 * @package Src\Models\Car
 * @property int $car_crash_id
 * @property int|null $car_id
 * @property int|null $driver_id
 * @property string $dateTime
 * @property string $address
 * @property string $description
 * @property int $our_fault
 * @property string $inspector_info
 * @property string $participant_info
 * @property string|null $act
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $unix_date
 * @property-read Driver|null $driver
 * @property-read Collection|CarCrashImage[] $images
 * @property-read int|null $images_count
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|CarCrash newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CarCrash newQuery()
 * @method static Builder|CarCrash onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|CarCrash query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|CarCrash whereAct($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarCrash whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarCrash whereCarCrashId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarCrash whereCarId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarCrash whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarCrash whereDateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarCrash whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarCrash whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarCrash whereDriverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarCrash whereInspectorInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarCrash whereOurFault($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarCrash whereParticipantInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarCrash whereUnixDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarCrash whereUpdatedAt($value)
 * @method static Builder|CarCrash withTrashed()
 * @method static Builder|CarCrash withoutTrashed()
 * @mixin Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel except($values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property float|null $act_sum
 * @method static \Illuminate\Database\Eloquent\Builder|CarCrash whereActSum($value)
 * @property-read Car|null $car
 * @property-read FranchiseTransaction|null $transaction
 * @property-read FranchiseTransaction|null $transaction_reason
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 */
class CarCrash extends ServiceModel
{
    /**
     * @var string
     */
    protected $table = 'car_crashes';
    /**
     * @var string
     */
    protected $primaryKey = 'car_crash_id';
    /**
     * @var array
     */
    protected $fillable = [
        'car_id',
        'driver_id',
        'address',
        'description',
        'our_fault',
        'dateTime',
        'inspector_info',
        'participant_info',
        'act',
        'act_sum'
    ];
    /**
     * @var string[]
     */
    protected $casts = ['act_sum' => 'float'];
    /**
     * @var string
     */
    protected string $map = 'crash';

    /**
     * @return BelongsTo
     */
    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }

    /**
     * @return HasMany
     */
    public function images(): HasMany
    {
        return $this->hasMany(CarCrashImage::class, 'car_crash_id');
    }

    /**
     * @return MorphOne
     */
    public function transaction(): MorphOne
    {
        return $this->morphOne(FranchiseTransaction::class, 'reason', 'reason_type', 'reason_id');
    }

    /**
     * @return MorphOne
     */
    public function transaction_reason(): MorphOne
    {
        return $this->morphOne(FranchiseTransaction::class, 'reason', 'reason_type', 'reason_id');
    }

    /**
     * @return BelongsTo
     */
    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class, 'car_id', 'car_id');
    }
}
