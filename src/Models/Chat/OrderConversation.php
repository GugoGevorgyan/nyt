<?php

declare(strict_types=1);

namespace Src\Models\Chat;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;
use Src\Models\Corporate\AdminCorporate;
use Src\Models\Driver\Driver;
use Src\Models\Order\Order;

/**
 * Class OrderConversation
 *
 * @package Src\Models\Chat
 * @property int $order_conversation_id
 * @property int $order_id
 * @property int $driver_id
 * @property int $client_id
 * @property string $client_type
 * @property Carbon $created_at
 * @property-read Model|Eloquent $client
 * @property-read Driver $driver
 * @property-read Collection|OrderConversationTalk[] $messages
 * @property-read int|null $messages_count
 * @property-read Order $order
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|OrderConversation newModelQuery()
 * @method static Builder|OrderConversation newQuery()
 * @method static Builder|OrderConversation query()
 * @method static Builder|OrderConversation whereClientId($value)
 * @method static Builder|OrderConversation whereClientType($value)
 * @method static Builder|OrderConversation whereCreatedAt($value)
 * @method static Builder|OrderConversation whereDriverId($value)
 * @method static Builder|OrderConversation whereOrderConversationId($value)
 * @method static Builder|OrderConversation whereOrderId($value)
 * @mixin Eloquent
 * @property int $sender_id
 * @property string $sender_type
 * @method static Builder|OrderConversation whereSenderId($value)
 * @method static Builder|OrderConversation whereSenderType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 */
class OrderConversation extends ServiceModel
{
    /**
     * @var bool
     */
    public $timestamps = false;
    /**
     * @var string
     */
    protected $table = 'order_conversations';
    /**
     * @var string
     */
    protected $primaryKey = 'order_conversation_id';
    /**
     * @var string[]
     */
    protected $fillable = ['order_id', 'driver_id', 'client_id', 'client_type', 'sender_id', 'sender_type'];
    /**
     * @var string[]
     */
    protected $dates = ['created_at'];

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
    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class, 'driver_id', 'driver_id');
    }

    /**
     * @return MorphTo
     *
     * @see Client
     * @see AdminCorporate
     */
    public function client(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return HasMany
     */
    public function messages(): HasMany
    {
        return $this->hasMany(OrderConversationTalk::class, $this->primaryKey, $this->primaryKey);
    }
}
