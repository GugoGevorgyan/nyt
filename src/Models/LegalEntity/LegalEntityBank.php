<?php

namespace Src\Models\LegalEntity;

use Eloquent;
use Grimzy\LaravelMysqlSpatial\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;
use Src\Models\Location\City;
use Src\Models\Location\Country;
use Src\Models\Location\Region;

/**
 * Src\Models\LegalEntity\LegalEntityBank
 *
 * @property int $entity_bank_id
 * @property int $entity_id
 * @property int $country_id
 * @property int $region_id
 * @property int $city_id
 * @property string $name
 * @property string $bank_account_number Р/сч
 * @property string $correspondent_account_number К/сч
 * @property string $bank_identification_account БИК
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntityBank newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntityBank newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntityBank query()
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntityBank whereBankAccountNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntityBank whereBankIdentificationAccount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntityBank whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntityBank whereCorrespondentAccountNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntityBank whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntityBank whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntityBank whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntityBank whereEntityBankId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntityBank whereEntityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntityBank whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntityBank whereRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntityBank whereUpdatedAt($value)
 * @mixin Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel except($values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property-read City $city
 * @property-read Country $country
 * @property-read LegalEntity $entity
 * @property-read Region $region
 * @method static \Illuminate\Database\Query\Builder|LegalEntityBank onlyTrashed()
 * @method static \Illuminate\Database\Query\Builder|LegalEntityBank withTrashed()
 * @method static \Illuminate\Database\Query\Builder|LegalEntityBank withoutTrashed()
 * @method static Builder|ServiceModel comparison($geometryColumn, $geometry, $relationship)
 * @method static Builder|ServiceModel contains($geometryColumn, $geometry)
 * @method static Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static Builder|ServiceModel crosses($geometryColumn, $geometry)
 * @method static Builder|ServiceModel disjoint($geometryColumn, $geometry)
 * @method static Builder|ServiceModel distanceExcludingSelf($geometryColumn, $geometry, $distance)
 * @method static Builder|ServiceModel distanceSphere($geometryColumn, $geometry, $distance)
 * @method static Builder|ServiceModel distanceSphereExcludingSelf($geometryColumn, $geometry, $distance)
 * @method static Builder|ServiceModel distanceSphereValue($geometryColumn, $geometry)
 * @method static Builder|ServiceModel distanceValue($geometryColumn, $geometry)
 * @method static Builder|ServiceModel doesTouch($geometryColumn, $geometry)
 * @method static Builder|ServiceModel equals($geometryColumn, $geometry)
 * @method static Builder|ServiceModel intersects($geometryColumn, $geometry)
 * @method static Builder|ServiceModel orderByDistance($geometryColumn, $geometry, $direction = 'asc')
 * @method static Builder|ServiceModel orderByDistanceSphere($geometryColumn, $geometry, $direction = 'asc')
 * @method static Builder|ServiceModel orderBySpatial($geometryColumn, $geometry, $orderFunction, $direction = 'asc')
 * @method static Builder|ServiceModel overlaps($geometryColumn, $geometry)
 * @method static Builder|ServiceModel within($geometryColumn, $polygon)
 */
class LegalEntityBank extends ServiceModel
{

    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'legal_entity_banks';
    /**
     * @var string
     */
    protected $primaryKey = 'entity_bank_id';
    /**
     * @var string[]
     */
    protected $fillable = [
        'entity_id',
        'country_id',
        'region_id',
        'city_id',
        'name',
        'bank_account_number',
        'correspondent_account_number',
        'bank_identification_account'
    ];

    /**
     * @return BelongsTo
     */
    public function entity(): BelongsTo
    {
        return $this->belongsTo(LegalEntity::class, 'entity_id', 'legal_entity_id');
    }

    /**
     * @return BelongsTo
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id', 'country_id');
    }

    /**
     * @return BelongsTo
     */
    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'region_id', 'region_id');
    }

    /**
     * @return BelongsTo
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id', 'city_id');
    }

}
