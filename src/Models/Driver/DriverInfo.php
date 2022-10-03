<?php

declare(strict_types=1);

namespace Src\Models\Driver;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;
use Src\Models\Location\City;
use Src\Models\Location\Country;
use Src\Models\Location\Region;

/**
 * Class DriverInfo
 *
 * @package Src\Models\Driver
 * @property int $driver_info_id
 * @property int|null $candidate_id
 * @property string $license_type
 * @property string|null $photo
 * @property string|null $license_qr_code
 * @property string|null $license_scan
 * @property int|null $license_code
 * @property int|null $license_revocation
 * @property string|null $license_revocation_cause
 * @property string|null $passport_expiry
 * @property string|null $passport_serial
 * @property string|null $passport_scan
 * @property int|null $experience
 * @property int|null $penalty
 * @property float|null $penalty_size
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read DriverCandidate $candidate
 * @method static Builder|DriverInfo newModelQuery()
 * @method static Builder|DriverInfo newQuery()
 * @method static Builder|DriverInfo query()
 * @method static Builder|DriverInfo whereCandidateId($value)
 * @method static Builder|DriverInfo whereCreatedAt($value)
 * @method static Builder|DriverInfo whereDriverInfoId($value)
 * @method static Builder|DriverInfo whereExperience($value)
 * @method static Builder|DriverInfo whereLicenseCode($value)
 * @method static Builder|DriverInfo whereLicenseQrCode($value)
 * @method static Builder|DriverInfo whereLicenseRevocation($value)
 * @method static Builder|DriverInfo whereLicenseRevocationCause($value)
 * @method static Builder|DriverInfo whereLicenseScan($value)
 * @method static Builder|DriverInfo whereLicenseType($value)
 * @method static Builder|DriverInfo wherePassportExpiry($value)
 * @method static Builder|DriverInfo wherePassportScan($value)
 * @method static Builder|DriverInfo wherePassportSerial($value)
 * @method static Builder|DriverInfo wherePenalty($value)
 * @method static Builder|DriverInfo wherePenaltySize($value)
 * @method static Builder|DriverInfo wherePhoto($value)
 * @method static Builder|DriverInfo whereUpdatedAt($value)
 * @mixin Eloquent
 * @property int|null $franchise_id
 * @property string $birthday
 * @property string $name
 * @property string $surname
 * @property string $patronymic
 * @property string $full_name
 * @property string $full_name_short
 * @property-read DriverInfo $driver
 * @property-read string $unix_date
 * @method static Builder|DriverInfo whereBirthday($value)
 * @method static Builder|DriverInfo whereFranchiseId($value)
 * @method static Builder|DriverInfo whereName($value)
 * @method static Builder|DriverInfo wherePatronymic($value)
 * @method static Builder|DriverInfo whereSurname($value)
 * @property string $citizen
 * @property string $zip_code
 * @property int|null $country_id
 * @property int|null $region_id
 * @property int|null $city_id
 * @property string $address
 * @property string $id_kis_art
 * @property string $passport_number
 * @property string $passport_issued_by
 * @property string $passport_when_issued
 * @property-read City|null $city
 * @property-read Country|null $country
 * @property-read Region|null $region
 * @method static Builder|ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|DriverInfo whereAddress($value)
 * @method static Builder|DriverInfo whereCitizen($value)
 * @method static Builder|DriverInfo whereCityId($value)
 * @method static Builder|DriverInfo whereCountryId($value)
 * @method static Builder|DriverInfo wherePassportIssuedBy($value)
 * @method static Builder|DriverInfo wherePassportNumber($value)
 * @method static Builder|DriverInfo wherePassportWhenIssued($value)
 * @method static Builder|DriverInfo whereRegionId($value)
 * @method static Builder|DriverInfo whereZipCode($value)
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property string|null $email
 * @property float|null $deposit
 * @method static Builder|DriverInfo whereDeposit($value)
 * @method static Builder|DriverInfo whereEmail($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\Src\Models\Driver\DriverLicenseType[] $license_types
 * @property-read int|null $license_types_count
 * @property string $license_date
 * @property string $license_expiry
 * @method static Builder|DriverInfo whereLicenseDate($value)
 * @method static Builder|DriverInfo whereLicenseExpiry($value)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel comparison($geometryColumn, $geometry, $relationship)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel contains($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel crosses($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel disjoint($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel distanceExcludingSelf($geometryColumn, $geometry, $distance)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel distanceSphere($geometryColumn, $geometry, $distance)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel distanceSphereExcludingSelf($geometryColumn, $geometry, $distance)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel distanceSphereValue($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel distanceValue($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel doesTouch($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel equals($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel intersects($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel orderByDistance($geometryColumn, $geometry, $direction = 'asc')
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel orderByDistanceSphere($geometryColumn, $geometry, $direction = 'asc')
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel orderBySpatial($geometryColumn, $geometry, $orderFunction, $direction = 'asc')
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel overlaps($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel within($geometryColumn, $polygon)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|DriverInfo whereIdKisArt($value)
 */
class DriverInfo extends ServiceModel
{
    /**
     * @var string
     */
    protected $table = 'drivers_info';
    /**
     * @var string
     */
    protected $primaryKey = 'driver_info_id';
    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'surname',
        'patronymic',
        'email',
        'license_qr_code',
        'license_code',
        'license_scan',
        'license_date',
        'license_expiry',
        'passport_serial',
        'passport_scan',
        'photo',
        'experience',
        'birthday',
        'passport_number',
        'passport_issued_by',
        'passport_when_issued',
        'citizen',
        'address',
        'deposit',
        'id_kis_art'
    ];
    /**
     * @var string[]
     */
    protected $casts = [
        'deposit' => 'float',
    ];

    /**
     * Get the driver's full name.
     *
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        return $this->surname . ' ' . $this->name . ' ' . $this->patronymic;
    }

    /**
     * Get the driver's short full name.
     *
     * @return string
     */
    public function getFullNameShortAttribute(): string
    {
        return $this->surname . ' ' . $this->name[0] . '.' . $this->patronymic[0] . '.';
    }

    /**
     * @return BelongsToMany
     */
    public function license_types(): BelongsToMany
    {
        return $this->belongsToMany(
            DriverLicenseType::class,
            'driver_info_license_type',
            'driver_info_id',
            'license_type_id',
            'driver_info_id',
            'driver_license_type_id'
        );
    }

    /**
     * @return HasOne
     */
    public function driver(): HasOne
    {
        return $this->hasOne(Driver::class, 'driver_info_id');
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
    public function candidate(): HasOne
    {
        return $this->hasOne(DriverCandidate::class, 'driver_info_id', 'driver_info_id');
    }
}
