<?php

declare(strict_types=1);

namespace Src\Models\Client;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;
use JsonException;
use ServiceEntity\Models\ServiceModel;
use Src\Models\Driver\Driver;

/**
 * Class ClientTaxiView
 *
 * @package Src\Models\ClientMessage
 * @property int $client_driver_view_id
 * @property int|null $client_id
 * @property Driver $driver
 * @property int|null $status
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Client|null $client
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|ClientTaxiView newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ClientTaxiView newQuery()
 * @method static Builder|ClientTaxiView onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ClientTaxiView query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|ClientTaxiView whereClientCoordinates($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientTaxiView whereClientDriverViewId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientTaxiView whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientTaxiView whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientTaxiView whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientTaxiView whereDriver($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientTaxiView whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientTaxiView whereUpdatedAt($value)
 * @method static Builder|ClientTaxiView withTrashed()
 * @method static Builder|ClientTaxiView withoutTrashed()
 * @mixin Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @property int|null $road_taxi_view_id
 * @property int|null $clientable_id
 * @property string $clientable_type
 * @property-read Model|Eloquent $before_client
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel except($values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ClientTaxiView whereClientCoordinateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientTaxiView whereClientableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientTaxiView whereClientableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientTaxiView whereRoadTaxiViewId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @property-read Driver $drivers
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 */
class ClientTaxiView extends ServiceModel
{
    /**
     * @var string
     */
    protected $table = 'client_drivers_view';
    /**
     * @var string
     */
    protected $primaryKey = 'client_driver_view_id';
    /**
     * @var array
     */
    protected $fillable = ['driver', 'clientable_id', 'clientable_type', 'road_taxi_view_id'];
    /**
     * @var array
     */
    protected $casts = ['driver' => 'json'];


    /*=============================================================================================
                                            RELATIONS
    =============================================================================================*/
    /**
     * @return MorphTo
     */
    public function client(): MorphTo
    {
        return $this->morphTo('client', 'clientable_type', 'clientable_id');
    }

    /**
     * @return BelongsTo
     */
    public function drivers(): BelongsTo
    {
        return $this->belongsToJson(Driver::class, 'driver->ids');
    }

    /*=============================================================================================
                                            ACCESSORS MUTATORS
    =============================================================================================*/
    /**
     * @param $value
     * @return mixed
     * @throws JsonException
     */
    public function getDriverAttribute($value): mixed
    {
        return json_decode($value, true, 512, JSON_THROW_ON_ERROR);
    }
}
