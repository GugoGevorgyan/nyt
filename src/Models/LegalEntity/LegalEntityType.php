<?php

declare(strict_types=1);

namespace Src\Models\LegalEntity;

use Eloquent;
use Grimzy\LaravelMysqlSpatial\Eloquent\Builder;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;

/**
 * Src\Models\LegalEntity\LegalEntityType
 *
 * @property int $entity_type_id
 * @property string $abbreviation
 * @property string $name
 * @property string $value
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntityType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntityType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntityType query()
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntityType whereAbbreviation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntityType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntityType whereEntityTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntityType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntityType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LegalEntityType whereValue($value)
 * @mixin Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel except($values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
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
 * @property int $legal_entity_id
 * @property int|null $type_id
 * @property int|null $country_id
 * @property int|null $region_id
 * @property int|null $city_id
 * @property string|null $zip_code
 * @property string|null $address
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $tax_inn
 * @property string|null $tax_kpp
 * @property string|null $tax_psrn ОГРН
 * @property string|null $tax_psrn_serial серия ОГРН
 * @property string|null $tax_psrn_issued_by кем выдан ОГРН
 * @property string|null $tax_psrn_date дата выдачи ОГРН
 * @property string|null $aucneb ОКОНХ
 * @property string|null $arceo ОКПО
 * @property string|null $arcfo ОКФС
 * @property string|null $arclf ОКОПФ
 * @property string|null $registration_certificate_number Номер свидетельства о регистрации
 * @property string|null $registration_certificate_date Дата выдачи свидетельства о регистрации
 * @property string|null $director_name
 * @property string|null $director_surname
 * @property string|null $director_patronymic
 * @property string|null $deleted_at
 * @method static Builder|LegalEntityType whereAddress($value)
 * @method static Builder|LegalEntityType whereArceo($value)
 * @method static Builder|LegalEntityType whereArcfo($value)
 * @method static Builder|LegalEntityType whereArclf($value)
 * @method static Builder|LegalEntityType whereAucneb($value)
 * @method static Builder|LegalEntityType whereCityId($value)
 * @method static Builder|LegalEntityType whereCountryId($value)
 * @method static Builder|LegalEntityType whereDeletedAt($value)
 * @method static Builder|LegalEntityType whereDirectorName($value)
 * @method static Builder|LegalEntityType whereDirectorPatronymic($value)
 * @method static Builder|LegalEntityType whereDirectorSurname($value)
 * @method static Builder|LegalEntityType whereEmail($value)
 * @method static Builder|LegalEntityType whereLegalEntityId($value)
 * @method static Builder|LegalEntityType wherePhone($value)
 * @method static Builder|LegalEntityType whereRegionId($value)
 * @method static Builder|LegalEntityType whereRegistrationCertificateDate($value)
 * @method static Builder|LegalEntityType whereRegistrationCertificateNumber($value)
 * @method static Builder|LegalEntityType whereTaxInn($value)
 * @method static Builder|LegalEntityType whereTaxKpp($value)
 * @method static Builder|LegalEntityType whereTaxPsrn($value)
 * @method static Builder|LegalEntityType whereTaxPsrnDate($value)
 * @method static Builder|LegalEntityType whereTaxPsrnIssuedBy($value)
 * @method static Builder|LegalEntityType whereTaxPsrnSerial($value)
 * @method static Builder|LegalEntityType whereTypeId($value)
 * @method static Builder|LegalEntityType whereZipCode($value)
 */
class LegalEntityType extends ServiceModel
{
    /**
     * @var string
     */
    protected $table = 'legal_entity_types';
    /**
     * @var string
     */
    protected $primaryKey = 'entity_type_id';
}
