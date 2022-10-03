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
 * Class Airport
 *
 * @package Src\Models\TransportStations
 * @property int $airport_id
 * @property int $city_id
 * @property string $name
 * @property mixed|null $cordinate
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read City $city
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|Airport newModelQuery()
 * @method static Builder|Airport newQuery()
 * @method static Builder|Airport query()
 * @method static Builder|Airport whereAirportId($value)
 * @method static Builder|Airport whereCityId($value)
 * @method static Builder|Airport whereCordinate($value)
 * @method static Builder|Airport whereCreatedAt($value)
 * @method static Builder|Airport whereName($value)
 * @method static Builder|Airport whereUpdatedAt($value)
 * @mixin Eloquent
 * @property array $coordinate
 * @method static Builder|Airport whereCoordinate($value)
 * @property-read Collection|OrderMeet[] $meets
 * @property-read int|null $meets_count
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property string|null $terminal
 * @property string|null $address
 * @property mixed|null $lat
 * @property mixed|null $lut
 * @method static Builder|Airport whereAddress($value)
 * @method static Builder|Airport whereLat($value)
 * @method static Builder|Airport whereLut($value)
 * @method static Builder|Airport whereTerminal($value)
 * @method static Builder|ServiceModel cordDistance($latitude, $longitude)
 */
class Airport extends ServiceModel
{
    protected const LATITUDE = 'lat';
    protected const LONGITUDE = 'lut';
    protected static bool $kilometers = true;

    public $timestamps = false;
    /**
     * @var string
     */
    protected $table = 'airports';
    /**
     * @var string
     */
    protected $primaryKey = 'airport_id';
    /**
     * @var string[]
     */
    protected $fillable = ['city_id', 'name', 'terminal', 'address', 'lat', 'lut'];
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
    protected string $map = 'airport';

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
