<?php

declare(strict_types=1);

namespace Src\Models\Order;


use Illuminate\Database\Eloquent\Relations\BelongsTo;
use ServiceEntity\Models\ServiceModel;
use Znck\Eloquent\Relations\BelongsToThrough;

/**
 * Class OrderFeedbackComment
 *
 * @package Src\Models\Order
 * @property int $order_feedback_comment_id
 * @property int $feedback_id
 * @property int $commenting_id
 * @property int $new_status
 * @property int $old_status
 * @property string $comment
 * @property \Illuminate\Support\Carbon $created_at
 * @property-read \Src\Models\Order\OrderFeedback $feedback
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, int $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel except(array $values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderFeedbackComment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderFeedbackComment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderFeedbackComment query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderFeedbackComment whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderFeedbackComment whereCommentingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderFeedbackComment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderFeedbackComment whereFeedbackId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderFeedbackComment whereNewStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderFeedbackComment whereOldStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderFeedbackComment whereOrderFeedbackCommentId($value)
 * @mixin \Eloquent
 */
class OrderFeedbackComment extends ServiceModel
{


    /**
     * @var bool
     */
    public $timestamps = false;
    /**
     * @var string
     */
    protected $table = 'order_feedback_comments';
    /**
     * @var string
     */
    protected $primaryKey = 'order_feedback_comment_id';
    /**
     * @var string[]
     */
    protected $fillable = ['feedback_id', 'commenting_id', 'new_status', 'old_status', 'comment'];
    /**
     * @var array
     */
    protected $casts = [];
    /**
     * @var string[]
     */
    protected $dates = ['created_at'];

    /**
     * @return BelongsTo
     */
    public function feedback(): BelongsTo
    {
        return $this->belongsTo(OrderFeedback::class, 'feedback_id', 'order_feedback_id');
    }

    /**
     * @return BelongsToThrough
     */
    public function order(): BelongsToThrough
    {
        return $this->belongsToThrough(Order::class, OrderFeedback::class, null, '', [Order::class => 'order_id', OrderFeedback::class => 'feedback_id']);
    }
}
