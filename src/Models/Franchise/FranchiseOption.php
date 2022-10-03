<?php

declare(strict_types=1);

namespace Src\Models\Franchise;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo as BelongsToAlias;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;

/**
 * Class FranchiseOption
 *
 * @package Src\Models\Franchise
 * @property int $franchise_option_id
 * @property int $franchise_id
 * @property int $order_pending_time SECONDS
 * @property array|null $default_assessment
 * @property array|null $default_rating
 * @property string $order_cancel_before Разрешить отменять заказ до
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Franchise $franchise
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, int $distance = 1)
 * @method static Builder|ServiceModel except(array $values = [])
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|FranchiseOption newModelQuery()
 * @method static Builder|FranchiseOption newQuery()
 * @method static Builder|FranchiseOption query()
 * @method static Builder|FranchiseOption whereCreatedAt($value)
 * @method static Builder|FranchiseOption whereDefaultAssessment($value)
 * @method static Builder|FranchiseOption whereDefaultRating($value)
 * @method static Builder|FranchiseOption whereFranchiseId($value)
 * @method static Builder|FranchiseOption whereFranchiseOptionId($value)
 * @method static Builder|FranchiseOption whereOrderCancelBefore($value)
 * @method static Builder|FranchiseOption whereOrderPendingTime($value)
 * @method static Builder|FranchiseOption whereUpdatedAt($value)
 * @mixin Eloquent
 * @property array|null $waybill_max_days
 * @method static Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static Builder|FranchiseOption whereWaybillMaxDays($value)
 * @property int $dispatching_minute
 * @method static Builder|FranchiseOption whereDispatchingMinute($value)
 */
class FranchiseOption extends ServiceModel
{
    /**
     * @var string
     */
    protected $table = 'franchise_options';
    /**
     * @var string
     */
    protected $primaryKey = 'franchise_option_id';
    /**
     * @var string[]
     */
    protected $fillable = [
        'franchise_id',
        'order_pending_time',
        'order_cancel_before',
        'default_rating',
        'default_assessment',
        'waybill_max_days',
        'dispatching_minute'
    ];
    /**
     * @var array[]
     */
    protected $attributes = [
        'default_rating' => '{}',
        'default_assessment' => '{}',
        'waybill_max_days' => '{}'
    ];
    /**
     * @var string[]
     */
    protected $casts = [
        'default_rating' => 'array',
        'default_assessment' => 'array',
        'waybill_max_days' => 'array'
    ];

    /**
     * @return BelongsToAlias
     */
    public function franchise(): BelongsToAlias
    {
        return $this->belongsTo(Franchise::class, 'franchise_id');
    }
}
