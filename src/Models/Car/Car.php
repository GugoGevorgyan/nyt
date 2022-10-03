<?php

declare(strict_types=1);

namespace Src\Models\Car;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;
use Src\Models\CarReport\CarReport;
use Src\Models\Driver\Driver;
use Src\Models\Driver\DriverInfo;
use Src\Models\LegalEntity\LegalEntity;
use Src\Models\Order\Order;
use Src\Models\Park;
use Src\Models\Terminal\Waybill;
use Staudenmeir\EloquentJsonRelations\Relations\BelongsToJson;
use Znck\Eloquent\Relations\BelongsToThrough;

/**
 * Src\Models\Cars
 *
 * @property int $car_id
 * @method static Builder|Car newModelQuery()
 * @method static Builder|Car newQuery()
 * @method static Builder|Car query()
 * @method static Builder|Car whereCarId($value)
 * @mixin Eloquent
 * @property int|null $car_class_id
 * @property int|null $park_id
 * @property-read ElasticquentCollection|Driver[] $crewDrivers
 * @property-read Driver $driver
 * @method static Builder|Car whereCarClassId($value)
 * @method static Builder|Car whereParkId($value)
 * @property-read ElasticquentCollection|Order[] $orders
 * @property-read Park|null $park
 * @property int|null $current_driver_id
 * @property array|null $driver_ids
 * @property string|null $mark
 * @property string|null $model
 * @property string|null $year
 * @property string|null $status change to enum
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Driver $current_driver
 * @method static Builder|Car whereCreatedAt($value)
 * @method static Builder|Car whereCurrentDriverId($value)
 * @method static Builder|Car whereDriverIds($value)
 * @method static Builder|Car whereMark($value)
 * @method static Builder|Car whereModel($value)
 * @method static Builder|Car whereStatus($value)
 * @method static Builder|Car whereUpdatedAt($value)
 * @method static Builder|Car whereYear($value)
 * @property-read CarClass|null $car_class
 * @property int|null $franchise_id
 * @property mixed|null $options
 * @property string $vin_code
 * @property string $inspection_date
 * @property string $inspection_expiration_date
 * @property string $inspection_scan
 * @property string $insurance_date
 * @property string $insurance_expiration_date
 * @property string $insurance_scan
 * @property string|null $color
 * @property string|null $state_license_plate
 * @property int|null $speedometer
 * @property int|null $garage_number
 * @property Carbon|null $deleted_at
 * @property-read Collection|CarCrash[] $crashes
 * @property-read int|null $crashes_count
 * @property-read Collection|Driver[] $drivers
 * @property-read int|null $drivers_count
 * @property-read int|null $orders_count
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|Car onlyTrashed()
 * @method static bool|null restore()
 * @method static Builder|Car whereColor($value)
 * @method static Builder|Car whereDeletedAt($value)
 * @method static Builder|Car whereFranchiseId($value)
 * @method static Builder|Car whereGarageNumber($value)
 * @method static Builder|Car whereInspectionDate($value)
 * @method static Builder|Car whereInspectionExpirationDate($value)
 * @method static Builder|Car whereInspectionScan($value)
 * @method static Builder|Car whereInsuranceDate($value)
 * @method static Builder|Car whereInsuranceExpirationDate($value)
 * @method static Builder|Car whereInsuranceScan($value)
 * @method static Builder|Car whereOptions($value)
 * @method static Builder|Car whereSpeedometer($value)
 * @method static Builder|Car whereStateLicensePlate($value)
 * @method static Builder|Car whereVinCode($value)
 * @method static \Illuminate\Database\Query\Builder|Car withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Car withoutTrashed()
 * @property string $body_number
 * @property string $vehicle_licence_number
 * @property string $vehicle_licence_date
 * @property string $registration_number
 * @property string $registration_date
 * @property float|null $rating
 * @method static Builder|Car whereBodyNumber($value)
 * @method static Builder|Car whereRating($value)
 * @method static Builder|Car whereRegistrationDate($value)
 * @method static Builder|Car whereRegistrationNumber($value)
 * @method static Builder|Car whereVehicleLicenceDate($value)
 * @method static Builder|Car whereVehicleLicenceNumber($value)
 * @method static Builder|ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property array|null $option
 * @property array $class
 * @method static Builder|Car whereClass($value)
 * @method static Builder|Car whereOption($value)
 * @property int $status_id
 * @property int|null $entity_id
 * @property-read LegalEntity|null $entity
 * @method static Builder|Car whereEntityId($value)
 * @method static Builder|Car whereStatusId($value)
 * @property-read Collection|CarReport[] $report
 * @property-read int|null $report_count
 * @property string|null $sts_number
 * @property string|null $pts_number
 * @property string|null $sts_file
 * @property string|null $pts_file
 * @property array|null $images
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static Builder|Car whereImages($value)
 * @method static Builder|Car wherePtsFile($value)
 * @method static Builder|Car wherePtsNumber($value)
 * @method static Builder|Car whereStsFile($value)
 * @method static Builder|Car whereStsNumber($value)
 */
class Car extends ServiceModel
{
    /**
     * @var string
     */
    protected $table = 'cars';
    /**
     * @var string
     */
    protected $primaryKey = 'car_id';
    /**
     * @var array
     */
    protected $fillable = [
        'park_id',
        'entity_id',
        'class',
        'current_driver_id',
        'franchise_id',
        'body_number',
        'vehicle_licence_number',
        'vehicle_licence_date',
        'sts_number',
        'pts_number',
        'sts_file',
        'pts_file',
        'registration_date',
        'option',
        'mark',
        'model',
        'year',
        'images',
        'status_id',
        'rating',
        'inspection_date',
        'inspection_expiration_date',
        'inspection_scan',
        'insurance_date',
        'insurance_expiration_date',
        'insurance_scan',
        'speedometer',
        'state_license_plate',
        'garage_number',
        'vin_code',
        'color',
    ];
    /**
     * @var string[]
     */
    protected $casts = ['class' => 'json', 'option' => 'json', 'images' => 'array'];

    /**
     * @return HasMany
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, $this->primaryKey, $this->primaryKey);
    }

    /**
     * @return BelongsTo
     */
    public function park(): BelongsTo
    {
        return $this->belongsTo(Park::class, 'park_id');
    }

    /**
     * @return HasOne
     */
    public function current_driver(): HasOne
    {
        return $this->hasOne(Driver::class, 'driver_id', 'current_driver_id');
    }

    /**
     * @return BelongsToThrough
     */
    public function current_driver_info(): BelongsToThrough
    {
        return $this->belongsToThrough(DriverInfo::class, Driver::class, 'current_driver_id', '',
            [DriverInfo::class => 'driver_info_id', Driver::class => 'car_id']);
    }

    /**
     * @return HasMany
     */
    public function drivers(): HasMany
    {
        return $this->hasMany(Driver::class, $this->primaryKey, $this->primaryKey);
    }

    /**
     * @return BelongsTo
     */
    public function entity(): BelongsTo
    {
        return $this->belongsTo(LegalEntity::class, 'entity_id', 'legal_entity_id');
    }

    /**
     * @return HasMany
     */
    public function crashes(): HasMany
    {
        return $this->hasMany(CarCrash::class, $this->primaryKey, $this->primaryKey);
    }

    /**
     * @return BelongsToJson
     */
    public function classes(): BelongsToJson
    {
        return $this->belongsToJson(CarClass::class, 'class->ids', 'car_class_id');
    }

    /**
     * @return BelongsToJson
     */
    public function options(): BelongsToJson
    {
        return $this->belongsToJson(CarOption::class, 'option->ids', 'car_option_id');
    }

    /**
     * @return BelongsTo
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(CarStatus::class, 'status_id', 'car_status_id');
    }

    /**
     * @return HasManyThrough
     */
    public function report(): HasManyThrough
    {
        return $this->hasManyThrough(CarReport::class, Waybill::class, 'car_id', 'waybill_id');
    }
}
