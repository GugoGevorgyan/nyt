<?php

declare(strict_types=1);

namespace Src\Models\Order;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;
use Src\Models\TransportStations\Airport;
use Src\Models\TransportStations\Metro;
use Src\Models\TransportStations\RailwayStation;
use Src\Scopes\OrderMeetScope;

/**
 * Class OrderMeet
 *
 * @package Src\Models\Order
 * @property int $order_meet_id
 * @property int $order_id
 * @property string|null $vagon_number
 * @property string|null $flight_number
 * @property string|null $from
 * @property string|null $text
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|OrderMeet newModelQuery()
 * @method static Builder|OrderMeet newQuery()
 * @method static Builder|OrderMeet query()
 * @method static Builder|OrderMeet whereCreatedAt($value)
 * @method static Builder|OrderMeet whereFlightNumber($value)
 * @method static Builder|OrderMeet whereFrom($value)
 * @method static Builder|OrderMeet whereOrderId($value)
 * @method static Builder|OrderMeet whereOrderMeetId($value)
 * @method static Builder|OrderMeet whereText($value)
 * @method static Builder|OrderMeet whereUpdatedAt($value)
 * @method static Builder|OrderMeet whereVagonNumber($value)
 * @mixin Eloquent
 * @property int $airport_id
 * @property int $station_id
 * @property string|null $wagon_number
 * @method static Builder|OrderMeet whereAirportId($value)
 * @method static Builder|OrderMeet whereStationId($value)
 * @method static Builder|OrderMeet whereWagonNumber($value)
 * @property int $metro_id
 * @method static Builder|OrderMeet whereMetroId($value)
 * @property int $place_id
 * @property string $place_type
 * @property string|null $info
 * @property Carbon|null $deleted_at
 * @property-read Order $order
 * @property-read Model|Eloquent $place
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static \Illuminate\Database\Query\Builder|OrderMeet onlyTrashed()
 * @method static Builder|OrderMeet whereDeletedAt($value)
 * @method static Builder|OrderMeet whereInfo($value)
 * @method static Builder|OrderMeet wherePlaceId($value)
 * @method static Builder|OrderMeet wherePlaceType($value)
 * @method static \Illuminate\Database\Query\Builder|OrderMeet withTrashed()
 * @method static \Illuminate\Database\Query\Builder|OrderMeet withoutTrashed()
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 */
class OrderMeet extends ServiceModel
{
    /**
     * @var string
     */
    protected $table = 'order_meets';
    /**
     * @var string
     */
    protected $primaryKey = 'order_meet_id';
    /**
     * @var array
     */
    protected $fillable = [
        'order_id',
        'place_id',
        'place_type',
        'info',
        'text'
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new OrderMeetScope());
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
     * @link Airport
     * @link Metro
     * @link RailwayStation
     */
    public function place(): MorphTo
    {
        return $this->morphTo('place', 'place_type', 'place_id');
    }
}
