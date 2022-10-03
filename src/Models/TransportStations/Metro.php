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
 * Class Metro
 *
 * @package Src\Models\TransportStations
 * @property int $metro_id
 * @property int|null $city_id
 * @property string $name
 * @property mixed|null $coordinate
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|Metro newModelQuery()
 * @method static Builder|Metro newQuery()
 * @method static Builder|Metro query()
 * @method static Builder|Metro whereCityId($value)
 * @method static Builder|Metro whereCoordinate($value)
 * @method static Builder|Metro whereCreatedAt($value)
 * @method static Builder|Metro whereMetroId($value)
 * @method static Builder|Metro whereName($value)
 * @method static Builder|Metro whereUpdatedAt($value)
 * @mixin Eloquent
 * @property-read City|null $city
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
 * @method static Builder|Metro whereAddress($value)
 * @method static Builder|Metro whereInput($value)
 * @method static Builder|Metro whereLat($value)
 * @method static Builder|Metro whereLut($value)
 * @method static Builder|ServiceModel cordDistance($latitude, $longitude)
 */
class Metro extends ServiceModel
{
    public $timestamps = false;

    /**
     * @var string
     */
    protected $table = 'metros';
    /**
     * @var string
     */
    protected $primaryKey = 'metro_id';
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
    protected string $map = 'metro';

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
