<?php

declare(strict_types=1);

namespace Src\Models\Debt;


use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;
use Src\Models\Driver\Driver;
use Src\Models\Penalty\Penalty;

/**
 * Class Debt
 *
 * @package Src\Models\Debt
 * @property int $debt_id
 * @property int $debtor_id
 * @property string $debtor_type
 * @property int $type
 * @property string $cost
 * @property string $cost_paid
 * @property bool $closest
 * @property Carbon $created_at
 * @property-read Driver $driver
 * @method static Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, int $distance = 1)
 * @method static Builder|ServiceModel except(array $values = [])
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|Debt newModelQuery()
 * @method static Builder|Debt newQuery()
 * @method static Builder|Debt query()
 * @method static Builder|Debt whereClosest($value)
 * @method static Builder|Debt whereCost($value)
 * @method static Builder|Debt whereCostPaid($value)
 * @method static Builder|Debt whereCreatedAt($value)
 * @method static Builder|Debt whereDebtId($value)
 * @method static Builder|Debt whereDebtorId($value)
 * @method static Builder|Debt whereDebtorType($value)
 * @method static Builder|Debt whereType($value)
 * @mixin Eloquent
 * @property int $firm_paid
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $current_debt
 * @property-read Driver $debt_type
 * @property-read Penalty|null $penalty
 * @method static Builder|Debt whereFirmPaid($value)
 */
class Debt extends ServiceModel
{
    /**
     * @var bool
     */
    public $timestamps = false;
    /**
     * @var string
     */
    protected $table = 'debts';
    /**
     * @var string
     */
    protected $primaryKey = 'debt_id';
    /**
     * @var string[]
     */
    protected $fillable = ['debtor_id', 'debtor_type', 'cost', 'type', 'cost_paid', 'closest', 'firm_paid'];
    /**
     * @var string[]
     */
    protected $dates = ['created_at'];
    /**
     * @var string[]
     */
    protected $casts = ['payed' => 'boolean', 'cost_payed' => 'float', 'closest' => 'boolean'];

    /**
     * @return BelongsTo
     */
    public function debt_type(): BelongsTo
    {
        return $this->belongsTo(Driver::class, 'type', 'debt_type_id');
    }

    /**
     * @return MorphTo
     */
    public function current_debt(): MorphTo
    {
        return $this->morphTo('current_debt', 'debtor_type', 'debtor_id');
    }

    /**
     * @return HasOne
     */
    public function penalty(): HasOne
    {
        return $this->hasOne(Penalty::class, 'debt_id', 'debt_id');
    }
}
