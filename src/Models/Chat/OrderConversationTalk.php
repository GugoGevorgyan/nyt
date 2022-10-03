<?php

declare(strict_types=1);

namespace Src\Models\Chat;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;

/**
 * Class OrderConversationTalk
 *
 * @package Src\Models\Chat
 * @property int $order_conversation_talk_id
 * @property int|null $order_conversation_id
 * @property int|null $sender_id
 * @property string|null $sender_type
 * @property string|null $message
 * @property-read OrderConversation|null $conversation
 * @property-read Model|Eloquent $sender
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|OrderConversationTalk newModelQuery()
 * @method static Builder|OrderConversationTalk newQuery()
 * @method static Builder|OrderConversationTalk query()
 * @method static Builder|OrderConversationTalk whereMessage($value)
 * @method static Builder|OrderConversationTalk whereOrderConversationId($value)
 * @method static Builder|OrderConversationTalk whereOrderConversationTalkId($value)
 * @method static Builder|OrderConversationTalk whereSenderId($value)
 * @method static Builder|OrderConversationTalk whereSenderType($value)
 * @mixin Eloquent
 * @property Carbon $created_at
 * @method static Builder|OrderConversationTalk whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 */
class OrderConversationTalk extends ServiceModel
{
    /**
     * @var bool
     */
    public $timestamps = false;
    /**
     * @var string
     */
    protected $table = 'order_conversation_talks';
    /**
     * @var string
     */
    protected $primaryKey = 'order_conversation_talk_id';
    /**
     * @var string[]
     */
    protected $fillable = ['order_conversation_id', 'sender_id', 'sender_type', 'message'];
    /**
     * @var string[]
     */
    protected $dates = ['created_at'];

    /**
     * @return BelongsTo
     */
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(OrderConversation::class, 'order_conversation_id', 'order_conversation_id');
    }

    /**
     * @return MorphTo
     */
    public function sender(): MorphTo
    {
        return $this->morphTo();
    }
}
