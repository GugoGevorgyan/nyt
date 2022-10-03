<?php

declare(strict_types=1);

namespace Src\Models\Order;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;

/**
 * Class OrderStageCord
 *
 * @package Src\Models\Order
 * @property int $order_stage_cord_id
 * @property int $order_id
 * @property array $accept
 * @property Carbon|null $accepted
 * @property array $on_way
 * @property Carbon|null $on_wayed
 * @property array $in_place
 * @property Carbon|null $in_placed
 * @property array $start
 * @property Carbon|null $started
 * @property array $pauses
 * @property array $end
 * @property Carbon|null $ended
 * @property-read Order $order
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, int $distance = 1)
 * @method static Builder|ServiceModel except(array $values = [])
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|OrderStageCord newModelQuery()
 * @method static Builder|OrderStageCord newQuery()
 * @method static Builder|OrderStageCord query()
 * @method static Builder|OrderStageCord whereAccept($value)
 * @method static Builder|OrderStageCord whereAccepted($value)
 * @method static Builder|OrderStageCord whereEnd($value)
 * @method static Builder|OrderStageCord whereEnded($value)
 * @method static Builder|OrderStageCord whereInPlace($value)
 * @method static Builder|OrderStageCord whereInPlaced($value)
 * @method static Builder|OrderStageCord whereOnWay($value)
 * @method static Builder|OrderStageCord whereOnWayed($value)
 * @method static Builder|OrderStageCord whereOrderId($value)
 * @method static Builder|OrderStageCord whereOrderStageCordId($value)
 * @method static Builder|OrderStageCord wherePauses($value)
 * @method static Builder|OrderStageCord whereStart($value)
 * @method static Builder|OrderStageCord whereStarted($value)
 * @mixin Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 */
class OrderStageCord extends ServiceModel
{
    /**
     * @var bool
     */
    public $timestamps = false;
    /**
     * @var string
     */
    protected $table = 'order_stages_cord';
    /**
     * @var string
     */
    protected $primaryKey = 'order_stage_cord_id';
    /**
     * @var array
     */
    protected $fillable = [
        'order_id',
        'accept',
        'on_way',
        'in_place',
        'start',
        'end',
        'ended',
        'accepted',
        'on_wayed',
        'in_placed',
        'started',
        'pauses'
    ];
    /**
     * @var array
     */
    protected $casts = [
        'accept' => 'json',
        'on_way' => 'json',
        'in_place' => 'json',
        'start' => 'json',
        'pauses' => 'json',
        'end' => 'json',
    ];
    protected $dates = ['accepted', 'on_wayed', 'in_placed', 'started', 'ended'];
    /**
     * @var array
     */
    protected $attributes = [
        'accept' => '{}',
        'on_way' => '{}',
        'in_place' => '{}',
        'start' => '{}',
        'pauses' => '[]',
        'end' => '{}'
    ];

    /**
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
