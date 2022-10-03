<?php

declare(strict_types=1);

namespace Src\Models\Order;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;
use Src\Models\Car\Car;
use Src\Models\Driver\Driver;

/**
 * Class CanceledOrder
 *
 * @package Src\Models\Order
 * @property int $canceled_order_id
 * @property int $order_id
 * @property int $cancelable_id
 * @property string $cancelable_type
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|OrderAssessment[] $assessments
 * @property-read int|null $assessments_count
 * @property-read Model|Eloquent $cancelable
 * @property-read Collection|OrderFeedback[] $feedbacks
 * @property-read int|null $feedbacks_count
 * @property-read Order $order
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|CanceledOrder newModelQuery()
 * @method static Builder|CanceledOrder newQuery()
 * @method static Builder|CanceledOrder query()
 * @method static Builder|CanceledOrder whereCancelableId($value)
 * @method static Builder|CanceledOrder whereCancelableType($value)
 * @method static Builder|CanceledOrder whereCanceledOrderId($value)
 * @method static Builder|CanceledOrder whereCreatedAt($value)
 * @method static Builder|CanceledOrder whereOrderId($value)
 * @method static Builder|CanceledOrder whereUpdatedAt($value)
 * @mixin Eloquent
 * @property int|null $feedback_id
 * @method static Builder|CanceledOrder whereFeedbackId($value)
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property int|null $driver_id
 * @property int|null $car_id
 * @property-read Car|null $car
 * @property-read Driver|null $driver
 * @method static Builder|CanceledOrder whereCarId($value)
 * @method static Builder|CanceledOrder whereDriverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 */
class CanceledOrder extends ServiceModel
{
    /**
     * @var string
     */
    protected $table = 'canceled_orders';

    /**
     * @var string
     */
    protected $primaryKey = 'canceled_order_id';

    /**
     * @var string[]
     */
    protected $fillable = ['order_id', 'driver_id', 'car_id', 'cancelable_id', 'cancelable_type'];
    /**
     * @var string
     */
    protected string $map = 'canceledOrder';

    /**
     * @return MorphTo
     */
    public function cancelable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    /**
     * @return BelongsTo
     */
    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }

    /**
     * @return BelongsTo
     */
    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class, 'car_id');
    }

    /**
     * @return MorphMany
     */
    public function feedbacks(): MorphMany
    {
        return $this->morphMany(OrderFeedback::class, 'orderable', 'orderable_type', 'orderable_id');
    }

    /**
     * @todo none used canceled orders is not a assessment
     * @return MorphMany
     */
    public function assessments(): MorphMany
    {
        return $this->morphMany(OrderFeedback::class, 'orderable')->where('assessment', '!=', 0);
    }
}
