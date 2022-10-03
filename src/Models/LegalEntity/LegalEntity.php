<?php

declare(strict_types=1);

namespace Src\Models\LegalEntity;

use Eloquent;
use Grimzy\LaravelMysqlSpatial\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;
use Src\Models\Franchise\Franchise;
use Src\Models\Location\City;
use Src\Models\Location\Country;
use Src\Models\Location\Region;

/**
 * Src\Models\LegalEntity\LegalEntity
 *
 * @property int $entity_id
 * @property string|null $name
 * @property int $type_id
 * @property int $country_id
 * @property int $region_id
 * @property int $city_id
 * @property string $zip_code
 * @property string $address
 * @property string $phone
 * @property string|null $fax
 * @property string $email
 * @property string $tax_inn
 * @property string $tax_kpp
 * @property string $tax_psrn ОГРН
 * @property string $tax_psrn_serial серия ОГРН
 * @property string $tax_psrn_issued_by кем выдан ОГРН
 * @property string $tax_psrn_date дата выдачи ОГРН
 * @property string|null $aucneb ОКОНХ
 * @property string|null $arceo ОКПО
 * @property string|null $arcfo ОКФС
 * @property string|null $arclf ОКОПФ
 * @property string|null $registration_certificate_number Номер свидетельства о регистрации
 * @property string|null $registration_certificate_date Дата выдачи свидетельства о регистрации
 * @property string $director_name
 * @property string $director_surname
 * @property string $director_patronymic
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read City $city
 * @property-read LegalEntityType $type
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntity newQuery()
 * @method static \Illuminate\Database\Query\Builder|LegalEntity onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntity query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntity whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntity whereArceo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntity whereArcfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntity whereArclf($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntity whereAucneb($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntity whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntity whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntity whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntity whereDirectorName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntity whereDirectorPatronymic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntity whereDirectorSurname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntity whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntity whereEntityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntity whereFax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntity whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntity wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntity whereRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntity whereRegistrationCertificateDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntity whereRegistrationCertificateNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntity whereTaxInn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntity whereTaxKpp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntity whereTaxPsrn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntity whereTaxPsrnDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntity whereTaxPsrnIssuedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntity whereTaxPsrnSerial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntity whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntity whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntity whereZipCode($value)
 * @method static \Illuminate\Database\Query\Builder|LegalEntity withTrashed()
 * @method static \Illuminate\Database\Query\Builder|LegalEntity withoutTrashed()
 * @mixin Eloquent
 * @property int|null $franchise_id
 * @property-read Country $country
 * @property-read Region $region
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel except($values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntity whereFranchiseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property int $legal_entity_id
 * @property-read Collection|Franchise[] $franchises
 * @property-read int|null $franchises_count
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntity whereLegalEntityId($value)
 * @property-read Collection|LegalEntityBank[] $banks
 * @property-read int|null $banks_count
 * @property-read Franchise|null $franchise
 * @property-read string $full_title
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 */
class LegalEntity extends ServiceModel
{
    /**
     * @var string
     */
    protected $table = 'legal_entities';
    /**
     * @var string
     */
    protected $primaryKey = 'legal_entity_id';
    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'type_id',
        'country_id',
        'region_id',
        'city_id',
        'zip_code',
        'address',
        'phone',
        'fax',
        'email',
        'tax_inn',
        'tax_kpp',
        'tax_psrn',
        'tax_psrn_issued_by',
        'tax_psrn_serial',
        'tax_psrn_issued_by',
        'tax_psrn_date',
        'aucneb',
        'arceo',
        'arcfo',
        'arclf',
        'registration_certificate_number',
        'registration_certificate_date',
        'director_name',
        'director_surname',
        'director_patronymic'
    ];

    /**
     * Get the entity's full title.
     *
     * @return string
     */
    public function getFullTitleAttribute(): string
    {
        return $this->type->abbreviation
            .' "'.$this->name.'"'
            .', '
            .$this->zip_code
            .', '
            .$this->city->name
            .', '
            .$this->address
            .', '
            .'тел. '.$this->phone
            .', '
            .'ОГРН '.$this->tax_psrn
            .' '.$this->tax_psrn_serial;
    }

    /**
     * @return BelongsTo
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(LegalEntityType::class, 'type_id', 'entity_type_id');
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

    /**
     * @return HasOne
     */
    public function franchise(): HasOne
    {
        return $this->hasOne(Franchise::class, 'entity_id', 'legal_entity_id');
    }

    /**
     * @return BelongsToMany
     */
    public function franchises(): BelongsToMany
    {
        return $this->belongsToMany(Franchise::class, 'franchise_entities', 'legal_entity_id', 'franchise_id', 'legal_entity_id', 'franchise_id');
    }

    /**
     * @return HasMany
     */
    public function banks(): HasMany
    {
        return $this->hasMany(LegalEntityBank::class, 'entity_id', 'legal_entity_id');
    }
}
