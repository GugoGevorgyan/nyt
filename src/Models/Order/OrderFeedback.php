<?php

declare(strict_types=1);


namespace Src\Models\Order;


use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;

/**
 * Class OrderFeedback
 *
 * @package Src\Models\Order
 * @property int|null $order_feedback_id
 * @property int|null $feedback_option_id
 * @property int|null $orderable_id
 * @property int|null $writable_id
 * @property string|null $orderable_type
 * @property int|null $writable_type
 * @property string|null $text
 * @property-read OrderFeedbackOption $option
 * @property-read Model|Eloquent $orderable
 * @property-read Model|Eloquent $writable
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|OrderFeedback newModelQuery()
 * @method static Builder|OrderFeedback newQuery()
 * @method static Builder|OrderFeedback query()
 * @method static Builder|OrderFeedback whereFeedbackOptionId($value)
 * @method static Builder|OrderFeedback whereOrderFeedbackId($value)
 * @method static Builder|OrderFeedback whereOrderableId($value)
 * @method static Builder|OrderFeedback whereOrderableType($value)
 * @method static Builder|OrderFeedback whereText($value)
 * @method static Builder|OrderFeedback whereWritableId($value)
 * @method static Builder|OrderFeedback whereWritableType($value)
 * @mixin Eloquent
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|OrderFeedback whereCreatedAt($value)
 * @method static Builder|OrderFeedback whereUpdatedAt($value)
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property int $order_id
 * @property int $assessment
 * @property-read Order $order
 * @method static Builder|OrderFeedback whereAssessment($value)
 * @method static Builder|OrderFeedback whereOrderId($value)
 * @property int|null $readable_id
 * @property string|null $readable_type
 * @property-read Model|Eloquent $readable
 * @method static Builder|OrderFeedback whereReadableId($value)
 * @method static Builder|OrderFeedback whereReadableType($value)
 * @property-read Collection|OrderFeedbackComment[] $comments
 * @property-read int|null $comments_count
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 */
class OrderFeedback extends ServiceModel
{
    /**
     * @var string
     */
    protected $table = 'order_feedbacks';
    /**
     * @var string
     */
    protected $primaryKey = 'order_feedback_id';
    /**
     * @var array
     */
    protected $fillable = [
        'feedback_option_id',
        'order_id',
        'orderable_id',
        'orderable_type',
        'writable_id',
        'writable_type',
        'readable_id',
        'readable_type',
        'assessment',
        'text',
    ];

    /**
     * Completed or Canceled Orders
     *
     * @return MorphTo
     * @Link CompletedOrder
     * @Link CanceledOrder
     */
    public function orderable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    /**
     * @return MorphTo
     */
    public function writable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return MorphTo
     */
    public function readable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return BelongsTo
     */
    public function option(): BelongsTo
    {
        return $this->belongsTo(OrderFeedbackOption::class, 'feedback_option_id', 'order_feedback_option_id');
    }

    /**
     * @return HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(OrderFeedbackComment::class, 'feedback_id', 'order_feedback_id');
    }
}
