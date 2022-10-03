<?php

namespace Src\Models\Chat;


use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;

/**
 * Src\Models\Chat\Message
 *
 * @property int $message_id
 * @property int $room_id
 * @property int $sender_id
 * @property string $text
 * @property mixed $un_seen
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Message newModelQuery()
 * @method static Builder|Message newQuery()
 * @method static Builder|Message query()
 * @method static Builder|Message whereCreatedAt($value)
 * @method static Builder|Message whereMessageId($value)
 * @method static Builder|Message whereRoomId($value)
 * @method static Builder|Message whereSenderId($value)
 * @method static Builder|Message whereText($value)
 * @method static Builder|Message whereUnSeen($value)
 * @method static Builder|Message whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Message extends ServiceModel
{

}
