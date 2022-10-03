<?php

declare(strict_types=1);

namespace Src\Models\Penalty;

use Illuminate\Database\Eloquent\Model;
use ServiceEntity\Models\ServiceModel;
use Src\Models\Debt\Debt;

/**
 * Src\Models\Penalty\Penalty
 *
 * @property int $penalty_id
 * @property int $debt_id
 * @property int $offense_id
 * @property string $offense_date
 * @property string $offense_time
 * @property string $offense_location
 * @property string $pay_bill_date
 * @property string $last_bill_date
 * @property int $status
 * @property string $lat
 * @property string $lut
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Debt $debt
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, int $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel except(array $values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static \Illuminate\Database\Eloquent\Builder|Penalty newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Penalty newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Penalty query()
 * @method static \Illuminate\Database\Eloquent\Builder|Penalty whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Penalty whereDebtId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Penalty whereLastBillDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Penalty whereLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Penalty whereLut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Penalty whereOffenseDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Penalty whereOffenseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Penalty whereOffenseLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Penalty whereOffenseTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Penalty wherePayBillDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Penalty wherePenaltyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Penalty whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Penalty whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Penalty extends ServiceModel
{

    /**
     * @var string
     */
    protected $table = 'penalties';
    /**
     * @var string
     */
    protected $primaryKey = 'penalty_id';

    /**
     * @var string[]
     */
    protected $hidden = ['updated_at', 'created_at'];

    /**
     * @var string[]
     */
    protected $fillable = [
        'driver_id',
        'debt_id' ,
        'offense_id',
        'pay_bill_amount_with_discount',
        'offense_date',
        'offense_time',
        'offense_location',
        'pay_bill_date',
        'last_bill_date',
        'status'
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function debt()
    {
        return $this->belongsTo(Debt::class,'debt_id','debt_id');
    }
}
