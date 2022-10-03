<?php

declare(strict_types=1);

namespace Src\Models\Location;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use ServiceEntity\Models\ServiceModel;

/**
 * Src\Models\Location\Timezone
 *
 * @property int $timezone_id
 * @property int|null $country_id
 * @property string $zone_string
 * @property string $zone_gmt
 * @property-read \Src\Models\Location\Country|null $country
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, int $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel except(array $values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static \Illuminate\Database\Eloquent\Builder|Timezone newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Timezone newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Timezone query()
 * @method static \Illuminate\Database\Eloquent\Builder|Timezone whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Timezone whereTimezoneId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Timezone whereZoneGmt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Timezone whereZoneString($value)
 * @mixin \Eloquent
 */
class Timezone extends ServiceModel
{
    /**
     * @var bool
     */
    public $timestamps = false;
    /**
     * @var string
     */
    protected $primaryKey = 'timezone_id';
    /**
     * @var string
     */
    protected $table = 'timezones';
    /**
     * @var string[]
     */
    protected $fillable = ['country_id', 'zone_string', 'zone_gmt'];
    /**
     * @var string[]
     */
    protected $casts = [''];

    /**
     * @return BelongsTo
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id', 'country_id');
    }
}
