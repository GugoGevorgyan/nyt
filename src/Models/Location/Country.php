<?php

declare(strict_types=1);

namespace Src\Models\Location;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use ServiceEntity\Models\ServiceModel;
use Src\Models\Franchise\Franchise;
use Src\Models\Franchise\FranchiseRegion;

/**
 * Class Country
 *
 * @package Src\Models
 * @property int $country_id
 * @property string $name
 * @property string $iso
 * @property string|null $iso3
 * @property int|null $phonecode
 * @property-read Collection|City[] $cities
 * @property-read Collection|Region[] $regions
 * @method static Builder|Country newModelQuery()
 * @method static Builder|Country newQuery()
 * @method static Builder|Country query()
 * @method static Builder|Country whereCountryId($value)
 * @method static Builder|Country whereIso($value)
 * @method static Builder|Country whereIso3($value)
 * @method static Builder|Country whereName($value)
 * @method static Builder|Country wherePhonecode($value)
 * @mixin Eloquent
 * @property string $iso_2
 * @property string|null $phone_code
 * @property string|null $currency
 * @property string|null $deleted_at
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property-read int|null $cities_count
 * @property-read int|null $regions_count
 * @method static Builder|Country search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|Country searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|Country whereCreatedAt($value)
 * @method static Builder|Country whereCurrency($value)
 * @method static Builder|Country whereDeletedAt($value)
 * @method static Builder|Country whereIso2($value)
 * @method static Builder|Country whereUpdatedAt($value)
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @property-read Collection|Franchise[] $franchisee
 * @property-read int|null $franchisee_count
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static \Illuminate\Database\Query\Builder|Country onlyTrashed()
 * @method static \Illuminate\Database\Query\Builder|Country withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Country withoutTrashed()
 * @property string|null $phone_mask
 * @method static Builder|Country wherePhoneMask($value)
 * @property-read Collection|FranchiseRegion[] $franchisee_region
 * @property-read int|null $franchisee_region_count
 * @method static Builder|ServiceModel cordDistance($latitude, $longitude)
 * @property-read Collection|\Src\Models\Location\Timezone[] $timezones
 * @property-read int|null $timezones_count
 * @method static Builder|Country wherePhoneCode($value)
 */
class Country extends ServiceModel
{
    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string
     */
    protected $table = 'countries';
    /**
     * @var string
     */
    protected $primaryKey = 'country_id';
    /**
     * @var array
     */
    protected $guarded = [];
    /**
     * @var array
     */
    protected $fillable = ['name', 'iso_2', 'phone_code', 'currency', 'phone_mask'];

    /**
     * @return HasManyThrough
     */
    public function franchisee_region(): HasManyThrough
    {
        return $this->hasManyThrough(FranchiseRegion::class, Region::class, 'country_id', 'region_id');
    }

    /**
     * @return HasMany
     */
    public function regions(): HasMany
    {
        return $this->hasMany(Region::class, 'country_id', 'country_id');
    }

    /**
     * @return HasMany
     */
    public function cities(): HasMany
    {
        return $this->hasMany(City::class, 'country_id', 'country_id');
    }

    /**
     * @return HasMany
     */
    public function franchisee(): HasMany
    {
        return $this->hasMany(Franchise::class, $this->primaryKey);
    }

    /**
     * @return HasMany
     */
    public function timezones(): HasMany
    {
        return $this->hasMany(Timezone::class, 'country_id', 'country_id');
    }
}
