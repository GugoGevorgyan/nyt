<?php

namespace Src\Models\Chat;


use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;

/**
 * Src\Models\Chat\Room
 *
 * @property int $room_id
 * @property int $creator_id
 * @property int $type_id
 * @property string|null $title
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Room newModelQuery()
 * @method static Builder|Room newQuery()
 * @method static Builder|Room query()
 * @method static Builder|Room whereCreatedAt($value)
 * @method static Builder|Room whereCreatorId($value)
 * @method static Builder|Room whereRoomId($value)
 * @method static Builder|Room whereTitle($value)
 * @method static Builder|Room whereTypeId($value)
 * @method static Builder|Room whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Room extends ServiceModel
{

}
