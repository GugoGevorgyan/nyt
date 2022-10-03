<?php

namespace Src\Models\Chat;


use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;

/**
 * Src\Models\Chat\RoomType
 *
 * @property int $room_type_id
 * @property int $type
 * @property string $value
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|RoomType newModelQuery()
 * @method static Builder|RoomType newQuery()
 * @method static Builder|RoomType query()
 * @method static Builder|RoomType whereCreatedAt($value)
 * @method static Builder|RoomType whereRoomTypeId($value)
 * @method static Builder|RoomType whereType($value)
 * @method static Builder|RoomType whereUpdatedAt($value)
 * @method static Builder|RoomType whereValue($value)
 * @mixin Eloquent
 */
class RoomType extends ServiceModel
{

}
