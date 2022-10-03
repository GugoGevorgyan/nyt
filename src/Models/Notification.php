<?php

declare(strict_types=1);

namespace Src\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;

/**
 * Src\Models\Notification
 *
 * @property int $notification_id
 * @property string $group_number
 * @property int $annunciator_id
 * @property int $annunciator_type
 * @property int $notifier_id
 * @property int $notifier_type
 * @property string $title
 * @property string $body
 * @property array|null $payload
 * @property string $image
 * @property bool $viewed
 * @property Carbon $created_at
 * @property-read Model|Eloquent $annunciator
 * @property-read Model|Eloquent $notifier
 * @method static Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, int $distance = 1)
 * @method static Builder|ServiceModel except(array $values = [])
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|Notification newModelQuery()
 * @method static Builder|Notification newQuery()
 * @method static Builder|Notification query()
 * @method static Builder|Notification whereAnnunciatorId($value)
 * @method static Builder|Notification whereAnnunciatorType($value)
 * @method static Builder|Notification whereBody($value)
 * @method static Builder|Notification whereCreatedAt($value)
 * @method static Builder|Notification whereGroupNumber($value)
 * @method static Builder|Notification whereImage($value)
 * @method static Builder|Notification whereNotificationId($value)
 * @method static Builder|Notification whereNotifierId($value)
 * @method static Builder|Notification whereNotifierType($value)
 * @method static Builder|Notification wherePayload($value)
 * @method static Builder|Notification whereTitle($value)
 * @method static Builder|Notification whereViewed($value)
 * @mixin Eloquent
 */
class Notification extends ServiceModel
{
    /**
     * @var bool
     */
    public $timestamps = false;
    /**
     * @var string
     */
    protected $table = 'notifications';
    /**
     * @var string
     */
    protected $primaryKey = 'notification_id';
    /**
     * @var string[]
     */
    protected $fillable = [
        'notification_id',
        'group_number',
        'annunciator_id',
        'annunciator_type',
        'notifier_id',
        'notifier_type',
        'viewed',
        'title',
        'body',
        'payload',
        'image'
    ];
    /**
     * @var string[]
     */
    protected $casts = ['viewed' => 'bool', 'payload' => 'array'];
    /**
     * @var string[]
     */
    protected $dates = ['created_at'];

    /**
     * @return MorphTo
     */
    public function annunciator(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return MorphTo
     */
    public function notifier(): MorphTo
    {
        return $this->morphTo();
    }
}
