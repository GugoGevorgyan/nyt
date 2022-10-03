<?php

declare(strict_types=1);

namespace Src\Models\Order;


use Illuminate\Database\Eloquent\Relations\BelongsTo;
use ServiceEntity\Models\ServiceModel;
use Src\Models\Driver\ExternalBoard;

/**
 * Src\Models\Order\ExternalOrder
 *
 * @property int $external_order_id
 * @property int $order_id
 * @property int $board_id
 * @property string $order_key
 * @property int $draft
 * @property mixed $payload
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read ExternalBoard $board
 * @property-read \Src\Models\Order\Order $order
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, int $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel except(array $values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalOrder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalOrder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalOrder query()
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalOrder whereBoardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalOrder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalOrder whereDraft($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalOrder whereExternalOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalOrder whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalOrder whereOrderKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalOrder wherePayload($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalOrder whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ExternalOrder extends ServiceModel
{


    /**
     * @var string
     */
    protected $table = 'external_orders';
    /**
     * @var string
     */
    protected $primaryKey = 'external_order_id';
    /**
     * @var string[]
     */
    protected $fillable = ['order_id', 'board_id', 'order_key', 'draft'];
    /**
     * @var array
     */
    protected $casts = [];

    /**
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    /**
     * @return BelongsTo
     */
    public function board(): BelongsTo
    {
        return $this->belongsTo(ExternalBoard::class, 'board_id', 'external_board_id');
    }
}
