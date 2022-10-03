<?php

declare(strict_types=1);

namespace Src\Models\TransportStations;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;
use Src\Models\Location\City;
use Src\Models\Location\Country;
use Src\Models\Location\Region;
use Src\Models\Order\OrderMeet;
use Znck\Eloquent\Relations\BelongsToThrough;

/**
 * Class RailwayStation
 *
 * @package Src\Models\TransportStations
 * @property int $railway_station_id
 * @property int $city_id
 * @property string $name
 * @property mixed|null $coordinate
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read City $city
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|RailwayStation newModelQuery()
 * @method static Builder|RailwayStation newQuery()
 * @method static Builder|RailwayStation query()
 * @method static Builder|RailwayStation whereCityId($value)
 * @method static Builder|RailwayStation whereCoordinate($value)
 * @method static Builder|RailwayStation whereCreatedAt($value)
 * @method static Builder|RailwayStation whereName($value)
 * @method static Builder|RailwayStation whereRailwayStationId($value)
 * @method static Builder|RailwayStation whereUpdatedAt($value)
 * @mixin Eloquent
 * @property-read Collection|OrderMeet[] $meets
 * @property-read int|null $meets_count
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property string|null $input
 * @property string|null $address
 * @property mixed|null $lat
 * @property mixed|null $lut
 * @method static Builder|RailwayStation whereAddress($value)
 * @method static Builder|RailwayStation whereInput($value)
 * @method static Builder|RailwayStation whereLat($value)
 * @method static Builder|RailwayStation whereLut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 */
class RailwayStation extends ServiceModel
{
    protected const LATITUDE = 'lat';
    protected const LONGITUDE = 'lut';
    protected static bool $kilometers = true;

    public $timestamps = false;
    /**
     * @var string
     */
    protected $table = 'railway_stations';
    /**
     * @var string
     */
    protected $primaryKey = 'railway_station_id';
    /**
     * @var string[]
     */
    protected $fillable = ['city_id', 'name', 'input', 'address', 'lat', 'lut'];
    /**
     * @var string[]
     */
    protected $casts = ['lat' => 'decimal:10.8', 'lut' => 'decimal:11.8'];
    /**
     * @var string[]
     */
    protected $dates = ['created_at'];
    /**
     * @var string
     */
    protected string $map = 'railwayStation';

    /**
     * @return BelongsTo
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id', 'city_id');
    }

    /**
     * @return BelongsToThrough
     */
    public function region(): BelongsToThrough
    {
        return $this->belongsToThrough(Region::class, City::class, null, '', [Region::class => 'region_id', City::class => 'region_id']);
    }

    /**
     * @return BelongsToThrough
     */
    public function country(): BelongsToThrough
    {
        return $this->belongsToThrough(Country::class, City::class, null, '', [Country::class => 'country_id', City::class => 'country_id']);
    }

    /**
     * @return MorphMany
     */
    public function meets(): MorphMany
    {
        return $this->morphMany(OrderMeet::class, 'place', 'places_type', 'places_id');
    }
}
