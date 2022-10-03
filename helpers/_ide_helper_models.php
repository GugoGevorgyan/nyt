<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace Src\Models\CarReport{
/**
 * Src\Models\CarReport\CarReport
 *
 * @property int $car_report_id
 * @property int $waybill_id
 * @property int $emergency_lights
 * @property string|null $emergency_lights_comment
 * @property int $headlights
 * @property string|null $headlights_comment
 * @property int $tires
 * @property string|null $tires_comment
 * @property int $engine
 * @property string|null $engine_comment
 * @property array $images
 * @property string $comment
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $unix_date
 * @method static Builder|CarReport newModelQuery()
 * @method static Builder|CarReport newQuery()
 * @method static Builder|CarReport query()
 * @method static Builder|CarReport whereCarReportId($value)
 * @method static Builder|CarReport whereComment($value)
 * @method static Builder|CarReport whereCreatedAt($value)
 * @method static Builder|CarReport whereEmergencyLights($value)
 * @method static Builder|CarReport whereEmergencyLightsComment($value)
 * @method static Builder|CarReport whereEngine($value)
 * @method static Builder|CarReport whereEngineComment($value)
 * @method static Builder|CarReport whereHeadlights($value)
 * @method static Builder|CarReport whereHeadlightsComment($value)
 * @method static Builder|CarReport whereImages($value)
 * @method static Builder|CarReport whereTires($value)
 * @method static Builder|CarReport whereTiresComment($value)
 * @method static Builder|CarReport whereUnixDate($value)
 * @method static Builder|CarReport whereUpdatedAt($value)
 * @method static Builder|CarReport whereWaybillId($value)
 * @mixin Eloquent
 * @method static Builder|ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property int $question_id
 * @property int $verified
 * @property-read int|null $images_count
 * @property-read Waybill $waybill
 * @method static Builder|CarReport whereQuestionId($value)
 * @method static Builder|CarReport whereVerified($value)
 * @property-read \Src\Models\CarReport\CarReportQuestion $question
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
 */
	class CarReport extends \Eloquent {}
}

namespace Src\Models\CarReport{
/**
 * Class CarReportImage
 *
 * @package Src\Models\CarReport
 * @property int $car_report_image_id
 * @property int $report_id
 * @property string $path
 * @property string $name
 * @property string $ext
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Src\Models\CarReport\CarReport $report
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, int $distance = 1)
 * @method static Builder|ServiceModel except(array $values = [])
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|CarReportImage newModelQuery()
 * @method static Builder|CarReportImage newQuery()
 * @method static Builder|CarReportImage query()
 * @method static Builder|CarReportImage whereCarReportImageId($value)
 * @method static Builder|CarReportImage whereCreatedAt($value)
 * @method static Builder|CarReportImage whereExt($value)
 * @method static Builder|CarReportImage whereName($value)
 * @method static Builder|CarReportImage wherePath($value)
 * @method static Builder|CarReportImage whereReportId($value)
 * @method static Builder|CarReportImage whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 */
	class CarReportImage extends \Eloquent {}
}

namespace Src\Models\CarReport{
/**
 * Src\Models\CarReport\CarReportQuestion
 *
 * @property int $question_id
 * @property string $question
 * @property string $field_name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|CarReportQuestion newModelQuery()
 * @method static Builder|CarReportQuestion newQuery()
 * @method static Builder|CarReportQuestion query()
 * @method static Builder|CarReportQuestion whereCreatedAt($value)
 * @method static Builder|CarReportQuestion whereFieldName($value)
 * @method static Builder|CarReportQuestion whereQuestion($value)
 * @method static Builder|CarReportQuestion whereQuestionId($value)
 * @method static Builder|CarReportQuestion whereUpdatedAt($value)
 * @mixin Eloquent
 * @method static Builder|ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property int|null $point
 * @method static Builder|CarReportQuestion wherePoint($value)
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
 */
	class CarReportQuestion extends \Eloquent {}
}

namespace Src\Models\Car{
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
	class Car extends \Eloquent {}
}

namespace Src\Models\Car{
/**
 * Class CarClass
 *
 * @package Src\Models\Car
 * @property int $car_class_id
 * @property string|null $class_name
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Car[] $cars
 * @method static Builder|CarClass newModelQuery()
 * @method static Builder|CarClass newQuery()
 * @method static Builder|CarClass query()
 * @method static Builder|CarClass whereCarClassId($value)
 * @method static Builder|CarClass whereClassName($value)
 * @method static Builder|CarClass whereCreatedAt($value)
 * @method static Builder|CarClass whereDescription($value)
 * @method static Builder|CarClass whereUpdatedAt($value)
 * @mixin Eloquent
 * @property-read Collection|Order[] $orders
 * @property-read int|null $cars_count
 * @property-read Collection|TariffDestination[] $destinations
 * @property-read int|null $destinations_count
 * @property-read int|null $orders_count
 * @property-read Collection|Tariff[] $tariffs
 * @property-read int|null $tariffs_count
 * @method static Builder|ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @property string|null $image
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|CarClass whereImage($value)
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property string|null $name
 * @property-read \Src\Models\Car\ClassOptionTariff $class_option_tariff
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static Builder|CarClass whereName($value)
 */
	class CarClass extends \Eloquent {}
}

namespace Src\Models\Car{
/**
 * Class CarCrash
 *
 * @package Src\Models\Car
 * @property int $car_crash_id
 * @property int|null $car_id
 * @property int|null $driver_id
 * @property string $dateTime
 * @property string $address
 * @property string $description
 * @property int $our_fault
 * @property string $inspector_info
 * @property string $participant_info
 * @property string|null $act
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $unix_date
 * @property-read Driver|null $driver
 * @property-read Collection|CarCrashImage[] $images
 * @property-read int|null $images_count
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|CarCrash newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CarCrash newQuery()
 * @method static Builder|CarCrash onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|CarCrash query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|CarCrash whereAct($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarCrash whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarCrash whereCarCrashId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarCrash whereCarId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarCrash whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarCrash whereDateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarCrash whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarCrash whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarCrash whereDriverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarCrash whereInspectorInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarCrash whereOurFault($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarCrash whereParticipantInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarCrash whereUnixDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarCrash whereUpdatedAt($value)
 * @method static Builder|CarCrash withTrashed()
 * @method static Builder|CarCrash withoutTrashed()
 * @mixin Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel except($values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property float|null $act_sum
 * @method static \Illuminate\Database\Eloquent\Builder|CarCrash whereActSum($value)
 * @property-read Car|null $car
 * @property-read FranchiseTransaction|null $transaction
 * @property-read FranchiseTransaction|null $transaction_reason
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 */
	class CarCrash extends \Eloquent {}
}

namespace Src\Models\Car{
/**
 * Src\Models\Car\CarCrashImage
 *
 * @property int $car_crash_image_id
 * @property string $name
 * @property int|null $car_crash_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|CarCrashImage newModelQuery()
 * @method static Builder|CarCrashImage newQuery()
 * @method static Builder|CarCrashImage query()
 * @method static Builder|CarCrashImage whereCarCrashId($value)
 * @method static Builder|CarCrashImage whereCarCrashImageId($value)
 * @method static Builder|CarCrashImage whereCreatedAt($value)
 * @method static Builder|CarCrashImage whereName($value)
 * @method static Builder|CarCrashImage whereUpdatedAt($value)
 * @mixin Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\ServiceEntity\Models\ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static \Illuminate\Database\Eloquent\Builder|\ServiceEntity\Models\ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static \Illuminate\Database\Eloquent\Builder|\ServiceEntity\Models\ServiceModel except($values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property int|null $point
 * @method static Builder|CarCrashImage wherePoint($value)
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
 */
	class CarCrashImage extends \Eloquent {}
}

namespace Src\Models\Car{
/**
 * Class CarOption
 *
 * @package Src\Models\Car
 * @property int $car_option_id
 * @property string|null $option
 * @property float $price
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|CarOption newModelQuery()
 * @method static Builder|CarOption newQuery()
 * @method static Builder|CarOption query()
 * @method static Builder|CarOption whereCarOptionId($value)
 * @method static Builder|CarOption whereCreatedAt($value)
 * @method static Builder|CarOption whereOption($value)
 * @method static Builder|CarOption wherePrice($value)
 * @method static Builder|CarOption whereUpdatedAt($value)
 * @mixin Eloquent
 * @method static Builder|ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property string $name
 * @method static Builder|CarOption whereName($value)
 * @property string $value
 * @method static Builder|CarOption whereValue($value)
 * @property-read ClassOptionTariff $class_option_tariff
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 */
	class CarOption extends \Eloquent {}
}

namespace Src\Models\Car{
/**
 * Src\Models\Car\CarStatus
 *
 * @property int $car_status_id
 * @property string $value
 * @property string $text
 * @property string $color
 * @property string $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CarStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CarStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CarStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder|CarStatus whereCarStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarStatus whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarStatus whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarStatus whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarStatus whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarStatus whereValue($value)
 * @mixin \Eloquent
 */
	class CarStatus extends \Eloquent {}
}

namespace Src\Models\Car{
/**
 * Class ClassOptionTariff
 *
 * @package Src\Models\Car
 * @property int $class_option_tariff_id
 * @property int $tariff_id
 * @property int $class_id
 * @property int $option_id
 * @property float $price
 * @property Carbon $created_at
 * @property-read CarClass $car_class
 * @property-read CarOption $option
 * @property-read Tariff $tariff
 * @method static Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, int $distance = 1)
 * @method static Builder|ServiceModel except(array $values = [])
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ClassOptionTariff newModelQuery()
 * @method static Builder|ClassOptionTariff newQuery()
 * @method static Builder|ClassOptionTariff query()
 * @method static Builder|ClassOptionTariff whereClassId($value)
 * @method static Builder|ClassOptionTariff whereClassOptionTariffId($value)
 * @method static Builder|ClassOptionTariff whereCreatedAt($value)
 * @method static Builder|ClassOptionTariff whereOptionId($value)
 * @method static Builder|ClassOptionTariff wherePrice($value)
 * @method static Builder|ClassOptionTariff whereTariffId($value)
 * @mixin Eloquent
 */
	class ClassOptionTariff extends \Eloquent {}
}

namespace Src\Models\Chat{
/**
 * Src\Models\Chat\Message
 *
 * @property int $message_id
 * @property int $room_id
 * @property int $sender_id
 * @property string $text
 * @property mixed $un_seen
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Message newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Message newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Message query()
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereMessageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereRoomId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereSenderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereUnSeen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Message extends \Eloquent {}
}

namespace Src\Models\Chat{
/**
 * Class OrderConversation
 *
 * @package Src\Models\Chat
 * @property int $order_conversation_id
 * @property int $order_id
 * @property int $driver_id
 * @property int $client_id
 * @property string $client_type
 * @property Carbon $created_at
 * @property-read Model|Eloquent $client
 * @property-read Driver $driver
 * @property-read Collection|OrderConversationTalk[] $messages
 * @property-read int|null $messages_count
 * @property-read Order $order
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|OrderConversation newModelQuery()
 * @method static Builder|OrderConversation newQuery()
 * @method static Builder|OrderConversation query()
 * @method static Builder|OrderConversation whereClientId($value)
 * @method static Builder|OrderConversation whereClientType($value)
 * @method static Builder|OrderConversation whereCreatedAt($value)
 * @method static Builder|OrderConversation whereDriverId($value)
 * @method static Builder|OrderConversation whereOrderConversationId($value)
 * @method static Builder|OrderConversation whereOrderId($value)
 * @mixin Eloquent
 * @property int $sender_id
 * @property string $sender_type
 * @method static Builder|OrderConversation whereSenderId($value)
 * @method static Builder|OrderConversation whereSenderType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 */
	class OrderConversation extends \Eloquent {}
}

namespace Src\Models\Chat{
/**
 * Class OrderConversationTalk
 *
 * @package Src\Models\Chat
 * @property int $order_conversation_talk_id
 * @property int|null $order_conversation_id
 * @property int|null $sender_id
 * @property string|null $sender_type
 * @property string|null $message
 * @property-read OrderConversation|null $conversation
 * @property-read Model|Eloquent $sender
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|OrderConversationTalk newModelQuery()
 * @method static Builder|OrderConversationTalk newQuery()
 * @method static Builder|OrderConversationTalk query()
 * @method static Builder|OrderConversationTalk whereMessage($value)
 * @method static Builder|OrderConversationTalk whereOrderConversationId($value)
 * @method static Builder|OrderConversationTalk whereOrderConversationTalkId($value)
 * @method static Builder|OrderConversationTalk whereSenderId($value)
 * @method static Builder|OrderConversationTalk whereSenderType($value)
 * @mixin Eloquent
 * @property Carbon $created_at
 * @method static Builder|OrderConversationTalk whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 */
	class OrderConversationTalk extends \Eloquent {}
}

namespace Src\Models\Chat{
/**
 * Src\Models\Chat\Room
 *
 * @property int $room_id
 * @property int $creator_id
 * @property int $type_id
 * @property string|null $title
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Room newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Room newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Room query()
 * @method static \Illuminate\Database\Eloquent\Builder|Room whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Room whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Room whereRoomId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Room whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Room whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Room whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Room extends \Eloquent {}
}

namespace Src\Models\Chat{
/**
 * Src\Models\Chat\RoomType
 *
 * @property int $room_type_id
 * @property int $type
 * @property string $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|RoomType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RoomType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RoomType query()
 * @method static \Illuminate\Database\Eloquent\Builder|RoomType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RoomType whereRoomTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RoomType whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RoomType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RoomType whereValue($value)
 * @mixin \Eloquent
 */
	class RoomType extends \Eloquent {}
}

namespace Src\Models\Chat{
/**
 * Src\Models\Chat\WorkerRoom
 *
 * @property int $worker_room_id
 * @property int $room_id
 * @property int $system_worker_id
 * @property int $archived
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|WorkerRoom newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WorkerRoom newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WorkerRoom query()
 * @method static \Illuminate\Database\Eloquent\Builder|WorkerRoom whereArchived($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkerRoom whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkerRoom whereRoomId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkerRoom whereSystemWorkerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkerRoom whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkerRoom whereWorkerRoomId($value)
 * @mixin \Eloquent
 */
	class WorkerRoom extends \Eloquent {}
}

namespace Src\Models\Client{
/**
 * Class BeforeAuthClient
 *
 * @package Src\Models\Client
 * @property int $before_auth_client_id
 * @property int|null $client_id
 * @property string $hash_name
 * @property string $hash
 * @property string|null $remember_token
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|ClientSessionInfo[] $all_session_info
 * @property-read int|null $all_session_info_count
 * @property-read Client|null $client
 * @property-read Collection|\Src\Models\Oauth\Client[] $clients
 * @property-read int|null $clients_count
 * @property-read ClientCoordinate|null $coordinate
 * @property-read Collection|ClientCoordinate[] $coordinates
 * @property-read int|null $coordinates_count
 * @property-read ClientTaxiView|null $drivers_view
 * @property-read Collection|ClientTaxiView[] $drivers_views
 * @property-read int|null $drivers_views_count
 * @property-read OrderInitialData|null $initial_order_data
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read Collection|Role[] $roles
 * @property-read int|null $roles_count
 * @property-read ClientSessionInfo|null $session_info
 * @property-read Collection|Token[] $tokens
 * @property-read int|null $tokens_count
 * @method static Builder|ServiceAuthenticable except($values = [])
 * @method static Builder|BeforeAuthClient newModelQuery()
 * @method static Builder|BeforeAuthClient newQuery()
 * @method static \Illuminate\Database\Query\Builder|BeforeAuthClient onlyTrashed()
 * @method static Builder|ServiceAuthenticable permission($permissions)
 * @method static Builder|BeforeAuthClient query()
 * @method static Builder|ServiceAuthenticable role($roles, $guard = null)
 * @method static Builder|BeforeAuthClient whereBeforeAuthClientId($value)
 * @method static Builder|BeforeAuthClient whereClientId($value)
 * @method static Builder|BeforeAuthClient whereCreatedAt($value)
 * @method static Builder|BeforeAuthClient whereDeletedAt($value)
 * @method static Builder|BeforeAuthClient whereHash($value)
 * @method static Builder|BeforeAuthClient whereHashName($value)
 * @method static Builder|BeforeAuthClient whereRememberToken($value)
 * @method static Builder|BeforeAuthClient whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|BeforeAuthClient withTrashed()
 * @method static \Illuminate\Database\Query\Builder|BeforeAuthClient withoutTrashed()
 * @mixin Eloquent
 * @method static Builder|ServiceAuthenticable distance($latitude, $longitude)
 * @method static Builder|ServiceAuthenticable distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceAuthenticable geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceAuthenticable disatnceCordsss($latitude, $longitude, $distance)
 * @property-read Collection|\Src\Models\Role\Permission[] $permissions
 * @property-read int|null $permissions_count
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable comparison($geometryColumn, $geometry, $relationship)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable contains($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable cordDistance($latitude, $longitude)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable crosses($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable disjoint($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable distanceExcludingSelf($geometryColumn, $geometry, $distance)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable distanceSphere($geometryColumn, $geometry, $distance)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable distanceSphereExcludingSelf($geometryColumn, $geometry, $distance)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable distanceSphereValue($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable distanceValue($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable doesTouch($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable equals($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable intersects($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable orderByDistance($geometryColumn, $geometry, $direction = 'asc')
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable orderByDistanceSphere($geometryColumn, $geometry, $direction = 'asc')
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable orderBySpatial($geometryColumn, $geometry, $orderFunction, $direction = 'asc')
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable overlaps($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable within($geometryColumn, $polygon)
 */
	class BeforeAuthClient extends \Eloquent {}
}

namespace Src\Models\Client{
/**
 * Class Client
 *
 * @package Src\Models\Client
 * @property int $client_id
 * @property string $phone
 * @property string|null $email
 * @property string|null $password
 * @property string|null $remember_token
 * @property string|null $device
 * @property int|null $mean_assessment
 * @property int $logged
 * @property int $online
 * @property int $in_order
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|ClientAddress[] $addresses
 * @property-read int|null $addresses_count
 * @property-read Collection|ClientSessionInfo[] $all_session_info
 * @property-read int|null $all_session_info_count
 * @property-read BeforeAuthClient|null $before_auth
 * @property-read Collection|BeforeAuthClient[] $before_auths
 * @property-read int|null $before_auths_count
 * @property-read Collection|ClientCall[] $call
 * @property-read int|null $call_count
 * @property-read Order|null $cancel_order
 * @property-read Collection|OrderFeedback[] $canceled_feedbacks
 * @property-read int|null $canceled_feedbacks_count
 * @property-read Collection|CanceledOrder[] $canceled_orders
 * @property-read int|null $canceled_orders_count
 * @property-read Collection|\Src\Models\Oauth\Client[] $clients
 * @property-read int|null $clients_count
 * @property-read Collection|OrderFeedback[] $completed_feedbacks
 * @property-read int|null $completed_feedbacks_count
 * @property-read Collection|CorporateClient[] $corporate
 * @property-read int|null $corporate_count
 * @property-read Collection|Order[] $corporateOrders
 * @property-read int|null $corporate_orders_count
 * @property-read CanceledOrder|null $current_canceled_order
 * @property-read Order $current_order
 * @property-read ClientTaxiView|null $drivers_view
 * @property-read Collection|ClientTaxiView[] $drivers_views
 * @property-read int|null $drivers_views_count
 * @property-read Collection|Driver[] $favoriteDrivers
 * @property-read int|null $favorite_drivers_count
 * @property-read OrderInitialData|null $initial_order_data
 * @property-read Collection|Order[] $last_orders
 * @property-read int|null $last_orders_count
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read Collection|Order[] $orders
 * @property-read int|null $orders_count
 * @property-read Collection|Role[] $roles
 * @property-read int|null $roles_count
 * @property-read ClientSessionInfo|null $session_info
 * @property-read ClientSetting|null $setting
 * @property-read Collection|Token[] $tokens
 * @property-read int|null $tokens_count
 * @method static Builder|Client canceledOrderFeedback()
 * @method static Builder|Client completedOrderFeedback()
 * @method static Builder|ServiceAuthenticable except($values = [])
 * @method static Builder|Client newModelQuery()
 * @method static Builder|Client newQuery()
 * @method static \Illuminate\Database\Query\Builder|Client onlyTrashed()
 * @method static Builder|ServiceAuthenticable permission($permissions)
 * @method static Builder|Client query()
 * @method static Builder|Client resetedOrderFeedback()
 * @method static Builder|ServiceAuthenticable role($roles, $guard = null)
 * @method static Builder|Client whereClientId($value)
 * @method static Builder|Client whereCreatedAt($value)
 * @method static Builder|Client whereDeletedAt($value)
 * @method static Builder|Client whereDevice($value)
 * @method static Builder|Client whereEmail($value)
 * @method static Builder|Client whereInOrder($value)
 * @method static Builder|Client whereLogged($value)
 * @method static Builder|Client whereMeanAssessment($value)
 * @method static Builder|Client whereOnline($value)
 * @method static Builder|Client wherePassword($value)
 * @method static Builder|Client wherePhone($value)
 * @method static Builder|Client whereRememberToken($value)
 * @method static Builder|Client whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Client withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Client withoutTrashed()
 * @mixin Eloquent
 * @property-read int|null $assessmentables_count
 * @property-read int|null $assessments_count
 * @property object initial_order
 * @property-read CompletedOrder|null $completed_order
 * @property-read Collection|CompletedOrder[] $completed_orders
 * @property-read int|null $completed_orders_count
 * @property-read Collection|OrderConversation[] $conversation
 * @property-read int|null $conversation_count
 * @property-read Collection|OrderConversationTalk[] $conversation_sender
 * @property-read int|null $conversation_sender_count
 * @method static Builder|ServiceAuthenticable disatnceCordsss($latitude, $longitude, $distance)
 * @method static Builder|ServiceAuthenticable distance($latitude, $longitude)
 * @method static Builder|ServiceAuthenticable distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceAuthenticable geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @property-read Collection|Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property string|null $name
 * @property string|null $surname
 * @property string|null $patronymic
 * @method static Builder|Client whereName($value)
 * @method static Builder|Client wherePatronymic($value)
 * @method static Builder|Client whereSurname($value)
 * @property int $only_passenger
 * @property-read Collection|OrderFeedback[] $assessed
 * @property-read int|null $assessed_count
 * @property-read Collection|OrderFeedback[] $rater
 * @property-read int|null $rater_count
 * @method static Builder|Client whereOnlyPassenger($value)
 * @property-read FcmClient|null $fcm
 * @property-read Order|null $order
 * @property-read Collection|PreOrder[] $preorders
 * @property-read int|null $preorders_count
 * @property-read Collection|Driver[] $current_order_driver
 * @property-read int|null $current_order_driver_count
 * @property-read Collection|OrderInProcessRoad[] $current_order_in_process_road
 * @property-read int|null $current_order_in_process_road_count
 * @property-read Collection|OrderOnWayRoad[] $current_order_on_way_road
 * @property-read int|null $current_order_on_way_road_count
 * @property-read Collection|OrderProcess[] $current_order_process
 * @property-read int|null $current_order_process_count
 * @property-read OrderShippedDriver|null $current_shipped
 * @property-read Collection|PayCard[] $pay_cards
 * @property-read int|null $pay_cards_count
 * @property-read Collection|FranchiseTransaction[] $transactions
 * @property-read int|null $transactions_count
 * @property-read string $full_name
 * @method static Builder|ServiceAuthenticable cordDistance($latitude, $longitude)
 * @property-read Collection|PreOrder[] $pre_orders
 * @property-read int|null $pre_orders_count
 */
	class Client extends \Eloquent {}
}

namespace Src\Models\Client{
/**
 * Src\Models\ClientAddress
 *
 * @property int $client_address_id
 * @property string|null $name
 * @property string|null $address
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Client[] $clients
 * @method static Builder|ClientAddress newModelQuery()
 * @method static Builder|ClientAddress newQuery()
 * @method static Builder|ClientAddress query()
 * @method static Builder|ClientAddress whereAddress($value)
 * @method static Builder|ClientAddress whereClientAddressId($value)
 * @method static Builder|ClientAddress whereCreatedAt($value)
 * @method static Builder|ClientAddress whereName($value)
 * @method static Builder|ClientAddress whereUpdatedAt($value)
 * @mixin Eloquent
 * @property string|null $namespace
 * @property string|null $value
 * @property string|null $displayName
 * @property string|null $type
 * @property string|null $porch
 * @property string|null $driverHint
 * @property array|null $coordinates
 * @property int|null $favorite
 * @property string $icon
 * @property-read int|null $clients_count
 * @method static Builder|ClientAddress whereCoordinates($value)
 * @method static Builder|ClientAddress whereDisplayName($value)
 * @method static Builder|ClientAddress whereDriverHint($value)
 * @method static Builder|ClientAddress whereFavorite($value)
 * @method static Builder|ClientAddress whereIcon($value)
 * @method static Builder|ClientAddress whereNamespace($value)
 * @method static Builder|ClientAddress wherePorch($value)
 * @method static Builder|ClientAddress whereType($value)
 * @method static Builder|ClientAddress whereValue($value)
 * @method static Builder|ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @property int $client_id
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|ClientAddress whereClientId($value)
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property string|null $short_address
 * @property string|null $driver_hint
 * @method static Builder|ClientAddress whereShortAddress($value)
 * @property string $lat
 * @property string $lut
 * @method static Builder|ClientAddress whereLat($value)
 * @method static Builder|ClientAddress whereLut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 */
	class ClientAddress extends \Eloquent {}
}

namespace Src\Models\Client{
/**
 * Src\Models\ClientMessage\ClientCall
 *
 * @property int $client_call_id
 * @property int $franchise_id
 * @property int|null $operator_id
 * @property int $callable_id
 * @property string $callable_type
 * @property string $call_start
 * @property string $call_end
 * @property int $call_duration
 * @property string $unix_time
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static Builder|ClientCall newModelQuery()
 * @method static Builder|ClientCall newQuery()
 * @method static Builder|ClientCall query()
 * @method static Builder|ClientCall whereCallDuration($value)
 * @method static Builder|ClientCall whereCallEnd($value)
 * @method static Builder|ClientCall whereCallStart($value)
 * @method static Builder|ClientCall whereCallableId($value)
 * @method static Builder|ClientCall whereCallableType($value)
 * @method static Builder|ClientCall whereClientCallId($value)
 * @method static Builder|ClientCall whereCreatedAt($value)
 * @method static Builder|ClientCall whereFranchiseId($value)
 * @method static Builder|ClientCall whereOperatorId($value)
 * @method static Builder|ClientCall whereUnixTime($value)
 * @method static Builder|ClientCall whereUpdatedAt($value)
 * @mixin Eloquent
 * @property int $franchise_phone_id
 * @property int|null $franchise_sub_phone_id
 * @property int|null $system_worker_id
 * @property int|null $worker_operator_id
 * @property string $client_phone
 * @property-read Model|Eloquent $callable
 * @property-read Franchise $franchise
 * @property-read FranchisePhone $franchisePhone
 * @property-read FranchiseSubPhone|null $franchiseSubPhone
 * @property-read WorkerOperator|null $operator
 * @method static Builder|ClientCall whereClientPhone($value)
 * @method static Builder|ClientCall whereFranchisePhoneId($value)
 * @method static Builder|ClientCall whereFranchiseSubPhoneId($value)
 * @method static Builder|ClientCall whereSystemWorkerId($value)
 * @method static Builder|ClientCall whereWorkerOperatorId($value)
 * @property int $incoming
 * @property int $answered
 * @property-read mixed $call_date
 * @property-read mixed $call_time
 * @property-read mixed $duration_time
 * @property-read mixed $time_ago
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|ClientCall whereAnswered($value)
 * @method static Builder|ClientCall whereIncoming($value)
 * @property int|null $workerable_id
 * @property string|null $workerable_type
 * @property-read Model|Eloquent $workerable
 * @method static Builder|ClientCall whereWorkerableId($value)
 * @method static Builder|ClientCall whereWorkerableType($value)
 * @property int|null $client_id
 * @property-read Client|null $client
 * @property-read SystemWorker|null $system_worker
 * @method static Builder|ClientCall whereClientId($value)
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static Builder|ClientCall whereTimeAgo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 */
	class ClientCall extends \Eloquent {}
}

namespace Src\Models\Client{
/**
 * Class ClientFavoriteDriver
 *
 * @package Src\Models\Client
 * @property int $client_favorite_driver
 * @property int|null $client_id
 * @property int|null $driver_id
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, int $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel except(array $values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientFavoriteDriver newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ClientFavoriteDriver newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ClientFavoriteDriver query()
 * @method static \Illuminate\Database\Eloquent\Builder|ClientFavoriteDriver whereClientFavoriteDriver($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientFavoriteDriver whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientFavoriteDriver whereDriverId($value)
 * @mixin \Eloquent
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property string|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientFavoriteDriver whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientFavoriteDriver whereUpdatedAt($value)
 */
	class ClientFavoriteDriver extends \Eloquent {}
}

namespace Src\Models\Client{
/**
 * Class ClientSessionInfo
 *
 * @package Src\Models\ClientMessage
 * @property int $client_session_info_id
 * @property int $clientable_id
 * @property string $clientable_type
 * @property int|null $country_id
 * @property int|null $region_id
 * @property int|null $city_id
 * @property string|null $ip_address
 * @property string|null $device
 * @property string|null $platform
 * @property int $mobile
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Model|Eloquent $client
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|ClientSessionInfo newModelQuery()
 * @method static Builder|ClientSessionInfo newQuery()
 * @method static \Illuminate\Database\Query\Builder|ClientSessionInfo onlyTrashed()
 * @method static Builder|ClientSessionInfo query()
 * @method static Builder|ClientSessionInfo whereCityId($value)
 * @method static Builder|ClientSessionInfo whereClientSessionInfoId($value)
 * @method static Builder|ClientSessionInfo whereClientableId($value)
 * @method static Builder|ClientSessionInfo whereClientableType($value)
 * @method static Builder|ClientSessionInfo whereCountryId($value)
 * @method static Builder|ClientSessionInfo whereCreatedAt($value)
 * @method static Builder|ClientSessionInfo whereDeletedAt($value)
 * @method static Builder|ClientSessionInfo whereDevice($value)
 * @method static Builder|ClientSessionInfo whereIpAddress($value)
 * @method static Builder|ClientSessionInfo whereMobile($value)
 * @method static Builder|ClientSessionInfo wherePlatform($value)
 * @method static Builder|ClientSessionInfo whereRegionId($value)
 * @method static Builder|ClientSessionInfo whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|ClientSessionInfo withTrashed()
 * @method static \Illuminate\Database\Query\Builder|ClientSessionInfo withoutTrashed()
 * @mixin Eloquent
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 */
	class ClientSessionInfo extends \Eloquent {}
}

namespace Src\Models\Client{
/**
 * Class ClientSetting
 *
 * @package Src\Models\ClientMessage
 * @property int $client_setting_id
 * @property int $client_id
 * @property int $show_driver_my_coordinates
 * @property int $not_call
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property-read Client $client
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|ClientSetting newModelQuery()
 * @method static Builder|ClientSetting newQuery()
 * @method static Builder|ClientSetting query()
 * @method static Builder|ClientSetting whereClientId($value)
 * @method static Builder|ClientSetting whereClientSettingId($value)
 * @method static Builder|ClientSetting whereCreatedAt($value)
 * @method static Builder|ClientSetting whereNotCall($value)
 * @method static Builder|ClientSetting whereShowDriverMyCoordinates($value)
 * @method static Builder|ClientSetting whereUpdatedAt($value)
 * @mixin Eloquent
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
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
 */
	class ClientSetting extends \Eloquent {}
}

namespace Src\Models\Client{
/**
 * Class ClientTaxiView
 *
 * @package Src\Models\ClientMessage
 * @property int $client_driver_view_id
 * @property int|null $client_id
 * @property Driver $driver
 * @property int|null $status
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Client|null $client
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|ClientTaxiView newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ClientTaxiView newQuery()
 * @method static Builder|ClientTaxiView onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ClientTaxiView query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|ClientTaxiView whereClientCoordinates($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientTaxiView whereClientDriverViewId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientTaxiView whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientTaxiView whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientTaxiView whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientTaxiView whereDriver($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientTaxiView whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientTaxiView whereUpdatedAt($value)
 * @method static Builder|ClientTaxiView withTrashed()
 * @method static Builder|ClientTaxiView withoutTrashed()
 * @mixin Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @property int|null $road_taxi_view_id
 * @property int|null $clientable_id
 * @property string $clientable_type
 * @property-read Model|Eloquent $before_client
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel except($values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ClientTaxiView whereClientCoordinateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientTaxiView whereClientableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientTaxiView whereClientableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientTaxiView whereRoadTaxiViewId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @property-read Driver $drivers
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 */
	class ClientTaxiView extends \Eloquent {}
}

namespace Src\Models\Complaint{
/**
 * Src\Models\Complaint
 *
 * @property int $complaint_id
 * @property int $writer_id Кто написал жалобу
 * @property int $recipient_id На кого написали жалобу
 * @property int $status_id На каком этапе расмотрения жалоба
 * @property string $complaint Жалоба
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read SystemWorker $recipient
 * @property-read ComplaintStatus $status
 * @property-read SystemWorker $writer
 * @method static Builder|Complaint newModelQuery()
 * @method static Builder|Complaint newQuery()
 * @method static Builder|Complaint query()
 * @method static Builder|Complaint whereComplaint($value)
 * @method static Builder|Complaint whereComplaintId($value)
 * @method static Builder|Complaint whereCreatedAt($value)
 * @method static Builder|Complaint whereRecipientId($value)
 * @method static Builder|Complaint whereStatusId($value)
 * @method static Builder|Complaint whereUpdatedAt($value)
 * @method static Builder|Complaint whereWriterId($value)
 * @mixin Eloquent
 * @property int|null $order_id К какому заказу относится жалоба
 * @property string $decision Отчет по жалобе
 * @property-read Order $order
 * @method static Builder|ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|Complaint whereDecision($value)
 * @method static Builder|Complaint whereOrderId($value)
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property int $franchise_id
 * @property string $subject
 * @property-read \Illuminate\Database\Eloquent\Collection|\Src\Models\Complaint\ComplaintFile[] $files
 * @property-read int|null $files_count
 * @method static Builder|Complaint whereFranchiseId($value)
 * @method static Builder|Complaint whereSubject($value)
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
 */
	class Complaint extends \Eloquent {}
}

namespace Src\Models\Complaint{
/**
 * Src\Models\Complaint\ComplaintComment
 *
 * @property int $complaint_comment_id
 * @property int $complaint_id
 * @property int $worker_id
 * @property string|null $text
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Complaint $complaint
 * @property-read Collection|ComplaintCommentFile[] $files
 * @property-read int|null $files_count
 * @property-read SystemWorker $worker
 * @method static Builder|ComplaintComment newModelQuery()
 * @method static Builder|ComplaintComment newQuery()
 * @method static Builder|ComplaintComment query()
 * @method static Builder|ComplaintComment whereComplaintCommentId($value)
 * @method static Builder|ComplaintComment whereComplaintId($value)
 * @method static Builder|ComplaintComment whereCreatedAt($value)
 * @method static Builder|ComplaintComment whereText($value)
 * @method static Builder|ComplaintComment whereUpdatedAt($value)
 * @method static Builder|ComplaintComment whereWorkerId($value)
 * @mixin Eloquent
 */
	class ComplaintComment extends \Eloquent {}
}

namespace Src\Models\Complaint{
/**
 * Src\Models\Complaint\ComplaintCommentFile
 *
 * @property int $complaint_comment_file_id
 * @property int $complaint_comment_id
 * @property string $file
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ComplaintCommentFile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ComplaintCommentFile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ComplaintCommentFile query()
 * @method static \Illuminate\Database\Eloquent\Builder|ComplaintCommentFile whereComplaintCommentFileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplaintCommentFile whereComplaintCommentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplaintCommentFile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplaintCommentFile whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplaintCommentFile whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class ComplaintCommentFile extends \Eloquent {}
}

namespace Src\Models\Complaint{
/**
 * Src\Models\Complaint\ComplaintFile
 *
 * @property int $complaint_file_id
 * @property int $complaint_id
 * @property string $file
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ComplaintFile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ComplaintFile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ComplaintFile query()
 * @method static \Illuminate\Database\Eloquent\Builder|ComplaintFile whereComplaintFileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplaintFile whereComplaintId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplaintFile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplaintFile whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplaintFile whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class ComplaintFile extends \Eloquent {}
}

namespace Src\Models\Complaint{
/**
 * Src\Models\ComplaintStatus
 *
 * @property int $complaint_status_id
 * @property string $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|ComplaintStatus newModelQuery()
 * @method static Builder|ComplaintStatus newQuery()
 * @method static Builder|ComplaintStatus query()
 * @method static Builder|ComplaintStatus whereComplaintStatusId($value)
 * @method static Builder|ComplaintStatus whereCreatedAt($value)
 * @method static Builder|ComplaintStatus whereStatus($value)
 * @method static Builder|ComplaintStatus whereUpdatedAt($value)
 * @mixin Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\ServiceEntity\Models\ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static \Illuminate\Database\Eloquent\Builder|\ServiceEntity\Models\ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static \Illuminate\Database\Eloquent\Builder|\ServiceEntity\Models\ServiceModel except($values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property string $text
 * @property int $value
 * @property string $color
 * @method static Builder|ComplaintStatus whereColor($value)
 * @method static Builder|ComplaintStatus whereText($value)
 * @method static Builder|ComplaintStatus whereValue($value)
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
 */
	class ComplaintStatus extends \Eloquent {}
}

namespace Src\Models\Corporate{
/**
 * Class AdminCorporate
 *
 * @package Src\Models
 * @property int $admin_corporate_id
 * @property int|null $park_id
 * @property string|null $name
 * @property string|null $email
 * @property string|null $remember_token
 * @property string|null $password
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Client[] $clients
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read Collection|Permission[] $permissions
 * @property-read Collection|Role[] $roles
 * @property-read Collection|Token[] $tokens
 * @method static Builder|AdminCorporate newModelQuery()
 * @method static Builder|AdminCorporate newQuery()
 * @method static Builder|ServiceAuthenticable permission($permissions)
 * @method static Builder|AdminCorporate query()
 * @method static Builder|ServiceAuthenticable role($roles, $guard = null)
 * @method static Builder|AdminCorporate whereAdminCorporateId($value)
 * @method static Builder|AdminCorporate whereCreatedAt($value)
 * @method static Builder|AdminCorporate whereEmail($value)
 * @method static Builder|AdminCorporate whereName($value)
 * @method static Builder|AdminCorporate whereParkId($value)
 * @method static Builder|AdminCorporate wherePassword($value)
 * @method static Builder|AdminCorporate whereRememberToken($value)
 * @method static Builder|AdminCorporate whereUpdatedAt($value)
 * @mixin Eloquent
 * @property-read Company $company
 * @property int|null $franchise_id
 * @property-read int|null $clients_count
 * @property-read Franchise $franchise
 * @property-read int|null $notifications_count
 * @property-read int|null $roles_count
 * @property-read int|null $tokens_count
 * @method static Builder|AdminCorporate whereFranchiseId($value)
 * @property int $company_id
 * @property string|null $surname
 * @property string|null $patronymic
 * @property string|null $phone
 * @method static Builder|ServiceAuthenticable except($values = [])
 * @method static Builder|AdminCorporate whereCompanyId($value)
 * @method static Builder|AdminCorporate wherePatronymic($value)
 * @method static Builder|AdminCorporate wherePhone($value)
 * @method static Builder|AdminCorporate whereSurname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceAuthenticable distance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceAuthenticable distanceCord($latitude, $longitude, $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceAuthenticable geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceAuthenticable disatnceCordsss($latitude, $longitude, $distance)
 * @property-read int|null $permissions_count
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable comparison($geometryColumn, $geometry, $relationship)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable contains($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable cordDistance($latitude, $longitude)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable crosses($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable disjoint($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable distanceExcludingSelf($geometryColumn, $geometry, $distance)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable distanceSphere($geometryColumn, $geometry, $distance)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable distanceSphereExcludingSelf($geometryColumn, $geometry, $distance)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable distanceSphereValue($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable distanceValue($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable doesTouch($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable equals($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable intersects($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable orderByDistance($geometryColumn, $geometry, $direction = 'asc')
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable orderByDistanceSphere($geometryColumn, $geometry, $direction = 'asc')
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable orderBySpatial($geometryColumn, $geometry, $orderFunction, $direction = 'asc')
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable overlaps($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable within($geometryColumn, $polygon)
 * @property-read string $full_name
 */
	class AdminCorporate extends \Eloquent {}
}

namespace Src\Models\Corporate{
/**
 * Src\Models\Corporate\Company
 *
 * @property int $company_id
 * @property int $admin_corporate_id
 * @property string $name
 * @property string $email
 * @property string $actual_address
 * @property string $legal_address
 * @property string $limit
 * @property float $spent
 * @property string|null $details
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|AddressClient[] $addresses
 * @property-read Collection|Client[] $clients
 * @property-read AdminCorporate $corporateAdmin
 * @property-read Collection|CorporateClient[] $corporateClients
 * @property-read Collection|Order[] $orders
 * @method static Builder|Company newModelQuery()
 * @method static Builder|Company newQuery()
 * @method static Builder|Company query()
 * @method static Builder|Company whereActualAddress($value)
 * @method static Builder|Company whereAdminCorporateId($value)
 * @method static Builder|Company whereCompanyId($value)
 * @method static Builder|Company whereCreatedAt($value)
 * @method static Builder|Company whereDetails($value)
 * @method static Builder|Company whereEmail($value)
 * @method static Builder|Company whereLegalAddress($value)
 * @method static Builder|Company whereLimit($value)
 * @method static Builder|Company whereName($value)
 * @method static Builder|Company whereSpent($value)
 * @method static Builder|Company whereUpdatedAt($value)
 * @mixin Eloquent
 * @property int $order_canceled_timeout
 * @property int $period Период времени
 * @property int $frequency Количество отчетов в этот период времени
 * @property string $code Внутренний код компании
 * @property string $contract_start
 * @property string $contract_end
 * @property string $contract_scan
 * @property-read int|null $addresses_count
 * @property-read int|null $clients_count
 * @property-read int|null $corporate_clients_count
 * @property-read int|null $orders_count
 * @property-read Collection|Tariff[] $tariffs
 * @property-read int|null $tariffs_count
 * @method static Builder|Company exclude($columns)
 * @method static Builder|Company whereCode($value)
 * @method static Builder|Company whereContractEnd($value)
 * @method static Builder|Company whereContractScan($value)
 * @method static Builder|Company whereContractStart($value)
 * @method static Builder|Company whereFrequency($value)
 * @method static Builder|Company whereOrderCanceledTimeout($value)
 * @method static Builder|Company wherePeriod($value)
 * @method static Builder|ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @property-read Collection|ClientCall[] $calls
 * @property-read int|null $calls_count
 * @property-read Collection|Order[] $last_orders
 * @property-read int|null $last_orders_count
 * @property-read Collection|CompanyPhone[] $phones
 * @property-read int|null $phones_count
 * @property int $franchise_id
 * @property int $entity_id
 * @property string|null $logo
 * @property-read Collection|AdminCorporate[] $corporateAdmins
 * @property-read int|null $corporate_admins_count
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|Company whereEntityId($value)
 * @method static Builder|Company whereFranchiseId($value)
 * @method static Builder|Company whereLogo($value)
 * @property string $address
 * @method static Builder|Company whereAddress($value)
 * @property-read LegalEntity $entity
 * @property-read Franchise $franchise
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property-read FranchiseTransaction|null $second_side
 * @property-read FranchiseTransaction|null $side
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 */
	class Company extends \Eloquent {}
}

namespace Src\Models\Corporate{
/**
 * Src\Models\Corporate\CompanyPhone
 *
 * @property int $company_phone_id
 * @property int|null $company_id
 * @property string $number
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyPhone newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyPhone newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyPhone query()
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyPhone whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyPhone whereCompanyPhoneId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyPhone whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyPhone whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyPhone whereUpdatedAt($value)
 * @mixin Eloquent
 * @property Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel except($values = [])
 * @method static \Illuminate\Database\Query\Builder|CompanyPhone onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyPhone whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|CompanyPhone withTrashed()
 * @method static \Illuminate\Database\Query\Builder|CompanyPhone withoutTrashed()
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
 */
	class CompanyPhone extends \Eloquent {}
}

namespace Src\Models\Corporate{
/**
 * Class CompanyReport
 *
 * @package Src\Models\Corporate
 * @property int $company_report_id
 * @property int $company_id
 * @property string $excel
 * @property string $path
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Src\Models\Corporate\Company $company
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, int $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel except(array $values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyReport newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyReport newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyReport query()
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyReport whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyReport whereCompanyReportId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyReport whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyReport whereExcel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyReport whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyReport wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyReport whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 */
	class CompanyReport extends \Eloquent {}
}

namespace Src\Models\Corporate{
/**
 * Src\Models\Corporate\CorporateClient
 *
 * @property int $corporate_client_id
 * @property int|null $client_id
 * @property int|null $company_id
 * @property array|null $car_ids
 * @property int|null $allow_weekends
 * @property int|null $allow_order
 * @property int $limit
 * @property float $spent
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Client|null $client
 * @property-read Company|null $company
 * @method static Builder|CorporateClient exclude($columns)
 * @method static Builder|CorporateClient newModelQuery()
 * @method static Builder|CorporateClient newQuery()
 * @method static Builder|CorporateClient query()
 * @method static Builder|CorporateClient whereAllowOrder($value)
 * @method static Builder|CorporateClient whereAllowWeekends($value)
 * @method static Builder|CorporateClient whereCarIds($value)
 * @method static Builder|CorporateClient whereClientId($value)
 * @method static Builder|CorporateClient whereCompanyId($value)
 * @method static Builder|CorporateClient whereCorporateClientId($value)
 * @method static Builder|CorporateClient whereCreatedAt($value)
 * @method static Builder|CorporateClient whereLimit($value)
 * @method static Builder|CorporateClient whereSpent($value)
 * @method static Builder|CorporateClient whereUpdatedAt($value)
 * @mixin Eloquent
 * @method static Builder|ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @property string|null $email
 * @property string|null $patronymic
 * @property array|null $car_classes_ids
 * @property-read Collection|CanceledOrder[] $canceled_orders
 * @property-read int|null $canceled_orders_count
 * @property-read CanceledOrder|null $current_canceled_order
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|CorporateClient whereCarClassesIds($value)
 * @method static Builder|CorporateClient whereEmail($value)
 * @method static Builder|CorporateClient wherePatronymic($value)
 * @property string|null $name
 * @property string|null $surname
 * @method static Builder|CorporateClient whereName($value)
 * @method static Builder|CorporateClient whereSurname($value)
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property-read Collection|ClientAddress[] $client_addresses
 * @property-read int|null $client_addresses_count
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 */
	class CorporateClient extends \Eloquent {}
}

namespace Src\Models\Debt{
/**
 * Class Debt
 *
 * @package Src\Models\Debt
 * @property int $debt_id
 * @property int $debtor_id
 * @property string $debtor_type
 * @property int $type
 * @property string $cost
 * @property string $cost_paid
 * @property bool $closest
 * @property Carbon $created_at
 * @property-read Driver $driver
 * @method static Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, int $distance = 1)
 * @method static Builder|ServiceModel except(array $values = [])
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|Debt newModelQuery()
 * @method static Builder|Debt newQuery()
 * @method static Builder|Debt query()
 * @method static Builder|Debt whereClosest($value)
 * @method static Builder|Debt whereCost($value)
 * @method static Builder|Debt whereCostPaid($value)
 * @method static Builder|Debt whereCreatedAt($value)
 * @method static Builder|Debt whereDebtId($value)
 * @method static Builder|Debt whereDebtorId($value)
 * @method static Builder|Debt whereDebtorType($value)
 * @method static Builder|Debt whereType($value)
 * @mixin Eloquent
 * @property int $firm_paid
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $current_debt
 * @property-read Driver $debt_type
 * @property-read Penalty|null $penalty
 * @method static Builder|Debt whereFirmPaid($value)
 */
	class Debt extends \Eloquent {}
}

namespace Src\Models\Debt{
/**
 * Src\Models\Debt\DebtTypes
 *
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, int $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel except(array $values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static \Illuminate\Database\Eloquent\Builder|DebtTypes newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DebtTypes newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DebtTypes query()
 * @mixin \Eloquent
 * @property int $debt_type_id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|DebtTypes whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DebtTypes whereDebtTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DebtTypes whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DebtTypes whereUpdatedAt($value)
 */
	class DebtTypes extends \Eloquent {}
}

namespace Src\Models\Driver{
/**
 * Class Driver
 *
 * @package Src\Models\Driver
 * @property int $driver_id
 * @property int|null $driver_info_id
 * @property int|null $entity_id
 * @property int|null $car_id
 * @property int|null $type_id
 * @property int|null $subtype_id
 * @property int|null $graphic_id
 * @property int|null $free_days_price
 * @property int|null $busy_days_price
 * @property int|null $current_franchise_id
 * @property int|null $current_status_id
 * @property int $rating_level_id
 * @property mixed|null $options
 * @property float|null $lat
 * @property float|null $lut
 * @property float $mean_assessment
 * @property float $rating
 * @property int|null $logged
 * @property string|null $device
 * @property int $online
 * @property int $is_ready
 * @property string|null $nickname
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $password
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|DriverAddress[] $addresses
 * @property-read int|null $addresses_count
 * @property-read int|null $assessmentables_count
 * @property-read int|null $assessments_count
 * @property-read Collection|OrderFeedback[] $canceled_feedbacks
 * @property-read int|null $canceled_feedbacks_count
 * @property-read Collection|CanceledOrder[] $canceled_orders
 * @property-read int|null $canceled_orders_count
 * @property-read Car|null $car
 * @property-read Collection|\Src\Models\Oauth\Client[] $clients
 * @property-read int|null $clients_count
 * @property-read int|null $clients_road_taxi_view_count
 * @property-read Collection|Order[] $common
 * @property-read int|null $common_count
 * @property-read Collection|OrderFeedback[] $completed_feedbacks
 * @property-read int|null $completed_feedbacks_count
 * @property-read Collection|DriverContract[] $contracts
 * @property-read int|null $contracts_count
 * @property-read Collection|DriverCoordinate[] $coordinates
 * @property-read int|null $coordinates_count
 * @property-read DriverCoordinate|null $current_coordinates
 * @property-read Franchise|null $current_franchise
 * @property-read DriverInfo|null $driver_info
 * @property-read LegalEntity|null $entity
 * @property-read EstimatedRating|null $estimated_rating
 * @property-read Collection|EstimatedRating[] $estimated_ratings
 * @property-read int|null $estimated_ratings_count
 * @property-read Collection|Client[] $favorite
 * @property-read int|null $favorite_count
 * @property-read Collection|Franchise[] $franchisee
 * @property-read int|null $franchisee_count
 * @property-read float|int $distance
 * @property-read DriverGraphic|null $graphic
 * @property-read DriverAddress|null $home_address
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read Token|null $oauth_access_token
 * @property-read Collection|Order[] $current_order
 * @property-read OrderOnWayRoad|null $order_on_way_road
 * @property-read Collection|OrderOnWayRoad[] $order_on_way_roads
 * @property-read int|null $order_on_way_roads_count
 * @property-read Collection|CompletedOrder[] $orders
 * @property-read int|null $orders_count
 * @property-read Park|null $park
 * @property-read DriverRatingLevel $rating_level
 * @property-read int|null $ratings_count
 * @property-read Collection|Role[] $roles
 * @property-read int|null $roles_count
 * @property-read int|null $scheduled_count
 * @property-read Collection|DriverSchedule[] $schedules
 * @property-read int|null $schedules_count
 * @property-read DriverStatus|null $status
 * @property-read DriverSubtype|null $subtype
 * @property-read Collection|Token[] $tokens
 * @property-read int|null $tokens_count
 * @property-read DriverType|null $type
 * @property-read Collection|DriverAddress[] $work_addresses
 * @property-read int|null $work_addresses_count
 * @method static Builder|Driver canceledOrderFeedback()
 * @method static Builder|Driver completedOrderFeedback()
 * @method static Builder|ServiceAuthenticable distance($latitude, $longitude)
 * @method static Builder|ServiceAuthenticable distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceAuthenticable except($values = [])
 * @method static Builder|ServiceAuthenticable geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|Driver newModelQuery()
 * @method static Builder|Driver newQuery()
 * @method static \Illuminate\Database\Query\Builder|Driver onlyTrashed()
 * @method static Builder|ServiceAuthenticable permission($permissions)
 * @method static Builder|Driver query()
 * @method static Builder|ServiceAuthenticable role($roles, $guard = null)
 * @method static Builder|Driver whereBusyDaysPrice($value)
 * @method static Builder|Driver whereCarId($value)
 * @method static Builder|Driver whereCreatedAt($value)
 * @method static Builder|Driver whereCurrentFranchiseId($value)
 * @method static Builder|Driver whereCurrentStatusId($value)
 * @method static Builder|Driver whereDeletedAt($value)
 * @method static Builder|Driver whereDevice($value)
 * @method static Builder|Driver whereDriverId($value)
 * @method static Builder|Driver whereDriverInfoId($value)
 * @method static Builder|Driver whereEmail($value)
 * @method static Builder|Driver whereEntityId($value)
 * @method static Builder|Driver whereFreeDaysPrice($value)
 * @method static Builder|Driver whereGraphicId($value)
 * @method static Builder|Driver whereIsReady($value)
 * @method static Builder|Driver whereLat($value)
 * @method static Builder|Driver whereLogged($value)
 * @method static Builder|Driver whereLut($value)
 * @method static Builder|Driver whereMeanAssessment($value)
 * @method static Builder|Driver whereNickname($value)
 * @method static Builder|Driver whereOnline($value)
 * @method static Builder|Driver whereOptions($value)
 * @method static Builder|Driver wherePassword($value)
 * @method static Builder|Driver wherePhone($value)
 * @method static Builder|Driver whereRating($value)
 * @method static Builder|Driver whereRatingLevelId($value)
 * @method static Builder|Driver whereSubtypeId($value)
 * @method static Builder|Driver whereTypeId($value)
 * @method static Builder|Driver whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Driver withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Driver withoutTrashed()
 * @mixin Eloquent
 * @property-read OrderShippedDriver|null $active_order_shipment
 * @property int|null $azimuth
 * @property-read DriverContract|null $active_contract
 * @property-read Collection|OrderConversationTalk[] $conversation
 * @property-read int|null $conversation_count
 * @property-read Collection|OrderConversationTalk[] $conversation_sender
 * @property-read int|null $conversation_sender_count
 * @method static Builder|ServiceAuthenticable disatnceCordsss($latitude, $longitude, $distance)
 * @method static Builder|Driver whereAzimuth($value)
 * @property-read Collection|OrderConversationTalk[] $conversation_talks
 * @property-read int|null $conversation_talks_count
 * @property-read Collection|Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property array|null $selected_option
 * @property CarClass $selected_class
 * @property-read OrderProcess|null $all_process
 * @property-read OrderProcess|null $process
 * @property-read CarOption $selected_options
 * @property-read Collection|Waybill[] $waybills
 * @property-read int|null $waybills_count
 * @method static Builder|Driver whereSelectedClass($value)
 * @method static Builder|Driver whereSelectedOption($value)
 * @property-read DriverWallet|null $cash
 * @property-read Terminal|null $terminal
 * @property-read Collection|OrderFeedback[] $assessed
 * @property-read int|null $assessed_count
 * @property-read Collection|CarCrash[] $crashes
 * @property-read int|null $crashes_count
 * @property-read Collection|OrderFeedback[] $rater
 * @property-read int|null $rater_count
 * @property-read Collection|CarReport[] $report
 * @property-read int|null $report_count
 * @property-read DriverContract|null $unsigned_contract
 * @property-read DriverCoordinate|null $current_coordinate
 * @property-read FcmClient|null $fcm
 * @property-read Collection|PreOrder[] $preorders
 * @property-read int|null $preorders_count
 * @property-read Collection|Client[] $favorite_client
 * @property-read int|null $favorite_client_count
 * @property-read DriverLock|null $lock
 * @property-read DriverLock|null $locked
 * @property-read Collection|FranchiseTransaction[] $transactions
 * @property-read int|null $transactions_count
 * @property-read Waybill|null $current_waybill
 * @property-read Collection|Debt[] $debts
 * @property-read int|null $debts_count
 * @property-read Waybill|null $last_active_waybill
 * @property-read Collection|FranchiseTransaction[] $side
 * @property-read int|null $side_count
 * @property-read CompletedOrder|null $completed_order
 * @property-read Collection|Waybill[] $last_active_waybills
 * @property-read int|null $last_active_waybills_count
 * @property-read Collection|Waybill[] $active_waybills
 * @property-read int|null $active_waybills_count
 * @property-read Collection|FranchiseTransaction[] $last_side
 * @property-read int|null $last_side_count
 * @method static Builder|ServiceAuthenticable cordDistance($latitude, $longitude)
 * @property-read Collection|CompletedOrder[] $completed_orders
 * @property-read int|null $completed_orders_count
 * @property-read Collection|OrderCorporate[] $corporate_orders
 * @property-read int|null $corporate_orders_count
 * @property-read CompletedOrder|null $last_completed_order
 * @property-read OrderCorporate|null $last_corporate_order
 * @property-read DriverLock|null $lockes
 * @property-read Waybill|null $current_active_waybill
 * @property-read Collection|Debt[] $to_debts
 * @property-read int|null $to_debts_count
 * @property-read Collection|OrderCommon[] $common_orders
 * @property-read int|null $common_orders_count
 * @property-read Collection|OrderCommon[] $common_preorders
 * @property-read int|null $common_preorders_count
 * @property-read OrderInProcessRoad|null $order_in_process_road
 * @property-read Collection|OrderInProcessRoad[] $order_in_process_roads
 * @property-read int|null $order_in_process_roads_count
 */
	class Driver extends \Eloquent {}
}

namespace Src\Models\Driver{
/**
 * Class DriverAddress
 *
 * @package Src\Models\Driver
 * @property int $driver_address_id
 * @property int|null $driver_id
 * @property string|null $target
 * @property string|null $address
 * @property mixed|null $coordinate
 * @property int|null $active
 * @property string|null $created_at
 * @property-read Driver|null $driver
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|DriverAddress newModelQuery()
 * @method static Builder|DriverAddress newQuery()
 * @method static Builder|DriverAddress query()
 * @method static Builder|DriverAddress whereActive($value)
 * @method static Builder|DriverAddress whereAddress($value)
 * @method static Builder|DriverAddress whereCoordinate($value)
 * @method static Builder|DriverAddress whereCreatedAt($value)
 * @method static Builder|DriverAddress whereDriverAddressId($value)
 * @method static Builder|DriverAddress whereDriverId($value)
 * @method static Builder|DriverAddress whereTarget($value)
 * @mixin Eloquent
 * @property array|null $road
 * @method static Builder|DriverAddress whereRoad($value)
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property int|null $target_duration
 * @property int|null $target_distance
 * @property array|null $target_road
 * @method static Builder|DriverAddress whereTargetDistance($value)
 * @method static Builder|DriverAddress whereTargetDuration($value)
 * @method static Builder|DriverAddress whereTargetRoad($value)
 * @property float $lat
 * @property float $lut
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
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|DriverAddress whereLat($value)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|DriverAddress whereLut($value)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel within($geometryColumn, $polygon)
 */
	class DriverAddress extends \Eloquent {}
}

namespace Src\Models\Driver{
/**
 * Src\Models\Driver\DriverCandidate
 *
 * @property int $id
 * @property int|null $tutor_id
 * @property string $name
 * @property string $surname
 * @property string $phone
 * @property string|null $email
 * @property string $driver_license_info
 * @property string $driver_license_category
 * @property string $passport_info
 * @property string $learn_status
 * @property string $experience
 * @property int $raiting
 * @property string $image
 * @property int $penalty
 * @property int $driver_license_revocation
 * @property string $registration_date
 * @property string|null $learn_start
 * @property string|null $learn_end
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|DriverCandidate newModelQuery()
 * @method static Builder|DriverCandidate newQuery()
 * @method static Builder|DriverCandidate query()
 * @method static Builder|DriverCandidate whereCreatedAt($value)
 * @method static Builder|DriverCandidate whereDriverLicenseCategory($value)
 * @method static Builder|DriverCandidate whereDriverLicenseInfo($value)
 * @method static Builder|DriverCandidate whereDriverLicenseRevocation($value)
 * @method static Builder|DriverCandidate whereEmail($value)
 * @method static Builder|DriverCandidate whereExperience($value)
 * @method static Builder|DriverCandidate whereId($value)
 * @method static Builder|DriverCandidate whereImage($value)
 * @method static Builder|DriverCandidate whereLearnEnd($value)
 * @method static Builder|DriverCandidate whereLearnStart($value)
 * @method static Builder|DriverCandidate whereLearnStatus($value)
 * @method static Builder|DriverCandidate whereName($value)
 * @method static Builder|DriverCandidate wherePassportInfo($value)
 * @method static Builder|DriverCandidate wherePenalty($value)
 * @method static Builder|DriverCandidate wherePhone($value)
 * @method static Builder|DriverCandidate whereRaiting($value)
 * @method static Builder|DriverCandidate whereRegistrationDate($value)
 * @method static Builder|DriverCandidate whereSurname($value)
 * @method static Builder|DriverCandidate whereTutorId($value)
 * @method static Builder|DriverCandidate whereUpdatedAt($value)
 * @mixin Eloquent
 * @property int $driver_candidate_id
 * @property string|null $deleted_at
 * @property-read Driver $driver
 * @property-read DriverInfo $info
 * @property-read SystemWorker|null $tutor
 * @method static Builder|DriverCandidate whereDeletedAt($value)
 * @method static Builder|DriverCandidate whereDriverCandidateId($value)
 * @property int|null $driver_info_id
 * @property int|null $franchise_id
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|DriverCandidate onlyTrashed()
 * @method static bool|null restore()
 * @method static Builder|DriverCandidate whereDriverInfoId($value)
 * @method static Builder|DriverCandidate whereFranchiseId($value)
 * @method static \Illuminate\Database\Query\Builder|DriverCandidate withTrashed()
 * @method static \Illuminate\Database\Query\Builder|DriverCandidate withoutTrashed()
 * @method static Builder|ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property-read LearnStatus $learnStatus
 * @property int $learn_status_id
 * @method static Builder|DriverCandidate whereLearnStatusId($value)
 * @property-read DriverContract|null $driver_active_contract
 * @property-read DriverContract|null $driver_contract
 * @method static Builder|ServiceModel cordDistance($latitude, $longitude)
 */
	class DriverCandidate extends \Eloquent {}
}

namespace Src\Models\Driver{
/**
 * Src\Models\Driver\DriverContract
 *
 * @property int $driver_contract_id
 * @property int $driver_id
 * @property int $driver_type_id
 * @property int $driver_graphic_id
 * @property int $car_id
 * @property string $signing_day
 * @property string $expiration_day
 * @property string $work_start_day
 * @property int $duration
 * @property int $active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Car $car
 * @property-read Driver $driver
 * @property-read DriverGraphic $graphic
 * @property-read Collection|DriverSchedule[] $schedules
 * @property-read int|null $schedules_count
 * @property-read DriverType $type
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|DriverContract newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DriverContract newQuery()
 * @method static Builder|DriverContract onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|DriverContract query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|DriverContract whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DriverContract whereCarId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DriverContract whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DriverContract whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DriverContract whereDriverContractId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DriverContract whereDriverGraphicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DriverContract whereDriverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DriverContract whereDriverTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DriverContract whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DriverContract whereExpirationDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DriverContract whereSigningDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DriverContract whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DriverContract whereWorkStartDay($value)
 * @method static Builder|DriverContract withTrashed()
 * @method static Builder|DriverContract withoutTrashed()
 * @mixin Eloquent
 * @property int $driver_subtype_id
 * @property int $entity_id
 * @property int $signed
 * @property-read LegalEntity $entity
 * @property-read DriverSubtype $subtype
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static \Illuminate\Database\Eloquent\Builder|DriverContract whereDriverSubtypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DriverContract whereEntityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DriverContract whereSigned($value)
 * @property int|null $free_days_price
 * @property int|null $busy_days_price
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel except($values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|DriverContract whereBusyDaysPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DriverContract whereFreeDaysPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @property string|null $amount_paid
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static \Illuminate\Database\Eloquent\Builder|DriverContract whereAmountPaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DriverContract whereCashlessPercent($value)
 * @property string|null $scan
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|DriverContract whereScan($value)
 */
	class DriverContract extends \Eloquent {}
}

namespace Src\Models\Driver{
/**
 * Src\Models\Driver\DriverContractInfo
 *
 * @method static \Illuminate\Database\Eloquent\Builder|DriverContractInfo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DriverContractInfo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DriverContractInfo query()
 * @mixin \Eloquent
 */
	class DriverContractInfo extends \Eloquent {}
}

namespace Src\Models\Driver{
/**
 * Class DriverCoordinate
 *
 * @package Src\Models
 * @property int $driver_coordinate_id
 * @property int|null $driver_id
 * @property string|null $current_latitude
 * @property string|null $current_longitude
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Driver $driver
 * @method static Builder|DriverCoordinate newModelQuery()
 * @method static Builder|DriverCoordinate newQuery()
 * @method static Builder|DriverCoordinate query()
 * @method static Builder|DriverCoordinate whereCreatedAt($value)
 * @method static Builder|DriverCoordinate whereCurrentLatitude($value)
 * @method static Builder|DriverCoordinate whereCurrentLongitude($value)
 * @method static Builder|DriverCoordinate whereDriverCoordinateId($value)
 * @method static Builder|DriverCoordinate whereDriverId($value)
 * @method static Builder|DriverCoordinate whereUpdatedAt($value)
 * @mixin Eloquent
 * @property string|null $coordinates
 * @method static Builder|DriverCoordinate whereCoordinates($value)
 * @property Carbon $date
 * @method static Builder|DriverCoordinate whereDate($value)
 * @method static Builder|ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 */
	class DriverCoordinate extends \Eloquent {}
}

namespace Src\Models\Driver{
/**
 * Class DriverGraphic
 *
 * @package Src\Models
 * @property int $driver_graphic_id
 * @property string|null $name
 * @property string|null $description
 * @property int|null $working_days_count
 * @property int|null $weekend_days_count
 * @property-read Collection|Driver[] $drivers
 * @method static Builder|DriverGraphic newModelQuery()
 * @method static Builder|DriverGraphic newQuery()
 * @method static Builder|DriverGraphic query()
 * @method static Builder|DriverGraphic whereDescription($value)
 * @method static Builder|DriverGraphic whereDriverGraphicId($value)
 * @method static Builder|DriverGraphic whereName($value)
 * @method static Builder|DriverGraphic whereWeekendDaysCount($value)
 * @method static Builder|DriverGraphic whereWorkingDaysCount($value)
 * @mixin Eloquent
 * @property int|null $type
 * @method static Builder|DriverGraphic whereType($value)
 * @property mixed $week
 * @property-read int|null $drivers_count
 * @method static Builder|DriverGraphic whereWeek($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ServiceEntity\Models\ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static \Illuminate\Database\Eloquent\Builder|\ServiceEntity\Models\ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static \Illuminate\Database\Eloquent\Builder|\ServiceEntity\Models\ServiceModel except($values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
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
 */
	class DriverGraphic extends \Eloquent {}
}

namespace Src\Models\Driver{
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
	class DriverInfo extends \Eloquent {}
}

namespace Src\Models\Driver{
/**
 * Src\Models\Driver\DriverLicenseType
 *
 * @property int $driver_license_type_id
 * @property string $name
 * @property string $type
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|DriverLicenseType newModelQuery()
 * @method static Builder|DriverLicenseType newQuery()
 * @method static Builder|DriverLicenseType query()
 * @method static Builder|DriverLicenseType whereCreatedAt($value)
 * @method static Builder|DriverLicenseType whereDriverLicenseTypeId($value)
 * @method static Builder|DriverLicenseType whereName($value)
 * @method static Builder|DriverLicenseType whereType($value)
 * @method static Builder|DriverLicenseType whereUpdatedAt($value)
 * @mixin Eloquent
 * @property int $driver_info_license_type_id
 * @property int $driver_info_id
 * @property int $license_type_id
 * @method static Builder|DriverInfoLicenseType whereDriverInfoId($value)
 * @method static Builder|DriverInfoLicenseType whereDriverInfoLicenseTypeId($value)
 * @method static Builder|DriverInfoLicenseType whereLicenseTypeId($value)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel comparison($geometryColumn, $geometry, $relationship)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel contains($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel crosses($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel disjoint($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel distance($geometryColumn, $geometry, $distance)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, int $distance = 1)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel distanceExcludingSelf($geometryColumn, $geometry, $distance)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel distanceSphere($geometryColumn, $geometry, $distance)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel distanceSphereExcludingSelf($geometryColumn, $geometry, $distance)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel distanceSphereValue($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel distanceValue($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel doesTouch($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel equals($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel except(array $values = [])
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel intersects($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel orderByDistance($geometryColumn, $geometry, $direction = 'asc')
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel orderByDistanceSphere($geometryColumn, $geometry, $direction = 'asc')
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel orderBySpatial($geometryColumn, $geometry, $orderFunction, $direction = 'asc')
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel overlaps($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel within($geometryColumn, $polygon)
 */
	class DriverInfoLicenseType extends \Eloquent {}
}

namespace Src\Models\Driver{
/**
 * Src\Models\Driver\DriverLicenseType
 *
 * @property int $driver_license_type_id
 * @property string $name
 * @property string $type
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|DriverLicenseType newModelQuery()
 * @method static Builder|DriverLicenseType newQuery()
 * @method static Builder|DriverLicenseType query()
 * @method static Builder|DriverLicenseType whereCreatedAt($value)
 * @method static Builder|DriverLicenseType whereDriverLicenseTypeId($value)
 * @method static Builder|DriverLicenseType whereName($value)
 * @method static Builder|DriverLicenseType whereType($value)
 * @method static Builder|DriverLicenseType whereUpdatedAt($value)
 * @mixin Eloquent
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel comparison($geometryColumn, $geometry, $relationship)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel contains($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel crosses($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel disjoint($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel distance($geometryColumn, $geometry, $distance)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, int $distance = 1)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel distanceExcludingSelf($geometryColumn, $geometry, $distance)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel distanceSphere($geometryColumn, $geometry, $distance)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel distanceSphereExcludingSelf($geometryColumn, $geometry, $distance)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel distanceSphereValue($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel distanceValue($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel doesTouch($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel equals($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel except(array $values = [])
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel intersects($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel orderByDistance($geometryColumn, $geometry, $direction = 'asc')
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel orderByDistanceSphere($geometryColumn, $geometry, $direction = 'asc')
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel orderBySpatial($geometryColumn, $geometry, $orderFunction, $direction = 'asc')
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel overlaps($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel within($geometryColumn, $polygon)
 */
	class DriverLicenseType extends \Eloquent {}
}

namespace Src\Models\Driver{
/**
 * Class DriverLock
 *
 * @package Src\Models\Driver
 * @property int $driver_lock_id
 * @property int $driver_id
 * @property bool $locked
 * @property int $lock_count
 * @property \Illuminate\Support\Carbon|null $start
 * @property \Illuminate\Support\Carbon|null $end
 * @property \Illuminate\Support\Carbon $created_at
 * @property-read \Src\Models\Driver\Driver $driver
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, int $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel except(array $values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static \Illuminate\Database\Eloquent\Builder|DriverLock newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DriverLock newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DriverLock query()
 * @method static \Illuminate\Database\Eloquent\Builder|DriverLock whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DriverLock whereDriverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DriverLock whereDriverLockId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DriverLock whereEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DriverLock whereLockCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DriverLock whereLocked($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DriverLock whereStart($value)
 * @mixin \Eloquent
 */
	class DriverLock extends \Eloquent {}
}

namespace Src\Models\Driver{
/**
 * Class DriverRatingLevel
 *
 * @package Src\Models\Driver
 * @property int $driver_rating_level_id
 * @property string $level
 * @property int|null $from
 * @property int|null $before
 * @property-read \Illuminate\Database\Eloquent\Collection|\Src\Models\Driver\Driver[] $drivers
 * @property-read int|null $drivers_count
 * @method static \Illuminate\Database\Eloquent\Builder|\ServiceEntity\Models\ServiceModel except($values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Driver\DriverRatingLevel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Driver\DriverRatingLevel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Driver\DriverRatingLevel query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Driver\DriverRatingLevel whereBefore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Driver\DriverRatingLevel whereDriverRatingLevelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Driver\DriverRatingLevel whereFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Driver\DriverRatingLevel whereLevel($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
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
 */
	class DriverRatingLevel extends \Eloquent {}
}

namespace Src\Models\Driver{
/**
 * Src\Models\Driver\DriverSchedule
 *
 * @property int $driver_schedule_id
 * @property int $driver_id
 * @property int $driver_contract_id
 * @property int $working
 * @property int $accident
 * @property string $date
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read DriverContract $contract
 * @property-read Driver $driver
 * @method static Builder|DriverSchedule newModelQuery()
 * @method static Builder|DriverSchedule newQuery()
 * @method static Builder|DriverSchedule query()
 * @method static Builder|DriverSchedule whereAccident($value)
 * @method static Builder|DriverSchedule whereCreatedAt($value)
 * @method static Builder|DriverSchedule whereDate($value)
 * @method static Builder|DriverSchedule whereDeletedAt($value)
 * @method static Builder|DriverSchedule whereDriverContractId($value)
 * @method static Builder|DriverSchedule whereDriverId($value)
 * @method static Builder|DriverSchedule whereDriverScheduleId($value)
 * @method static Builder|DriverSchedule whereUpdatedAt($value)
 * @method static Builder|DriverSchedule whereWorking($value)
 * @mixin Eloquent
 * @method static Builder|ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property int $day
 * @property int $month
 * @property int $year
 * @method static Builder|DriverSchedule whereDay($value)
 * @method static Builder|DriverSchedule whereMonth($value)
 * @method static Builder|DriverSchedule whereYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 */
	class DriverSchedule extends \Eloquent {}
}

namespace Src\Models\Driver{
/**
 * Class DriverStatus
 *
 * @package Src\Models\Driver
 * @property int $driver_status_id
 * @property string $name
 * @property int $value
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Driver[] $drivers
 * @property-read int|null $drivers_count
 * @method static Builder|DriverStatus newModelQuery()
 * @method static Builder|DriverStatus newQuery()
 * @method static Builder|DriverStatus query()
 * @method static Builder|ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|DriverStatus whereCreatedAt($value)
 * @method static Builder|DriverStatus whereDriverStatusId($value)
 * @method static Builder|DriverStatus whereName($value)
 * @method static Builder|DriverStatus whereUpdatedAt($value)
 * @method static Builder|DriverStatus whereValue($value)
 * @mixin Eloquent
 * @property int $status
 * @method static Builder|DriverStatus whereStatus($value)
 * @property string $text
 * @property string $color
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|DriverStatus whereColor($value)
 * @method static Builder|DriverStatus whereText($value)
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static Builder|ServiceModel cordDistance($latitude, $longitude)
 */
	class DriverStatus extends \Eloquent {}
}

namespace Src\Models\Driver{
/**
 * Src\Models\Driver\DriverSubtype
 *
 * @property int $driver_subtype_id
 * @property int $driver_type_id
 * @property string $name
 * @property string $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Driver\DriverSubtype newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Driver\DriverSubtype newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Driver\DriverSubtype query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Driver\DriverSubtype whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Driver\DriverSubtype whereDriverSubtypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Driver\DriverSubtype whereDriverTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Driver\DriverSubtype whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Driver\DriverSubtype whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Driver\DriverSubtype whereValue($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\ServiceEntity\Models\ServiceModel except($values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
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
 */
	class DriverSubtype extends \Eloquent {}
}

namespace Src\Models\Driver{
/**
 * Class DriverType
 *
 * @package Src\Models
 * @property int $driver_type_id
 * @property string|null $type
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Driver[] $drivers
 * @method static Builder|DriverType newModelQuery()
 * @method static Builder|DriverType newQuery()
 * @method static Builder|DriverType query()
 * @method static Builder|DriverType whereCreatedAt($value)
 * @method static Builder|DriverType whereDescription($value)
 * @method static Builder|DriverType whereDriverTypeId($value)
 * @method static Builder|DriverType whereType($value)
 * @method static Builder|DriverType whereUpdatedAt($value)
 * @mixin Eloquent
 * @property string $image
 * @property-read int|null $drivers_count
 * @property-read Collection|DriverSubtype[] $subtypes
 * @property-read int|null $subtypes_count
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|DriverType whereImage($value)
 * @property string $name
 * @method static Builder|DriverType whereName($value)
 * @property-read Collection|DriverTypeOptional[] $franchise_options
 * @property-read int|null $franchise_options_count
 * @property bool|null $worked
 * @method static Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static Builder|DriverType whereWorked($value)
 */
	class DriverType extends \Eloquent {}
}

namespace Src\Models\Driver{
/**
 * Class DriverTypeOption
 *
 * @package Src\Models\Driver
 * @property int $driver_type_option_id
 * @property int $franchise_id
 * @property int $driver_type_id
 * @property int $driver_type_optional_id
 * @property int|null $percent_value
 * @property Carbon $created_at
 * @property-read DriverTypeOptional $option
 * @method static Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, int $distance = 1)
 * @method static Builder|ServiceModel except(array $values = [])
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|DriverTypeOption newModelQuery()
 * @method static Builder|DriverTypeOption newQuery()
 * @method static Builder|DriverTypeOption query()
 * @method static Builder|DriverTypeOption whereCreatedAt($value)
 * @method static Builder|DriverTypeOption whereDriverTypeId($value)
 * @method static Builder|DriverTypeOption whereDriverTypeOptionId($value)
 * @method static Builder|DriverTypeOption whereDriverTypeOptionalId($value)
 * @method static Builder|DriverTypeOption whereFranchiseId($value)
 * @method static Builder|DriverTypeOption wherePercentValue($value)
 * @mixin Eloquent
 */
	class DriverTypeOption extends \Eloquent {}
}

namespace Src\Models\Driver{
/**
 * Src\Models\Driver\DriverTypeOptional
 *
 * @property int $driver_type_optional_id
 * @property string $name
 * @property string $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|DriverTypeOptional newModelQuery()
 * @method static Builder|DriverTypeOptional newQuery()
 * @method static Builder|DriverTypeOptional query()
 * @method static Builder|DriverTypeOptional whereCreatedAt($value)
 * @method static Builder|DriverTypeOptional whereDescription($value)
 * @method static Builder|DriverTypeOptional whereDriverTypeOptionalId($value)
 * @method static Builder|DriverTypeOptional whereName($value)
 * @method static Builder|DriverTypeOptional whereUpdatedAt($value)
 * @mixin Eloquent
 * @method static Builder|ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property bool $valued
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static Builder|DriverTypeOptional whereValued($value)
 */
	class DriverTypeOptional extends \Eloquent {}
}

namespace Src\Models\Driver{
/**
 * Class DriverWallet
 *
 * @package Src\Models\Driver
 * @property int $driver_cash_id
 * @property int $driver_id
 * @property int $cash_type
 * @property float $transaction_cash
 * @property float $balance
 * @property float $deposit
 * @property float $amount_paid
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Driver $driver
 * @property mixed debt
 * @property mixed min_repayment
 * @property mixed maturity
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|DriverWallet newModelQuery()
 * @method static Builder|DriverWallet newQuery()
 * @method static Builder|DriverWallet query()
 * @method static Builder|DriverWallet whereAmountPaid($value)
 * @method static Builder|DriverWallet whereBalance($value)
 * @method static Builder|DriverWallet whereCashType($value)
 * @method static Builder|DriverWallet whereCreatedAt($value)
 * @method static Builder|DriverWallet whereDeposit($value)
 * @method static Builder|DriverWallet whereDriverCashId($value)
 * @method static Builder|DriverWallet whereDriverId($value)
 * @method static Builder|DriverWallet whereTransactionCash($value)
 * @method static Builder|DriverWallet whereUpdatedAt($value)
 * @mixin Eloquent
 * @property int $driver_wallet_id
 * @property int|null $repayment_id
 * @property string|null $min_repayment_waybill
 * @property-read DebtRepayment|null $repayment
 * @property-read FranchiseTransaction|null $transaction
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static Builder|DriverWallet whereDebt($value)
 * @method static Builder|DriverWallet whereDriverWalletId($value)
 * @method static Builder|DriverWallet whereMaturity($value)
 * @method static Builder|DriverWallet whereMinRepayment($value)
 * @method static Builder|DriverWallet whereMinRepaymentWaybill($value)
 * @method static Builder|DriverWallet whereRepaymentId($value)
 * @property string|null $min_repayment
 * @property float|null $debt
 * @property int|null $maturity
 * @property int $is_paid
 * @method static Builder|DriverWallet whereIsPaid($value)
 */
	class DriverWallet extends \Eloquent {}
}

namespace Src\Models\Driver{
/**
 * Src\Models\Driver\ExternalBoard
 *
 * @property int $external_board_id
 * @property int $key_id
 * @property string $name
 * @property int $passed_count
 * @property int $completed_count
 * @property mixed $oauth_payload
 * @property \Illuminate\Support\Carbon $created_at
 * @property-read ApiKey $key
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, int $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel except(array $values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalBoard newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalBoard newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalBoard query()
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalBoard whereCompletedCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalBoard whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalBoard whereExternalBoardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalBoard whereKeyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalBoard whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalBoard whereOauthPayload($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalBoard wherePassedCount($value)
 * @mixin \Eloquent
 */
	class ExternalBoard extends \Eloquent {}
}

namespace Src\Models\Driver{
/**
 * Src\Models\Driver\FranchiseDriver
 *
 * @property int $driver_franchisee_id
 * @property int|null $franchise_id
 * @property int|null $driver_id
 * @property int|null $type_id
 * @property int|null $graphic_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read DriverGraphic|null $graphic
 * @method static Builder|FranchiseDriver newModelQuery()
 * @method static Builder|FranchiseDriver newQuery()
 * @method static Builder|FranchiseDriver query()
 * @method static Builder|FranchiseDriver whereCreatedAt($value)
 * @method static Builder|FranchiseDriver whereDriverFranchiseeId($value)
 * @method static Builder|FranchiseDriver whereDriverId($value)
 * @method static Builder|FranchiseDriver whereFranchiseId($value)
 * @method static Builder|FranchiseDriver whereGraphicId($value)
 * @method static Builder|FranchiseDriver whereTypeId($value)
 * @method static Builder|FranchiseDriver whereUpdatedAt($value)
 * @mixin Eloquent
 */
	class FranchiseDriver extends \Eloquent {}
}

namespace Src\Models\Driver{
/**
 * Src\Models\Driver\LearnStatus
 *
 * @method static \Illuminate\Database\Eloquent\Builder|LearnStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LearnStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LearnStatus query()
 * @mixin \Eloquent
 * @property int $learn_status_id
 * @property string $value
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|LearnStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LearnStatus whereLearnStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LearnStatus whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LearnStatus whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LearnStatus whereValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, int $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel except(array $values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 */
	class LearnStatus extends \Eloquent {}
}

namespace Src\Models\Franchise{
/**
 * Class Franchise
 *
 * @package Src\Models\Franchise
 * @property int $franchise_id
 * @property int|null $creator_admin_id
 * @property int|null $owner_admin_id
 * @property string|null $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Image $logo
 * @property-read Collection|Module[] $modules
 * @property-read SystemWorker $owner
 * @property-read Collection|Park[] $parks
 * @property-read SuperAdmin|null $superAdminCreator
 * @property-read Collection|SystemWorker[] $systemWorkers
 * @method static Builder|Franchise newModelQuery()
 * @method static Builder|Franchise newQuery()
 * @method static Builder|Franchise query()
 * @method static Builder|Franchise whereCreatedAt($value)
 * @method static Builder|Franchise whereCreatorAdminId($value)
 * @method static Builder|Franchise whereFranchiseId($value)
 * @method static Builder|Franchise whereName($value)
 * @method static Builder|Franchise whereOwnerAdminId($value)
 * @method static Builder|Franchise whereUpdatedAt($value)
 * @mixin Eloquent
 * @property string $owner_name
 * @property string $owner_email
 * @property-read Collection|Driver[] $drivers
 * @property-read Collection|Region[] $region
 * @property-read Collection|SystemWorker[] $system_workers
 * @method static Builder|Franchise whereOwnerEmail($value)
 * @method static Builder|Franchise whereOwnerName($value)
 * @property string|null $company_type
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $tax_code
 * @property string|null $contract_exp_date
 * @property string|null $contract_code
 * @property string|null $contract_scan
 * @property string|null $director
 * @property Carbon|null $deleted_at
 * @property-read Collection|SystemWorker[] $admins
 * @property-read int|null $admins_count
 * @property-read Collection|Company[] $companies
 * @property-read int|null $companies_count
 * @property-read Collection|AdminCorporate[] $corporateAdmins
 * @property-read int|null $corporate_admins_count
 * @property-read int|null $drivers_count
 * @property-read Collection|FranchiseModule[] $franchise_module
 * @property-read int|null $franchise_module_count
 * @property-read int|null $modules_count
 * @property-read Collection|Order[] $orders
 * @property-read int|null $orders_count
 * @property-read int|null $parks_count
 * @property-read Collection|Region[] $regions
 * @property-read int|null $regions_count
 * @property-read int|null $system_workers_count
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|Franchise onlyTrashed()
 * @method static bool|null restore()
 * @method static Builder|Franchise whereCompanyType($value)
 * @method static Builder|Franchise whereContractCode($value)
 * @method static Builder|Franchise whereContractExpDate($value)
 * @method static Builder|Franchise whereContractScan($value)
 * @method static Builder|Franchise whereDeletedAt($value)
 * @method static Builder|Franchise whereDirector($value)
 * @method static Builder|Franchise whereEmail($value)
 * @method static Builder|Franchise whereLogo($value)
 * @method static Builder|Franchise wherePhone($value)
 * @method static Builder|Franchise whereTaxCode($value)
 * @method static \Illuminate\Database\Query\Builder|Franchise withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Franchise withoutTrashed()
 * @property int $entity_id
 * @property int $country_id
 * @property int $region_id
 * @property int $city_id
 * @property string $address
 * @property string $zip_code
 * @property string $director_name
 * @property string $director_surname
 * @property string $director_patronymic
 * @property-read LegalEntity $entity
 * @property-read City $reg_city
 * @method static Builder|ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|Franchise whereAddress($value)
 * @method static Builder|Franchise whereCityId($value)
 * @method static Builder|Franchise whereCountryId($value)
 * @method static Builder|Franchise whereDirectorName($value)
 * @method static Builder|Franchise whereDirectorPatronymic($value)
 * @method static Builder|Franchise whereDirectorSurname($value)
 * @method static Builder|Franchise whereEntityId($value)
 * @method static Builder|Franchise whereRegionId($value)
 * @method static Builder|Franchise whereZipCode($value)
 * @property-read Collection|FranchisePhone[] $phones
 * @property-read int|null $phones_count
 * @property-read Collection|City[] $cities
 * @property-read int|null $cities_count
 * @property-read Collection|LegalEntity[] $entities
 * @property-read int|null $entities_count
 * @method static Builder|ServiceModel except($values = [])
 * @property-read Collection|SystemWorker[] $dispatchers
 * @property-read int|null $dispatchers_count
 * @property-read Country $country
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property-read Collection|FranchiseRegion[] $franchise_region
 * @property-read int|null $franchise_region_count
 * @property-read Collection|FranchiseModule[] $module_role_ids
 * @property-read int|null $module_role_ids_count
 * @property-read Collection|LegalEntity[] $related_entities
 * @property-read int|null $related_entities_count
 * @property-read Collection|FranchiseCity[] $franchise_cities
 * @property-read int|null $franchise_cities_count
 * @property-read Collection|FranchiseModule[] $franchise_modules
 * @property-read int|null $franchise_modules_count
 * @property-read Collection|FranchiseRegion[] $franchise_regions
 * @property-read int|null $franchise_regions_count
 * @property-read Collection|FranchiseRole[] $franchise_roles
 * @property-read int|null $franchise_roles_count
 * @property-read Collection|FranchiseRegion[] $region_city_ids
 * @property-read int|null $region_city_ids_count
 * @property-read Collection|Role[] $roles
 * @property-read int|null $roles_count
 * @property-read Collection|SystemWorker[] $parkManagers
 * @property-read int|null $park_managers_count
 * @property-read FranchiseOption|null $option
 * @property string|null $text
 * @method static Builder|Franchise whereText($value)
 * @property-read int|null $order_bils_count
 * @property-read Collection|FranchiseTransaction[] $order_bils
 * @method static Builder|ServiceModel cordDistance($latitude, $longitude)
 */
	class Franchise extends \Eloquent {}
}

namespace Src\Models\Franchise{
/**
 * Src\Models\Franchise\FranchiseCity
 *
 * @property int $franchise_city_id
 * @property int $franchise_region_id
 * @property int $franchise_id
 * @property int $city_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|FranchiseCity newModelQuery()
 * @method static Builder|FranchiseCity newQuery()
 * @method static Builder|FranchiseCity query()
 * @method static Builder|FranchiseCity whereCityId($value)
 * @method static Builder|FranchiseCity whereCreatedAt($value)
 * @method static Builder|FranchiseCity whereFranchiseCityId($value)
 * @method static Builder|FranchiseCity whereFranchiseId($value)
 * @method static Builder|FranchiseCity whereFranchiseRegionId($value)
 * @method static Builder|FranchiseCity whereUpdatedAt($value)
 * @mixin Eloquent
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
 */
	class FranchiseCity extends \Eloquent {}
}

namespace Src\Models\Franchise{
/**
 * Class FranchiseEntity
 *
 * @package Src\Models\Franchise
 * @property int $franchise_entity_id
 * @property int $entity_id
 * @property int $franchise_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|FranchiseEntity newModelQuery()
 * @method static Builder|FranchiseEntity newQuery()
 * @method static Builder|FranchiseEntity query()
 * @method static Builder|FranchiseEntity whereCreatedAt($value)
 * @method static Builder|FranchiseEntity whereEntityId($value)
 * @method static Builder|FranchiseEntity whereFranchiseEntityId($value)
 * @method static Builder|FranchiseEntity whereFranchiseId($value)
 * @method static Builder|FranchiseEntity whereUpdatedAt($value)
 * @mixin Eloquent
 * @property int $legal_entity_id
 * @method static Builder|FranchiseEntity whereLegalEntityId($value)
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
 */
	class FranchiseEntity extends \Eloquent {}
}

namespace Src\Models\Franchise{
/**
 * Class FranchiseModule
 *
 * @package Src\Models\Franchise
 * @property int $franchise_module_id
 * @property int|null $franchise_id
 * @property int|null $module_id
 * @property array|null $role_ids
 * @property-read Collection|Role[] $roles
 * @method static Builder|FranchiseModule newModelQuery()
 * @method static Builder|FranchiseModule newQuery()
 * @method static Builder|FranchiseModule query()
 * @method static Builder|FranchiseModule whereFranchiseId($value)
 * @method static Builder|FranchiseModule whereFranchiseeModuleId($value)
 * @method static Builder|FranchiseModule whereModuleId($value)
 * @method static Builder|FranchiseModule whereRoleIds($value)
 * @mixin Eloquent
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|FranchiseModule whereCreatedAt($value)
 * @method static Builder|FranchiseModule whereUpdatedAt($value)
 * @method static Builder|ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property-read Collection|FranchiseRole[] $franchise_roles
 * @property-read int|null $franchise_roles_count
 * @property-read Module|null $module
 * @property-read int|null $roles_count
 * @method static Builder|FranchiseModule whereFranchiseModuleId($value)
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
 */
	class FranchiseModule extends \Eloquent {}
}

namespace Src\Models\Franchise{
/**
 * Class FranchiseOption
 *
 * @package Src\Models\Franchise
 * @property int $franchise_option_id
 * @property int $franchise_id
 * @property int $order_pending_time SECONDS
 * @property array|null $default_assessment
 * @property array|null $default_rating
 * @property string $order_cancel_before Разрешить отменять заказ до
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Franchise $franchise
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, int $distance = 1)
 * @method static Builder|ServiceModel except(array $values = [])
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|FranchiseOption newModelQuery()
 * @method static Builder|FranchiseOption newQuery()
 * @method static Builder|FranchiseOption query()
 * @method static Builder|FranchiseOption whereCreatedAt($value)
 * @method static Builder|FranchiseOption whereDefaultAssessment($value)
 * @method static Builder|FranchiseOption whereDefaultRating($value)
 * @method static Builder|FranchiseOption whereFranchiseId($value)
 * @method static Builder|FranchiseOption whereFranchiseOptionId($value)
 * @method static Builder|FranchiseOption whereOrderCancelBefore($value)
 * @method static Builder|FranchiseOption whereOrderPendingTime($value)
 * @method static Builder|FranchiseOption whereUpdatedAt($value)
 * @mixin Eloquent
 * @property array|null $waybill_max_days
 * @method static Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static Builder|FranchiseOption whereWaybillMaxDays($value)
 * @property int $dispatching_minute
 * @method static Builder|FranchiseOption whereDispatchingMinute($value)
 */
	class FranchiseOption extends \Eloquent {}
}

namespace Src\Models\Franchise{
/**
 * Src\Models\Franchise\FranchisePhone
 *
 * @property int $franchise_phone_id
 * @property int|null $franchise_id
 * @property string $number
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|FranchisePhone newModelQuery()
 * @method static Builder|FranchisePhone newQuery()
 * @method static Builder|FranchisePhone query()
 * @method static Builder|FranchisePhone whereCreatedAt($value)
 * @method static Builder|FranchisePhone whereFranchiseId($value)
 * @method static Builder|FranchisePhone whereFranchisePhoneId($value)
 * @method static Builder|FranchisePhone whereNumber($value)
 * @method static Builder|FranchisePhone whereUpdatedAt($value)
 * @mixin Eloquent
 * @property-read Collection|FranchiseSubPhone[] $subPhones
 * @property-read int|null $sub_phones_count
 * @method static Builder|ServiceModel except($values = [])
 * @property-read Franchise|null $franchise
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
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
 */
	class FranchisePhone extends \Eloquent {}
}

namespace Src\Models\Franchise{
/**
 * Class FranchiseRegion
 *
 * @package Src\Models
 * @property int $franchise_region_id
 * @property int|null $franchise_id
 * @property int|null $region_id
 * @property-read Collection|Region[] $regions
 * @property-read int|null $regions_count
 * @method static Builder|FranchiseRegion newModelQuery()
 * @method static Builder|FranchiseRegion newQuery()
 * @method static Builder|FranchiseRegion query()
 * @method static Builder|FranchiseRegion whereFranchiseId($value)
 * @method static Builder|FranchiseRegion whereFranchiseRegionId($value)
 * @method static Builder|FranchiseRegion whereRegionId($value)
 * @mixin Eloquent
 * @method static Builder|ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @property false|string $city
 * @property-read Region|null $region
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|FranchiseRegion whereCity($value)
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property-read Collection|\Src\Models\Franchise\FranchiseCity[] $franchise_cities
 * @property-read int|null $franchise_cities_count
 * @property-read Collection|City[] $cities
 * @property-read int|null $cities_count
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
 * @property string $created_at
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|FranchiseRegion whereCreatedAt($value)
 */
	class FranchiseRegion extends \Eloquent {}
}

namespace Src\Models\Franchise{
/**
 * Src\Models\Franchise\FranchiseSubPhone
 *
 * @property int $franchise_sub_phone_id
 * @property int|null $franchise_phone_id
 * @property string $number
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|FranchiseSubPhone newModelQuery()
 * @method static Builder|FranchiseSubPhone newQuery()
 * @method static Builder|FranchiseSubPhone query()
 * @method static Builder|FranchiseSubPhone whereCreatedAt($value)
 * @method static Builder|FranchiseSubPhone whereFranchisePhoneId($value)
 * @method static Builder|FranchiseSubPhone whereFranchiseSubPhoneId($value)
 * @method static Builder|FranchiseSubPhone whereNumber($value)
 * @method static Builder|FranchiseSubPhone whereUpdatedAt($value)
 * @mixin Eloquent
 * @property-read WorkerOperator $activeWorker
 * @property string $password
 * @property-read Collection|WorkerOperator[] $atc_logged_operators
 * @property-read int|null $atc_logged_operators_count
 * @property-read Collection|WorkerOperator[] $operators
 * @property-read int|null $operators_count
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|FranchiseSubPhone wherePassword($value)
 * @property-read Collection|WorkerDispatcher[] $atc_logged_dispatchers
 * @property-read int|null $atc_logged_dispatchers_count
 * @property-read Collection|WorkerDispatcher[] $dispatchers
 * @property-read int|null $dispatchers_count
 * @property-read FranchisePhone|null $franchisePhone
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
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
 */
	class FranchiseSubPhone extends \Eloquent {}
}

namespace Src\Models\Franchise{
/**
 * Class FranchiseTransactionObserver
 *
 * @package Src\Models\Franchise
 * @property int $franchise_transaction_id
 * @property string|null $number
 * @property int $franchise_id
 * @property int|null $park_id
 * @property int|null $worker_id
 * @property int|null $payment_type_id
 * @property int $side_id
 * @property string $side_type
 * @property int|null $second_side_id
 * @property string|null $second_side_type
 * @property int|null $reason_id
 * @property string|null $reason_type
 * @property int $type
 * @property string $franchise_cost
 * @property string $side_cost
 * @property string $amount
 * @property string $remainder
 * @property bool $out
 * @property bool $payed
 * @property string|null $comment
 * @property \Illuminate\Support\Carbon $created_at
 * @property-read \Src\Models\Franchise\Franchise $franchise
 * @property-read Park|null $park
 * @property-read PaymentType|null $payment_type
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $reason
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $second_side
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $side
 * @property-read SystemWorker|null $worker
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, int $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel except(array $values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseTransaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseTransaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseTransaction query()
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseTransaction whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseTransaction whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseTransaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseTransaction whereFranchiseCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseTransaction whereFranchiseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseTransaction whereFranchiseTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseTransaction whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseTransaction whereOut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseTransaction whereParkId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseTransaction wherePayed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseTransaction wherePaymentTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseTransaction whereReasonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseTransaction whereReasonType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseTransaction whereRemainder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseTransaction whereSecondSideId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseTransaction whereSecondSideType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseTransaction whereSideCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseTransaction whereSideId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseTransaction whereSideType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseTransaction whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FranchiseTransaction whereWorkerId($value)
 * @mixin \Eloquent
 */
	class FranchiseTransaction extends \Eloquent {}
}

namespace Src\Models\Franchise{
/**
 * Class Module
 *
 * @package Src\Models\Franchise
 * @property int $module_id
 * @property string|null $name
 * @property string|null $slug_name
 * @property string|null $description
 * @property int $default
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Franchise[] $franchisee
 * @property-read Collection|Permission[] $permissions
 * @property-read Collection|Role[] $roles
 * @method static Builder|Module newModelQuery()
 * @method static Builder|Module newQuery()
 * @method static Builder|Module query()
 * @method static Builder|Module whereCreatedAt($value)
 * @method static Builder|Module whereDefault($value)
 * @method static Builder|Module whereDescription($value)
 * @method static Builder|Module whereModuleId($value)
 * @method static Builder|Module whereName($value)
 * @method static Builder|Module whereSlugName($value)
 * @method static Builder|Module whereUpdatedAt($value)
 * @mixin Eloquent
 * @property int|null $route_id
 * @property string|null $icon
 * @property-read Route $route
 * @method static Builder|Module whereIcon($value)
 * @method static Builder|Module whereRouteId($value)
 * @property string $alias
 * @property Carbon|null $deleted_at
 * @property-read int|null $franchisee_count
 * @property-read int|null $permissions_count
 * @property-read int|null $roles_count
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|Module onlyTrashed()
 * @method static bool|null restore()
 * @method static Builder|Module whereAlias($value)
 * @method static Builder|Module whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Module withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Module withoutTrashed()
 * @method static Builder|ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @property string $text
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static Builder|Module whereText($value)
 * @method static Builder|ServiceModel cordDistance($latitude, $longitude)
 */
	class Module extends \Eloquent {}
}

namespace Src\Models\LegalEntity{
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
	class LegalEntity extends \Eloquent {}
}

namespace Src\Models\LegalEntity{
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
	class LegalEntityBank extends \Eloquent {}
}

namespace Src\Models\LegalEntity{
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
	class LegalEntityType extends \Eloquent {}
}

namespace Src\Models\Location{
/**
 * Class City
 *
 * @package Src\Models\Location
 * @property int $city_id
 * @property int $region_id
 * @property int $country_id
 * @property string $name
 * @property-read Country $country
 * @property-read Region $region
 * @method static Builder|City newModelQuery()
 * @method static Builder|City newQuery()
 * @method static Builder|City query()
 * @method static Builder|City whereCityId($value)
 * @method static Builder|City whereCountryId($value)
 * @method static Builder|City whereName($value)
 * @method static Builder|City whereRegionId($value)
 * @mixin Eloquent
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Tariff[] $tariffs
 * @property-read int|null $tariffs_count
 * @method static Builder|ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|City whereCreatedAt($value)
 * @method static Builder|City whereDeletedAt($value)
 * @method static Builder|City whereUpdatedAt($value)
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property-read Collection|Franchise[] $franchisee
 * @property-read int|null $franchisee_count
 * @property-read Collection|Airport[] $airports
 * @property-read int|null $airports_count
 * @property-read Collection|Metro[] $metros
 * @property-read int|null $metros_count
 * @property-read Collection|RailwayStation[] $railways
 * @property-read int|null $railways_count
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 */
	class City extends \Eloquent {}
}

namespace Src\Models\Location{
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
	class Country extends \Eloquent {}
}

namespace Src\Models\Location{
/**
 * Class Region
 *
 * @package Src\Models
 * @property int $region_id
 * @property string $name
 * @property string|null $country_iso_2
 * @property int $country_id
 * @property-read Collection|City[] $cities
 * @property-read Country $country
 * @property-read Collection|Franchise[] $franchises
 * @method static Builder|Region newModelQuery()
 * @method static Builder|Region newQuery()
 * @method static Builder|Region query()
 * @method static Builder|Region whereCountryId($value)
 * @method static Builder|Region whereCountryIso2($value)
 * @method static Builder|Region whereName($value)
 * @method static Builder|Region whereRegionId($value)
 * @mixin Eloquent
 * @property string|null $iso_2
 * @property string|null $deleted_at
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property-read int|null $cities_count
 * @property-read int|null $franchises_count
 * @property-read Collection|Tariff[] $tariffs
 * @property-read int|null $tariffs_count
 * @method static Builder|Region whereCreatedAt($value)
 * @method static Builder|Region whereDeletedAt($value)
 * @method static Builder|Region whereIso2($value)
 * @method static Builder|Region whereUpdatedAt($value)
 * @method static Builder|ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @property-read Collection|FranchiseRegion[] $FranchiseRegion
 * @property-read int|null $franchise_region_count
 * @property-read Collection|Franchise[] $franchisee
 * @property-read int|null $franchisee_count
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
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
 * @property array|null $cord
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|Region whereCord($value)
 */
	class Region extends \Eloquent {}
}

namespace Src\Models\Location{
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
	class Timezone extends \Eloquent {}
}

namespace Src\Models\Monitor{
/**
 * Class Address
 *
 * @package Src\Models\Monitor
 * @property int $address_id
 * @property string $address
 * @property mixed $coordinate
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|AddressDetail[] $end_route
 * @property-read int|null $end_route_count
 * @property-read Collection|AddressesRoute[] $end_routes
 * @property-read int|null $end_routes_count
 * @property-read Collection|AddressDetail[] $initial_route
 * @property-read int|null $initial_route_count
 * @property-read Collection|AddressesRoute[] $initial_routes
 * @property-read int|null $initial_routes_count
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|Address newModelQuery()
 * @method static Builder|Address newQuery()
 * @method static Builder|Address query()
 * @method static Builder|Address whereAddress($value)
 * @method static Builder|Address whereAddressId($value)
 * @method static Builder|Address whereCoordinate($value)
 * @method static Builder|Address whereCreatedAt($value)
 * @method static Builder|Address whereUpdatedAt($value)
 * @mixin Eloquent
 * @property string $cord_text
 * @method static Builder|Address whereCordText($value)
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @property string|null $locality
 * @property string|null $province
 * @property float|null $lat
 * @property float|null $lut
 * @property string|null $code
 * @method static Builder|Address whereCode($value)
 * @method static Builder|Address whereLat($value)
 * @method static Builder|Address whereLocality($value)
 * @method static Builder|Address whereLut($value)
 * @method static Builder|Address whereProvince($value)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property string|null $short_address
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
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|Address whereShortAddress($value)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceModel within($geometryColumn, $polygon)
 */
	class Address extends \Eloquent {}
}

namespace Src\Models\Monitor{
/**
 * Class AddressDetail
 *
 * @package Src\Models\Monitor
 * @property int $address_detail_id
 * @property int $initial_address_id
 * @property int $end_address_id
 * @property int|null $distance
 * @property int|null $duration
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Address $end_address
 * @property-read Address $initial_address
 * @property-read Collection|AddressesRoute[] $routes
 * @property-read int|null $routes_count
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|AddressDetail newModelQuery()
 * @method static Builder|AddressDetail newQuery()
 * @method static Builder|AddressDetail query()
 * @method static Builder|AddressDetail whereAddressDetailId($value)
 * @method static Builder|AddressDetail whereCreatedAt($value)
 * @method static Builder|AddressDetail whereDistance($value)
 * @method static Builder|AddressDetail whereDuration($value)
 * @method static Builder|AddressDetail whereEndAddressId($value)
 * @method static Builder|AddressDetail whereInitialAddressId($value)
 * @method static Builder|AddressDetail whereUpdatedAt($value)
 * @mixin Eloquent
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
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
 */
	class AddressDetail extends \Eloquent {}
}

namespace Src\Models\Monitor{
/**
 * Class AddressesRoute
 *
 * @package Src\Models\Monitor
 * @property int $address_route_id
 * @property int|null $detail_id
 * @property object|null $route
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read AddressDetail|null $detail
 * @property-read Collection|AddressDetail[] $end_route
 * @property-read int|null $end_route_count
 * @property-read Collection|AddressDetail[] $initial_route
 * @property-read int|null $initial_route_count
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|AddressesRoute newModelQuery()
 * @method static Builder|AddressesRoute newQuery()
 * @method static Builder|AddressesRoute query()
 * @method static Builder|AddressesRoute whereAddressRouteId($value)
 * @method static Builder|AddressesRoute whereCreatedAt($value)
 * @method static Builder|AddressesRoute whereDetailId($value)
 * @method static Builder|AddressesRoute whereRoute($value)
 * @method static Builder|AddressesRoute whereUpdatedAt($value)
 * @mixin Eloquent
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
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
 */
	class AddressesRoute extends \Eloquent {}
}

namespace Src\Models\Monitor{
/**
 * Class ApiMonitoringListen
 *
 * @package Src\Models
 * @property int $api_monitoring_id
 * @property string|null $api
 * @property string|null $request
 * @property int|null $error
 * @property int|null $response_code
 * @property Carbon|null $created_at
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|Address newModelQuery()
 * @method static Builder|Address newQuery()
 * @method static Builder|Address query()
 * @method static Builder|Address whereApi($value)
 * @method static Builder|Address whereApiMonitoringId($value)
 * @method static Builder|Address whereCreatedAt($value)
 * @method static Builder|Address whereError($value)
 * @method static Builder|Address whereRequest($value)
 * @method static Builder|Address whereResponseCode($value)
 * @mixin Eloquent
 * @property string $request_method
 * @property string|null $updated_at
 * @method static Builder|ApiMonitoring whereRequestMethod($value)
 * @method static Builder|ApiMonitoring whereUpdatedAt($value)
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
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
 * @property int|null $api_key_id
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ApiMonitoring whereApiKeyId($value)
 */
	class ApiMonitoring extends \Eloquent {}
}

namespace Src\Models{
/**
 * Src\Models\Notification
 *
 * @property int $notification_id
 * @property string $group_number
 * @property int $annunciator_id
 * @property int $annunciator_type
 * @property int $notifier_id
 * @property int $notifier_type
 * @property string $title
 * @property string $body
 * @property array|null $payload
 * @property string $image
 * @property bool $viewed
 * @property Carbon $created_at
 * @property-read Model|Eloquent $annunciator
 * @property-read Model|Eloquent $notifier
 * @method static Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, int $distance = 1)
 * @method static Builder|ServiceModel except(array $values = [])
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|Notification newModelQuery()
 * @method static Builder|Notification newQuery()
 * @method static Builder|Notification query()
 * @method static Builder|Notification whereAnnunciatorId($value)
 * @method static Builder|Notification whereAnnunciatorType($value)
 * @method static Builder|Notification whereBody($value)
 * @method static Builder|Notification whereCreatedAt($value)
 * @method static Builder|Notification whereGroupNumber($value)
 * @method static Builder|Notification whereImage($value)
 * @method static Builder|Notification whereNotificationId($value)
 * @method static Builder|Notification whereNotifierId($value)
 * @method static Builder|Notification whereNotifierType($value)
 * @method static Builder|Notification wherePayload($value)
 * @method static Builder|Notification whereTitle($value)
 * @method static Builder|Notification whereViewed($value)
 * @mixin Eloquent
 */
	class Notification extends \Eloquent {}
}

namespace Src\Models\Oauth{
/**
 * Class AuthCode
 *
 * @package Src\Models\Oauth
 * @property string $id
 * @property int $user_id
 * @property int $client_id
 * @property string|null $scopes
 * @property bool $revoked
 * @property Carbon|null $expires_at
 * @property-read Client $client
 * @method static Builder|AuthCode newModelQuery()
 * @method static Builder|AuthCode newQuery()
 * @method static Builder|AuthCode query()
 * @method static Builder|AuthCode whereClientId($value)
 * @method static Builder|AuthCode whereExpiresAt($value)
 * @method static Builder|AuthCode whereId($value)
 * @method static Builder|AuthCode whereRevoked($value)
 * @method static Builder|AuthCode whereScopes($value)
 * @method static Builder|AuthCode whereUserId($value)
 * @mixin Eloquent
 */
	class AuthCode extends \Eloquent {}
}

namespace Src\Models\Oauth{
/**
 * Class ClientMessage
 *
 * @package Src\Models\Oauth
 * @property int $id
 * @property int|null $user_id
 * @property string $name
 * @property string $secret
 * @property string|null $provider
 * @property string|null $device
 * @property string $redirect
 * @property bool $personal_access_client
 * @property bool $password_client
 * @property bool $revoked
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|AuthCode[] $authCodes
 * @property-read int|null $auth_codes_count
 * @property-read string|null $plain_secret
 * @property-read Collection|Token[] $tokens
 * @property-read int|null $tokens_count
 * @method static Builder|Client newModelQuery()
 * @method static Builder|Client newQuery()
 * @method static Builder|Client query()
 * @method static Builder|Client whereCreatedAt($value)
 * @method static Builder|Client whereDevice($value)
 * @method static Builder|Client whereId($value)
 * @method static Builder|Client whereName($value)
 * @method static Builder|Client wherePasswordClient($value)
 * @method static Builder|Client wherePersonalAccessClient($value)
 * @method static Builder|Client whereProvider($value)
 * @method static Builder|Client whereRedirect($value)
 * @method static Builder|Client whereRevoked($value)
 * @method static Builder|Client whereSecret($value)
 * @method static Builder|Client whereUpdatedAt($value)
 * @method static Builder|Client whereUserId($value)
 * @mixin Eloquent
 */
	class Client extends \Eloquent {}
}

namespace Src\Models\Oauth{
/**
 * Class FcmClient
 *
 * @package Src\Models\Oauth
 * @property int $fcm_client_id
 * @property int $client_id
 * @property string $client_type
 * @property string $key
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Model|Eloquent $client
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, int $distance = 1)
 * @method static Builder|ServiceModel except(array $values = [])
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|FcmClient newModelQuery()
 * @method static Builder|FcmClient newQuery()
 * @method static Builder|FcmClient query()
 * @method static Builder|FcmClient whereClientId($value)
 * @method static Builder|FcmClient whereClientType($value)
 * @method static Builder|FcmClient whereCreatedAt($value)
 * @method static Builder|FcmClient whereFcmClientId($value)
 * @method static Builder|FcmClient whereKey($value)
 * @method static Builder|FcmClient whereUpdatedAt($value)
 * @mixin Eloquent
 * @method static Builder|ServiceModel cordDistance($latitude, $longitude)
 */
	class FcmClient extends \Eloquent {}
}

namespace Src\Models\Oauth{
/**
 * Class PersonalAccessClient
 *
 * @package Src\Models\Oauth
 * @property int $id
 * @property int $client_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Client $client
 * @method static Builder|PersonalAccessClient newModelQuery()
 * @method static Builder|PersonalAccessClient newQuery()
 * @method static Builder|PersonalAccessClient query()
 * @method static Builder|PersonalAccessClient whereClientId($value)
 * @method static Builder|PersonalAccessClient whereCreatedAt($value)
 * @method static Builder|PersonalAccessClient whereId($value)
 * @method static Builder|PersonalAccessClient whereUpdatedAt($value)
 * @mixin Eloquent
 */
	class PersonalAccessClient extends \Eloquent {}
}

namespace Src\Models\Oauth{
/**
 * Class Refresh
 *
 * @package Src\Models\Oauth
 * @property string $id
 * @property string $access_token_id
 * @property bool $revoked
 * @property \Illuminate\Support\Carbon|null $expires_at
 * @property-read \Src\Models\Oauth\Token $accessToken
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Oauth\Refresh newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Oauth\Refresh newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Oauth\Refresh query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Oauth\Refresh whereAccessTokenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Oauth\Refresh whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Oauth\Refresh whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Oauth\Refresh whereRevoked($value)
 * @mixin \Eloquent
 */
	class Refresh extends \Eloquent {}
}

namespace Src\Models\Oauth{
/**
 * Class Token
 *
 * @package Src\Models\Oauth
 * @property string $id
 * @property int|null $user_id
 * @property int $client_id
 * @property string|null $name
 * @property array|null $scopes
 * @property bool $revoked
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property \Illuminate\Support\Carbon|null $expires_at
 * @property-read \Src\Models\Oauth\Client $client
 * @property-read \Src\Models\Oauth\Refresh|null $refresh_token
 * @method static \Illuminate\Database\Eloquent\Builder|Token newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Token newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Token query()
 * @method static \Illuminate\Database\Eloquent\Builder|Token whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Token whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Token whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Token whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Token whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Token whereRevoked($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Token whereScopes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Token whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Token whereUserId($value)
 * @mixin \Eloquent
 */
	class Token extends \Eloquent {}
}

namespace Src\Models\Order{
/**
 * Class CanceledOrder
 *
 * @package Src\Models\Order
 * @property int $canceled_order_id
 * @property int $order_id
 * @property int $cancelable_id
 * @property string $cancelable_type
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|OrderAssessment[] $assessments
 * @property-read int|null $assessments_count
 * @property-read Model|Eloquent $cancelable
 * @property-read Collection|OrderFeedback[] $feedbacks
 * @property-read int|null $feedbacks_count
 * @property-read Order $order
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|CanceledOrder newModelQuery()
 * @method static Builder|CanceledOrder newQuery()
 * @method static Builder|CanceledOrder query()
 * @method static Builder|CanceledOrder whereCancelableId($value)
 * @method static Builder|CanceledOrder whereCancelableType($value)
 * @method static Builder|CanceledOrder whereCanceledOrderId($value)
 * @method static Builder|CanceledOrder whereCreatedAt($value)
 * @method static Builder|CanceledOrder whereOrderId($value)
 * @method static Builder|CanceledOrder whereUpdatedAt($value)
 * @mixin Eloquent
 * @property int|null $feedback_id
 * @method static Builder|CanceledOrder whereFeedbackId($value)
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property int|null $driver_id
 * @property int|null $car_id
 * @property-read Car|null $car
 * @property-read Driver|null $driver
 * @method static Builder|CanceledOrder whereCarId($value)
 * @method static Builder|CanceledOrder whereDriverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 */
	class CanceledOrder extends \Eloquent {}
}

namespace Src\Models\Order{
/**
 * Class CompletedOrder
 *
 * @package Src\Models\Order
 * @property int $completed_order_id
 * @property int|null $order_id
 * @property int|null $driver_id
 * @property string|null $distance
 * @property string|null $duration
 * @property float|null $cost
 * @property object $trajectory
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read int|null $assessments_count
 * @property-read Driver $driver
 * @property-read Collection|OrderFeedback[] $feedbacks
 * @property-read int|null $feedbacks_count
 * @property-read Order|null $order
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|CompletedOrder newModelQuery()
 * @method static Builder|CompletedOrder newQuery()
 * @method static Builder|CompletedOrder query()
 * @method static Builder|CompletedOrder whereCompletedOrderId($value)
 * @method static Builder|CompletedOrder whereCost($value)
 * @method static Builder|CompletedOrder whereCreatedAt($value)
 * @method static Builder|CompletedOrder whereDistance($value)
 * @method static Builder|CompletedOrder whereDriverId($value)
 * @method static Builder|CompletedOrder whereDuration($value)
 * @method static Builder|CompletedOrder whereOrderId($value)
 * @method static Builder|CompletedOrder whereTrajectory($value)
 * @method static Builder|CompletedOrder whereUpdatedAt($value)
 * @mixin Eloquent
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property-read Waybill|null $waybill
 * @property int|null $car_id
 * @property-read Car|null $car
 * @method static Builder|CompletedOrder whereCarId($value)
 * @property string|null $destination_address
 * @method static Builder|CompletedOrder whereDestinationAddress($value)
 * @property string|null $in_price
 * @property string|null $out_price
 * @property mixed|null $in_trajectory
 * @property mixed|null $out_trajectory
 * @property string|null $in_distance
 * @property string|null $out_distance
 * @property string|null $in_duration
 * @property string|null $out_duration
 * @property-read Collection|OrderFeedback[] $assessments
 * @property-read OrderInitialData|null $initial
 * @property string|null $in_distance_price
 * @property string|null $in_duration_price
 * @property string|null $out_distance_price
 * @property string|null $out_duration_price
 * @property bool $changed
 * @property-read Collection|CompletedOrderChange[] $changes
 * @property-read int|null $changes_count
 * @property string $distance_price
 * @property string $duration_price
 * @property-read CompletedOrderCrossing|null $crossing
 * @method static Builder|CompletedOrder whereDistancePrice($value)
 * @method static Builder|CompletedOrder whereDurationPrice($value)
 * @property string|null $destination_lat
 * @property string|null $destination_lut
 * @property-read \Src\Models\Order\OrderProcess|null $process
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static Builder|CompletedOrder whereChanged($value)
 * @method static Builder|CompletedOrder whereDestinationLat($value)
 * @method static Builder|CompletedOrder whereDestinationLut($value)
 */
	class CompletedOrder extends \Eloquent {}
}

namespace Src\Models\Order{
/**
 * Class CompletedOrderChange
 *
 * @package Src\Models\Order
 * @property int $completed_order_change_id
 * @property int $completed_id
 * @property int $changer_id system_workers
 * @property string $old_price
 * @property string $new_price
 * @property float|null $old_distance
 * @property float|null $new_distance
 * @property int|null $old_duration
 * @property int|null $new_duration
 * @property mixed|null $old_trajectory
 * @property mixed|null $new_trajectory
 * @property \Illuminate\Support\Carbon $created_at
 * @property-read \Src\Models\Order\CompletedOrder $completed
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, int $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel except(array $values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedOrderChange newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedOrderChange newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedOrderChange query()
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedOrderChange whereChangerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedOrderChange whereCompletedId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedOrderChange whereCompletedOrderChangeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedOrderChange whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedOrderChange whereNewDistance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedOrderChange whereNewDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedOrderChange whereNewPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedOrderChange whereNewTrajectory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedOrderChange whereOldDistance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedOrderChange whereOldDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedOrderChange whereOldPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedOrderChange whereOldTrajectory($value)
 * @mixin \Eloquent
 */
	class CompletedOrderChange extends \Eloquent {}
}

namespace Src\Models\Order{
/**
 * Src\Models\Order\CompletedOrderCrossing
 *
 * @property int $completed_order_crossing_id
 * @property int $completed_id
 * @property string|null $in_price
 * @property string|null $out_price
 * @property string|null $in_distance_price
 * @property string|null $in_duration_price
 * @property string|null $out_distance_price
 * @property string|null $out_duration_price
 * @property array|null $in_trajectory
 * @property array|null $out_trajectory
 * @property float|null $in_distance
 * @property float|null $out_distance
 * @property int|null $in_duration
 * @property int|null $out_duration
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Src\Models\Order\CompletedOrder $completed
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, int $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel except(array $values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedOrderCrossing newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedOrderCrossing newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedOrderCrossing query()
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedOrderCrossing whereCompletedId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedOrderCrossing whereCompletedOrderCrossingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedOrderCrossing whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedOrderCrossing whereInDistance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedOrderCrossing whereInDistancePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedOrderCrossing whereInDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedOrderCrossing whereInDurationPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedOrderCrossing whereInPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedOrderCrossing whereInTrajectory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedOrderCrossing whereOutDistance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedOrderCrossing whereOutDistancePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedOrderCrossing whereOutDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedOrderCrossing whereOutDurationPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedOrderCrossing whereOutPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedOrderCrossing whereOutTrajectory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompletedOrderCrossing whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class CompletedOrderCrossing extends \Eloquent {}
}

namespace Src\Models\Order{
/**
 * Src\Models\Order\ExternalOrder
 *
 * @property int $external_order_id
 * @property int $order_id
 * @property int $board_id
 * @property string $order_key
 * @property int $draft
 * @property mixed $payload
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read ExternalBoard $board
 * @property-read \Src\Models\Order\Order $order
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, int $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel except(array $values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalOrder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalOrder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalOrder query()
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalOrder whereBoardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalOrder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalOrder whereDraft($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalOrder whereExternalOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalOrder whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalOrder whereOrderKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalOrder wherePayload($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalOrder whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class ExternalOrder extends \Eloquent {}
}

namespace Src\Models\Order{
/**
 * Class OperatorRejectCause
 *
 * @package Src\Models\Order
 * @property int $operator_reject_cause_id
 * @property string $text
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\ServiceEntity\Models\ServiceModel except($values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Order\OperatorRejectCause newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Order\OperatorRejectCause newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Order\OperatorRejectCause query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Order\OperatorRejectCause whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Order\OperatorRejectCause whereOperatorRejectCauseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Order\OperatorRejectCause whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Order\OperatorRejectCause whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
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
 */
	class OperatorRejectCause extends \Eloquent {}
}

namespace Src\Models\Order{
/**
 * Class Order
 *
 * @package Src\Models\Order
 * @property int $order_id
 * @property int|null $car_class_id
 * @property int|null $order_type_id
 * @property int|null $payment_type_id
 * @property int|null $status_id
 * @property int|null $client_id
 * @property int|null $customer_id
 * @property string|null $customer_type
 * @property array|null $franchisee
 * @property array|null $car_option
 * @property array $from_coordinates
 * @property array|null $to_coordinates
 * @property string $address_from
 * @property string|null $address_to
 * @property string|null $platform
 * @property string|null $comments
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read CanceledOrder|null $canceled
 * @property-read CarClass|null $carClass
 * @property-read Client|null $client
 * @property-read CompletedOrder|null $completed
 * @property-read OrderCorporate|null $corporate
 * @property-read Driver|null $driver
 * @property-read Franchise $franchise
 * @property-read Collection|OrderInProcessRoad[] $in_process_roads
 * @property-read int|null $in_process_roads_count
 * @property-read OrderInitialData|null $initial_data
 * @property-read OrderMeet|null $meet
 * @property-read Collection|OrderOnWayRoad[] $on_way_roads
 * @property-read int|null $on_way_roads_count
 * @property-read OrderType|null $orderType
 * @property-read Collection|OrderShippedDriver[] $ordering_shipments
 * @property-read int|null $ordering_shipments_count
 * @property-read PaymentType|null $paymentType
 * @property-read PreOrder|null $preorder
 * @property-read PreOrder|null $schedule
 * @property-read OrderStageCord|null $stage
 * @property-read OrderStatus|null $status
 * @property-read SystemWorker|null $system_worker
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel except($values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order newQuery()
 * @method static Builder|Order onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereAddressFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereAddressTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCarClassId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCarOption($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCustomerType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereFranchisee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereFromCoordinates($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereOrderTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePaymentTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePlatform($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereToCoordinates($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUpdatedAt($value)
 * @method static Builder|Order withTrashed()
 * @method static Builder|Order withoutTrashed()
 * @mixin Eloquent
 * @property-read Model|Eloquent $customer
 * @property-read OrderCommon|null $common
 * @property-read Collection|Driver[] $commons_driver
 * @property-read int|null $commons_driver_count
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @property int $show_cord
 * @property string|null $lat
 * @property string|null $lut
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereLut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereShowCord($value)
 * @property-read Collection|OrderConversationTalk[] $conversation_talk
 * @property-read int|null $conversation_talk_count
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property-read OrderProcess|null $process
 * @property-read Car|null $completed_car
 * @property-read Driver|null $completed_driver
 * @property int|null $passenger_id
 * @property int|null $operator_id
 * @property-read int|null $assessment_count
 * @property-read OrderInProcessRoad|null $in_process_road
 * @property-read OrderOnWayRoad|null $on_way_road
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereOperatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePassengerId($value)
 * @property-read SystemWorker|null $operator
 * @property-read OrderAttach|null $attach
 * @property-read Collection|Complaint[] $complaints
 * @property-read int|null $complaints_count
 * @property-read OrderShippedDriver|null $current_shipped
 * @property-read Collection|OrderFeedback[] $feedbacks
 * @property-read int|null $feedbacks_count
 * @property-read Collection|OrderWorkerComment[] $worker_comments
 * @property-read int|null $worker_comments_count
 * @property-read HigherOrderBuilderProxy|mixed|string $from_short
 * @property-read HigherOrderBuilderProxy|mixed|string $to_short
 * @property-read Collection|OrderFeedback[] $assessment
 * @property-read Client|null $passenger
 * @property-read FranchiseTransaction|null $transaction_reason
 * @property-read FranchiseTransaction|null $reason
 * @property-read int|null $attach_count
 * @property-read Collection|CompletedOrderChange[] $changes
 * @property-read int|null $changes_count
 * @property-read Collection|OrderFeedbackComment[] $feedback_comments
 * @property-read int|null $feedback_comments_count
 * @property-read OrderShippedDriver|null $selected_shipped
 * @property-read CompletedOrderCrossing|null $crossing
 * @property-read OrderRent|null $rent
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 * @property int $dist_type
 * @property-read EstimatedRating|null $estimated_rating
 * @property-read Collection|EstimatedRating[] $estimated_ratings
 * @property-read int|null $estimated_ratings_count
 * @property-read OrderShippedDriver|null $shipped
 * @property-read Driver|null $shipped_driver
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDistType($value)
 * @property int|null $customer_zone_id
 * @property int|null $location_zone_id
 * @property-read Collection|\Src\Models\Order\OrderCommon[] $commons
 * @property-read int|null $commons_count
 * @property-read Timezone|null $customer_zone
 * @property-read Timezone|null $location_zone
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCustomerZoneId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereLocationZoneId($value)
 * @property \datetime $repeated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereRepeatedAt($value)
 */
	class Order extends \Eloquent {}
}

namespace Src\Models\Order{
/**
 * Src\Models\Order\OrderAttach
 *
 * @property int $order_attach_id
 * @property int $order_id
 * @property int|null $driver_id
 * @property int|null $system_worker_id
 * @property int|null $accepted
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|OrderAttach newModelQuery()
 * @method static Builder|OrderAttach newQuery()
 * @method static \Illuminate\Database\Query\Builder|OrderAttach onlyTrashed()
 * @method static Builder|OrderAttach query()
 * @method static Builder|OrderAttach whereAccepted($value)
 * @method static Builder|OrderAttach whereCreatedAt($value)
 * @method static Builder|OrderAttach whereDriverId($value)
 * @method static Builder|OrderAttach whereOrderAttachId($value)
 * @method static Builder|OrderAttach whereOrderId($value)
 * @method static Builder|OrderAttach whereSystemWorkerId($value)
 * @method static Builder|OrderAttach whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|OrderAttach withTrashed()
 * @method static \Illuminate\Database\Query\Builder|OrderAttach withoutTrashed()
 * @mixin Eloquent
 * @property int|null $shipped_id
 * @property-read OrderShippedDriver|null $shipped
 * @method static Builder|OrderAttach whereShippedId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, int $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel except(array $values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 */
	class OrderAttach extends \Eloquent {}
}

namespace Src\Models\Order{
/**
 * Class OrderCommon
 *
 * @package Src\Order
 * @property int $order_common_id
 * @property int $order_id
 * @property array $driver_ids
 * @property int|null $accept
 * @property int $emergency
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Order $order
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|OrderCommon newModelQuery()
 * @method static Builder|OrderCommon newQuery()
 * @method static Builder|OrderCommon query()
 * @method static Builder|OrderCommon whereAccept($value)
 * @method static Builder|OrderCommon whereCreatedAt($value)
 * @method static Builder|OrderCommon whereDriverIds($value)
 * @method static Builder|OrderCommon whereEmergency($value)
 * @method static Builder|OrderCommon whereOrderCommonId($value)
 * @method static Builder|OrderCommon whereOrderId($value)
 * @method static Builder|OrderCommon whereUpdatedAt($value)
 * @mixin Eloquent
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property array $driver
 * @property-read Collection|OrderShippedDriver[] $shipped
 * @property-read int|null $shipped_count
 * @method static Builder|OrderCommon whereDriver($value)
 * @property bool $manual
 * @method static Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static Builder|OrderCommon whereManual($value)
 * @property int|null $filter_type
 * @property int|null $distance
 * @property string|null $accepted
 * @property bool|null $active
 * @property-read \Src\Models\Order\PreOrder $preorder
 * @property-read \Src\Models\Order\OrderShippedDriver|null $ship
 * @method static Builder|OrderCommon whereAccepted($value)
 * @method static Builder|OrderCommon whereActive($value)
 * @method static Builder|OrderCommon whereDistance($value)
 * @method static Builder|OrderCommon whereFilterType($value)
 */
	class OrderCommon extends \Eloquent {}
}

namespace Src\Models\Order{
/**
 * Src\Models\Order\OrderCorporate
 *
 * @property int $order_corporate_id
 * @property int $order_id
 * @property int $company_id
 * @property int $driver_id
 * @property int $slip_number
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|OrderCorporate newModelQuery()
 * @method static Builder|OrderCorporate newQuery()
 * @method static Builder|OrderCorporate query()
 * @method static Builder|OrderCorporate whereCompanyId($value)
 * @method static Builder|OrderCorporate whereCreatedAt($value)
 * @method static Builder|OrderCorporate whereDriverId($value)
 * @method static Builder|OrderCorporate whereOrderCorporateId($value)
 * @method static Builder|OrderCorporate whereOrderId($value)
 * @method static Builder|OrderCorporate whereSlipNumber($value)
 * @method static Builder|OrderCorporate whereUpdatedAt($value)
 * @mixin Eloquent
 * @property string|null $deleted_at
 * @property-read \Src\Models\Corporate\Company $company
 * @property-read \Src\Models\Driver\Driver $driver
 * @property-read \Src\Models\Order\Order $order
 * @method static \Illuminate\Database\Eloquent\Builder|\ServiceEntity\Models\ServiceModel except($values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Order\OrderCorporate whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
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
 * @property-read \Illuminate\Database\Eloquent\Collection|\Src\Models\Order\CompletedOrder[] $completed
 * @property-read int|null $completed_count
 */
	class OrderCorporate extends \Eloquent {}
}

namespace Src\Models\Order{
/**
 * Class OrderFeedback
 *
 * @package Src\Models\Order
 * @property int|null $order_feedback_id
 * @property int|null $feedback_option_id
 * @property int|null $orderable_id
 * @property int|null $writable_id
 * @property string|null $orderable_type
 * @property int|null $writable_type
 * @property string|null $text
 * @property-read OrderFeedbackOption $option
 * @property-read Model|Eloquent $orderable
 * @property-read Model|Eloquent $writable
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|OrderFeedback newModelQuery()
 * @method static Builder|OrderFeedback newQuery()
 * @method static Builder|OrderFeedback query()
 * @method static Builder|OrderFeedback whereFeedbackOptionId($value)
 * @method static Builder|OrderFeedback whereOrderFeedbackId($value)
 * @method static Builder|OrderFeedback whereOrderableId($value)
 * @method static Builder|OrderFeedback whereOrderableType($value)
 * @method static Builder|OrderFeedback whereText($value)
 * @method static Builder|OrderFeedback whereWritableId($value)
 * @method static Builder|OrderFeedback whereWritableType($value)
 * @mixin Eloquent
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|OrderFeedback whereCreatedAt($value)
 * @method static Builder|OrderFeedback whereUpdatedAt($value)
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property int $order_id
 * @property int $assessment
 * @property-read Order $order
 * @method static Builder|OrderFeedback whereAssessment($value)
 * @method static Builder|OrderFeedback whereOrderId($value)
 * @property int|null $readable_id
 * @property string|null $readable_type
 * @property-read Model|Eloquent $readable
 * @method static Builder|OrderFeedback whereReadableId($value)
 * @method static Builder|OrderFeedback whereReadableType($value)
 * @property-read Collection|OrderFeedbackComment[] $comments
 * @property-read int|null $comments_count
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 */
	class OrderFeedback extends \Eloquent {}
}

namespace Src\Models\Order{
/**
 * Class OrderFeedbackComment
 *
 * @package Src\Models\Order
 * @property int $order_feedback_comment_id
 * @property int $feedback_id
 * @property int $commenting_id
 * @property int $new_status
 * @property int $old_status
 * @property string $comment
 * @property \Illuminate\Support\Carbon $created_at
 * @property-read \Src\Models\Order\OrderFeedback $feedback
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, int $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel except(array $values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderFeedbackComment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderFeedbackComment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderFeedbackComment query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderFeedbackComment whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderFeedbackComment whereCommentingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderFeedbackComment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderFeedbackComment whereFeedbackId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderFeedbackComment whereNewStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderFeedbackComment whereOldStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderFeedbackComment whereOrderFeedbackCommentId($value)
 * @mixin \Eloquent
 */
	class OrderFeedbackComment extends \Eloquent {}
}

namespace Src\Models\Order{
/**
 * Class OrderFeedbackOption
 *
 * @package Src\Models\Order
 * @property int|null $order_feedback_option_id
 * @property int|null $option
 * @property string|null $name
 * @property int|null $completed
 * @property int|null $canceled
 * @property int|null $reseted
 * @property string|null $owner_type
 * @property-read Collection|\Src\Models\Order\OrderFeedback[] $feedback
 * @property-read int|null $feedback_count
 * @method static \Illuminate\Database\Eloquent\Builder|\ServiceEntity\Models\ServiceModel except($values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Order\OrderFeedbackOption newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Order\OrderFeedbackOption newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Order\OrderFeedbackOption query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Order\OrderFeedbackOption whereCanceled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Order\OrderFeedbackOption whereCompleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Order\OrderFeedbackOption whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Order\OrderFeedbackOption whereOption($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Order\OrderFeedbackOption whereOrderFeedbackOptionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Order\OrderFeedbackOption whereOwnerType($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Order\OrderFeedbackOption whereReseted($value)
 * @property mixed $assessment
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Order\OrderFeedbackOption whereAssessment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
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
 */
	class OrderFeedbackOption extends \Eloquent {}
}

namespace Src\Models\Order{
/**
 * Class OrderInProcessRoad
 *
 * @package Src\Models\Order
 * @property int $order_in_process_road_id
 * @property array|mixed $route
 * @property int $shipment_driver_id
 * @property float|null $distance
 * @property float|null $duration
 * @property int $selected
 * @property array|mixed $real_road
 * @property int|null $speed
 * @property Carbon|null $cord_updated
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Driver $driver
 * @property-read OrderShippedDriver $shipment_driver
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel except($values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInProcessRoad newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInProcessRoad newQuery()
 * @method static Builder|OrderInProcessRoad onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInProcessRoad query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInProcessRoad whereCordUpdated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInProcessRoad whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInProcessRoad whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInProcessRoad whereDistance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInProcessRoad whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInProcessRoad whereOrderInProcessRoadId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInProcessRoad whereRealRoad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInProcessRoad whereRoute($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInProcessRoad whereSelected($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInProcessRoad whereShipmentDriverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInProcessRoad whereSpeed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInProcessRoad whereUpdatedAt($value)
 * @method static Builder|OrderInProcessRoad withTrashed()
 * @method static Builder|OrderInProcessRoad withoutTrashed()
 * @mixin Eloquent
 * @property-read OrderProcess|null $process
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 */
	class OrderInProcessRoad extends \Eloquent {}
}

namespace Src\Models\Order{
/**
 * Class InitialOrderData
 *
 * @package Src\Models\Order
 * @property int $initial_order_data_id
 * @property int|null $order_id
 * @property int|null $initial_tariff_id
 * @property int|null $second_tariff_id
 * @property int $orderable_id
 * @property string $orderable_type
 * @property string|null $price
 * @property string|null $currency
 * @property int $sitting_fee
 * @property int $initial
 * @property string|null $distance
 * @property string|null $duration
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Tariff|null $initial_tariff
 * @property-read Order|null $order
 * @property-read Model|Eloquent $orderable
 * @property-read Tariff|null $second_tariff
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel except($values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInitialData newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInitialData newQuery()
 * @method static Builder|OrderInitialData onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInitialData query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInitialData whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInitialData whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInitialData whereDistance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInitialData whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInitialData whereInitial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInitialData whereInitialOrderDataId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInitialData whereInitialTariffId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInitialData whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInitialData whereOrderableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInitialData whereOrderableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInitialData wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInitialData whereSecondTariffId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInitialData whereSittingFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInitialData whereUpdatedAt($value)
 * @method static Builder|OrderInitialData withTrashed()
 * @method static Builder|OrderInitialData withoutTrashed()
 * @mixin Eloquent
 * @property string $lat
 * @property string $lut
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInitialData whereLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInitialData whereLut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInitialData whereDeletedAt($value)
 * @property int $order_initial_data_id
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInitialData whereOrderInitialDataId($value)
 * @property int $region_id
 * @property int|null $city_id
 * @property int|null $behind
 * @property string|null $option_price
 * @property string|null $sitting_price
 * @property int $waiting_cancel
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInitialData whereBehind($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInitialData whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInitialData whereOptionPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInitialData whereRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInitialData whereSittingPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderInitialData whereWaitingCancel($value)
 */
	class OrderInitialData extends \Eloquent {}
}

namespace Src\Models\Order{
/**
 * Class OrderMeet
 *
 * @package Src\Models\Order
 * @property int $order_meet_id
 * @property int $order_id
 * @property string|null $vagon_number
 * @property string|null $flight_number
 * @property string|null $from
 * @property string|null $text
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|OrderMeet newModelQuery()
 * @method static Builder|OrderMeet newQuery()
 * @method static Builder|OrderMeet query()
 * @method static Builder|OrderMeet whereCreatedAt($value)
 * @method static Builder|OrderMeet whereFlightNumber($value)
 * @method static Builder|OrderMeet whereFrom($value)
 * @method static Builder|OrderMeet whereOrderId($value)
 * @method static Builder|OrderMeet whereOrderMeetId($value)
 * @method static Builder|OrderMeet whereText($value)
 * @method static Builder|OrderMeet whereUpdatedAt($value)
 * @method static Builder|OrderMeet whereVagonNumber($value)
 * @mixin Eloquent
 * @property int $airport_id
 * @property int $station_id
 * @property string|null $wagon_number
 * @method static Builder|OrderMeet whereAirportId($value)
 * @method static Builder|OrderMeet whereStationId($value)
 * @method static Builder|OrderMeet whereWagonNumber($value)
 * @property int $metro_id
 * @method static Builder|OrderMeet whereMetroId($value)
 * @property int $place_id
 * @property string $place_type
 * @property string|null $info
 * @property Carbon|null $deleted_at
 * @property-read Order $order
 * @property-read Model|Eloquent $place
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static \Illuminate\Database\Query\Builder|OrderMeet onlyTrashed()
 * @method static Builder|OrderMeet whereDeletedAt($value)
 * @method static Builder|OrderMeet whereInfo($value)
 * @method static Builder|OrderMeet wherePlaceId($value)
 * @method static Builder|OrderMeet wherePlaceType($value)
 * @method static \Illuminate\Database\Query\Builder|OrderMeet withTrashed()
 * @method static \Illuminate\Database\Query\Builder|OrderMeet withoutTrashed()
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 */
	class OrderMeet extends \Eloquent {}
}

namespace Src\Models\Order{
/**
 * Class OrderOnWayRoad
 *
 * @package Src\Models\Order
 * @property int $order_process_data_id
 * @property int $order_id
 * @property array $route
 * @property float $distance
 * @property int $duration
 * @property int|null $selected
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Order $order
 * @method static \Illuminate\Database\Eloquent\Builder|OrderOnWayRoad newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderOnWayRoad newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderOnWayRoad query()
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderOnWayRoad whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderOnWayRoad whereDistance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderOnWayRoad whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderOnWayRoad whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderOnWayRoad whereOrderProcessDataId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderOnWayRoad whereRoute($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderOnWayRoad whereSelected($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderOnWayRoad whereUpdatedAt($value)
 * @mixin Eloquent
 * @property int $order_on_way_road_id
 * @property int $shipment_driver_id
 * @property array|null $real_road
 * @property int|null $speed
 * @property Carbon|null $cord_updated
 * @property Carbon|null $deleted_at
 * @property-read Driver $driver
 * @property-read OrderShippedDriver $shipment_driver
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel except($values = [])
 * @method static Builder|OrderOnWayRoad onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderOnWayRoad whereCordUpdated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderOnWayRoad whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderOnWayRoad whereOrderOnWayRoadId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderOnWayRoad whereRealRoad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderOnWayRoad whereShipmentDriverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderOnWayRoad whereSpeed($value)
 * @method static Builder|OrderOnWayRoad withTrashed()
 * @method static Builder|OrderOnWayRoad withoutTrashed()
 * @property-read OrderProcess|null $process
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property int $order_late
 * @method static \Illuminate\Database\Eloquent\Builder|OrderOnWayRoad whereOrderLate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 */
	class OrderOnWayRoad extends \Eloquent {}
}

namespace Src\Models\Order{
/**
 * Class OrderProcess
 *
 * @package Src\Models\Order
 * @property int $order_process_id
 * @property int $processed_id
 * @property string $processed_type
 * @property float|null $price
 * @property float|null $sitting_price
 * @property float|null $waiting_price
 * @property int|null $travel_time
 * @property int|null $pause_time
 * @property int|null $distance_traveled
 * @property int|null $waiting_time
 * @property Carbon|null $cord_updated
 * @property int|null $speed
 * @property Carbon|null $price_passed
 * @property-read Model|Eloquent $processed
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|OrderProcess newModelQuery()
 * @method static Builder|OrderProcess newQuery()
 * @method static Builder|OrderProcess query()
 * @method static Builder|OrderProcess whereCordUpdated($value)
 * @method static Builder|OrderProcess whereDistanceTraveled($value)
 * @method static Builder|OrderProcess whereOrderProcessId($value)
 * @method static Builder|OrderProcess wherePrice($value)
 * @method static Builder|OrderProcess wherePricePassed($value)
 * @method static Builder|OrderProcess whereProcessedId($value)
 * @method static Builder|OrderProcess whereProcessedType($value)
 * @method static Builder|OrderProcess whereSittingPrice($value)
 * @method static Builder|OrderProcess whereSpeed($value)
 * @method static Builder|OrderProcess whereTravelTime($value)
 * @method static Builder|OrderProcess whereWaitingTime($value)
 * @mixin Eloquent
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property int $order_shipped_id
 * @property float|null $calculate_price
 * @property float|null $pause_price
 * @property-read OrderShippedDriver $shipped
 * @method static Builder|OrderProcess whereCalculatePrice($value)
 * @method static Builder|OrderProcess whereOrderShippedId($value)
 * @method static Builder|OrderProcess wherePausePrice($value)
 * @property string|null $total_price
 * @method static Builder|OrderProcess whereTotalPrice($value)
 * @property string|null $increment_price
 * @property string|null $options_price
 * @property string|null $cancel_price
 * @method static Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static Builder|OrderProcess whereCancelPrice($value)
 * @method static Builder|OrderProcess whereIncrementPrice($value)
 * @method static Builder|OrderProcess whereOptionsPrice($value)
 * @method static Builder|OrderProcess whereWaitingPrice($value)
 * @method static Builder|OrderProcess wherePauseTime($value)
 */
	class OrderProcess extends \Eloquent {}
}

namespace Src\Models\Order{
/**
 * Src\Models\Order\OrderRent
 *
 * @property int $order_rent_id
 * @property int $order_id
 * @property int $hours
 * @property int|null $after_rent_time MINUTE
 * @property string $created_at
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, int $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel except(array $values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRent query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRent whereAfterRentTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRent whereHours($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRent whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRent whereOrderRentId($value)
 * @mixin \Eloquent
 */
	class OrderRent extends \Eloquent {}
}

namespace Src\Models\Order{
/**
 * Class OrderShippedDriver
 *
 * @package Src\Models\Driver
 * @property int $pre_order_data_id
 * @property int $driver_id
 * @property int $order_id
 * @property int $system_rating_driver_id
 * @property int|null $status_id
 * @property int $current
 * @property string $response_url_hash
 * @property string $unix
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Driver $driver
 * @property-read Order $order
 * @property-read OrderShippedStatus|null $status
 * @method static Builder|OrderShippedDriver newModelQuery()
 * @method static Builder|OrderShippedDriver newQuery()
 * @method static Builder|OrderShippedDriver query()
 * @method static Builder|ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|OrderShippedDriver whereCreatedAt($value)
 * @method static Builder|OrderShippedDriver whereCurrent($value)
 * @method static Builder|OrderShippedDriver whereDriverId($value)
 * @method static Builder|OrderShippedDriver whereOrderId($value)
 * @method static Builder|OrderShippedDriver wherePreOrderDataId($value)
 * @method static Builder|OrderShippedDriver whereResponseUrlHash($value)
 * @method static Builder|OrderShippedDriver whereStatusId($value)
 * @method static Builder|OrderShippedDriver whereSystemRatingDriverId($value)
 * @method static Builder|OrderShippedDriver whereUnix($value)
 * @method static Builder|OrderShippedDriver whereUpdatedAt($value)
 * @mixin Eloquent
 * @property int $order_shipped_driver_id
 * @method static Builder|OrderShippedDriver whereOrderingShipmentDriverId($value)
 * @property string $on_way_response_url_hash
 * @property string $in_place_response_url_hash
 * @method static Builder|OrderShippedDriver whereInPlaceResponseUrlHash($value)
 * @method static Builder|OrderShippedDriver whereOnWayResponseUrlHash($value)
 * @property string|null $accept_hash
 * @property string|null $on_way_hash
 * @property string|null $in_place_hash
 * @property string|null $in_order_hash
 * @property string|null $pause_hash
 * @property string|null $end_hash
 * @property string|null $update_cord_hash
 * @property-read Collection|OrderInProcessRoad[] $in_process_roads
 * @property-read int|null $in_process_roads_count
 * @property-read OrderOnWayRoad|null $on_way_road
 * @property-read Collection|OrderOnWayRoad[] $on_way_roads
 * @property-read int|null $on_way_roads_count
 * @property-read OrderInProcessRoad|null $process_road
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|OrderShippedDriver whereAcceptHash($value)
 * @method static Builder|OrderShippedDriver whereGoInOrderHash($value)
 * @method static Builder|OrderShippedDriver whereInPlaceHash($value)
 * @method static Builder|OrderShippedDriver whereOnWayHash($value)
 * @method static Builder|OrderShippedDriver whereOrderEndHash($value)
 * @method static Builder|OrderShippedDriver whereOrderPauseHash($value)
 * @method static Builder|OrderShippedDriver whereUpdateCordHash($value)
 * @method static Builder|OrderShippedDriver whereEndHash($value)
 * @method static Builder|OrderShippedDriver wherePauseHash($value)
 * @property string|null $reset_hash
 * @method static Builder|OrderShippedDriver whereResetHash($value)
 * @property int $estimated_rating_id
 * @property-read EstimatedRating $estimated_rating
 * @method static Builder|OrderShippedDriver whereEstimatedRatingId($value)
 * @property-read OrderInProcessRoad|null $in_process_road
 * @property int|null $distance
 * @property int|null $duration
 * @property array|null $road
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|OrderShippedDriver whereDistance($value)
 * @method static Builder|OrderShippedDriver whereDuration($value)
 * @method static Builder|OrderShippedDriver whereOrderShippedDriverId($value)
 * @method static Builder|OrderShippedDriver whereRoad($value)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property-read OrderProcess|null $process
 * @property int|null $late
 * @method static Builder|OrderShippedDriver whereLate($value)
 * @property int $common
 * @property-read OrderAttach|null $attach
 * @method static Builder|OrderShippedDriver whereCommon($value)
 * @method static Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static Builder|OrderShippedDriver whereInOrderHash($value)
 * @property-read \Src\Models\Order\PreOrder $preorder
 */
	class OrderShippedDriver extends \Eloquent {}
}

namespace Src\Models\Order{
/**
 * Class OrderShippedStatus
 *
 * @package Src\Models\Order
 * @property int $pre_order_data_status_id
 * @property int $status
 * @property string $name
 * @property string $color
 * @property string $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|OrderShippedDriver[] $pre_orders
 * @property-read int|null $pre_orders_count
 * @method static Builder|OrderShippedStatus newModelQuery()
 * @method static Builder|OrderShippedStatus newQuery()
 * @method static Builder|OrderShippedStatus query()
 * @method static Builder|ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|OrderShippedStatus whereColor($value)
 * @method static Builder|OrderShippedStatus whereCreatedAt($value)
 * @method static Builder|OrderShippedStatus whereDescription($value)
 * @method static Builder|OrderShippedStatus whereName($value)
 * @method static Builder|OrderShippedStatus wherePreOrderDataStatusId($value)
 * @method static Builder|OrderShippedStatus whereStatus($value)
 * @method static Builder|OrderShippedStatus whereUpdatedAt($value)
 * @mixin Eloquent
 * @property int $ordering_shipment_driver_status_id
 * @property-read Collection|OrderShippedDriver[] $orders_shipment_driver
 * @property-read int|null $orders_shipment_driver_count
 * @method static Builder|OrderShippedStatus whereOrderingShipmentDriverStatusId($value)
 * @method static Builder|ServiceModel except($values = [])
 * @property int $order_shipped_status_id
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|OrderShippedStatus whereOrderShippedStatusId($value)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property string $text
 * @method static Builder|OrderShippedStatus whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 */
	class OrderShippedStatus extends \Eloquent {}
}

namespace Src\Models\Order{
/**
 * Class OrderStageCord
 *
 * @package Src\Models\Order
 * @property int $order_stage_cord_id
 * @property int $order_id
 * @property array $accept
 * @property Carbon|null $accepted
 * @property array $on_way
 * @property Carbon|null $on_wayed
 * @property array $in_place
 * @property Carbon|null $in_placed
 * @property array $start
 * @property Carbon|null $started
 * @property array $pauses
 * @property array $end
 * @property Carbon|null $ended
 * @property-read Order $order
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, int $distance = 1)
 * @method static Builder|ServiceModel except(array $values = [])
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|OrderStageCord newModelQuery()
 * @method static Builder|OrderStageCord newQuery()
 * @method static Builder|OrderStageCord query()
 * @method static Builder|OrderStageCord whereAccept($value)
 * @method static Builder|OrderStageCord whereAccepted($value)
 * @method static Builder|OrderStageCord whereEnd($value)
 * @method static Builder|OrderStageCord whereEnded($value)
 * @method static Builder|OrderStageCord whereInPlace($value)
 * @method static Builder|OrderStageCord whereInPlaced($value)
 * @method static Builder|OrderStageCord whereOnWay($value)
 * @method static Builder|OrderStageCord whereOnWayed($value)
 * @method static Builder|OrderStageCord whereOrderId($value)
 * @method static Builder|OrderStageCord whereOrderStageCordId($value)
 * @method static Builder|OrderStageCord wherePauses($value)
 * @method static Builder|OrderStageCord whereStart($value)
 * @method static Builder|OrderStageCord whereStarted($value)
 * @mixin Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 */
	class OrderStageCord extends \Eloquent {}
}

namespace Src\Models\Order{
/**
 * Src\Models\Order\OrderStatus
 *
 * @property int $order_status_id
 * @property string $status
 * @property-read Collection|Order[] $orders
 * @property-read int|null $orders_count
 * @method static Builder|OrderStatus newModelQuery()
 * @method static Builder|OrderStatus newQuery()
 * @method static Builder|OrderStatus query()
 * @method static Builder|OrderStatus whereOrderStatusId($value)
 * @method static Builder|OrderStatus whereStatus($value)
 * @mixin Eloquent
 * @property string $color
 * @method static Builder|ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|OrderStatus whereColor($value)
 * @property string $name
 * @property string $text
 * @method static Builder|OrderStatus whereName($value)
 * @method static Builder|OrderStatus whereText($value)
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 */
	class OrderStatus extends \Eloquent {}
}

namespace Src\Models\Order{
/**
 * Src\Models\Order\OrderType
 *
 * @property int $order_type_id
 * @property string|null $name
 * @property-read Collection|Order[] $orders
 * @property-read int|null $orders_count
 * @method static Builder|OrderType newModelQuery()
 * @method static Builder|OrderType newQuery()
 * @method static Builder|OrderType query()
 * @method static Builder|OrderType whereName($value)
 * @method static Builder|OrderType whereOrderTypeId($value)
 * @mixin Eloquent
 * @method static Builder|ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel except($values = [])
 * @property string|null $text
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|OrderType whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
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
 */
	class OrderType extends \Eloquent {}
}

namespace Src\Models\Order{
/**
 * Src\Models\Order\OrderWorkerComment
 *
 * @property int $order_worker_comment_id
 * @property int $order_id
 * @property int $system_worker_id
 * @property string $text
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read SystemWorker $worker
 * @method static Builder|OrderWorkerComment newModelQuery()
 * @method static Builder|OrderWorkerComment newQuery()
 * @method static Builder|OrderWorkerComment query()
 * @method static Builder|OrderWorkerComment whereCreatedAt($value)
 * @method static Builder|OrderWorkerComment whereOrderId($value)
 * @method static Builder|OrderWorkerComment whereOrderWorkerCommentId($value)
 * @method static Builder|OrderWorkerComment whereSystemWorkerId($value)
 * @method static Builder|OrderWorkerComment whereText($value)
 * @method static Builder|OrderWorkerComment whereUpdatedAt($value)
 * @mixin Eloquent
 * @property int $driver
 * @method static Builder|OrderWorkerComment whereDriver($value)
 */
	class OrderWorkerComment extends \Eloquent {}
}

namespace Src\Models\Order{
/**
 * Src\Models\Order\PaymentType
 *
 * @property int $payment_type_id
 * @property int $type
 * @property string $name
 * @property string $text
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Src\Models\Order\Order[] $orders
 * @property-read int|null $orders_count
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, int $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel except(array $values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentType newQuery()
 * @method static \Illuminate\Database\Query\Builder|PaymentType onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentType query()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentType whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentType wherePaymentTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentType whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentType whereType($value)
 * @method static \Illuminate\Database\Query\Builder|PaymentType withTrashed()
 * @method static \Illuminate\Database\Query\Builder|PaymentType withoutTrashed()
 * @mixin \Eloquent
 */
	class PaymentType extends \Eloquent {}
}

namespace Src\Models\Order{
/**
 * Class PreOrder
 *
 * @package Src\Models\Order
 * @property int $order_schedule_id
 * @property int $order_id
 * @property string|null $start_time
 * @property string|null $create_time
 * @property string|null $time
 * @property-read Order $order
 * @property-read int|null $schedule_drivers_count
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|PreOrder newModelQuery()
 * @method static Builder|PreOrder newQuery()
 * @method static Builder|PreOrder query()
 * @method static Builder|PreOrder whereCreateTime($value)
 * @method static Builder|PreOrder whereOrderId($value)
 * @method static Builder|PreOrder whereOrderScheduleId($value)
 * @method static Builder|PreOrder whereStartTime($value)
 * @method static Builder|PreOrder whereTime($value)
 * @method static Builder|PreOrder whereTimeZone($value)
 * @mixin Eloquent
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property int $preorder_id
 * @property array|null $driver
 * @property int $diff_minute
 * @property int $accept
 * @property Carbon|null $accepted
 * @property Carbon $created_at
 * @property-read Collection|OrderShippedDriver[] $shipped
 * @property-read int|null $shipped_count
 * @method static Builder|PreOrder whereAccept($value)
 * @method static Builder|PreOrder whereAccepted($value)
 * @method static Builder|PreOrder whereCreatedAt($value)
 * @method static Builder|PreOrder whereDiffMinute($value)
 * @method static Builder|PreOrder whereDriver($value)
 * @method static Builder|PreOrder wherePreorderId($value)
 * @property Carbon|null $distribution_start
 * @property bool $active
 * @property int $changed
 * @property-read OrderInitialData $initial
 * @property-read Collection|OrderShippedDriver[] $shippeds
 * @property-read int|null $shippeds_count
 * @method static Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static Builder|PreOrder whereActive($value)
 * @method static Builder|PreOrder whereChanged($value)
 * @method static Builder|PreOrder whereDistributionStart($value)
 * @property bool|null $skip
 * @property-read \Src\Models\Order\OrderCommon|null $common
 * @property-read Collection|\Src\Models\Order\OrderCommon[] $commons
 * @property-read int|null $commons_count
 * @method static Builder|PreOrder whereSkip($value)
 */
	class PreOrder extends \Eloquent {}
}

namespace Src\Models\Order{
/**
 * Class ProcessRealRoad
 *
 * @package Src\Models\Order
 * @method static \Illuminate\Database\Eloquent\Builder|\ServiceEntity\Models\ServiceModel except($values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Order\ProcessRealRoad newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Order\ProcessRealRoad newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Order\ProcessRealRoad query()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
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
 */
	class ProcessRealRoad extends \Eloquent {}
}

namespace Src\Models{
/**
 * Class Park
 *
 * @package Src\Models
 * @property int $park_id
 * @property int $entity_id
 * @property string|null $name
 * @property int|null $city_id
 * @property string|null $address
 * @property string|null $image
 * @property int|null $owner_id
 * @property int|null $franchise_id
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Car[] $cars
 * @property-read int|null $cars_count
 * @property-read City|null $city
 * @property-read Collection|Driver[] $drivers
 * @property-read int|null $drivers_count
 * @property-read LegalEntity $entity
 * @property-read Franchise $franchise
 * @property-read Collection|Order[] $orders
 * @property-read int|null $orders_count
 * @property-read SystemWorker|null $parkManager
 * @property-read Terminal|null $terminal
 * @property-read Collection|Waybill[] $waybills
 * @property-read int|null $waybills_count
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|Park newModelQuery()
 * @method static Builder|Park newQuery()
 * @method static \Illuminate\Database\Query\Builder|Park onlyTrashed()
 * @method static Builder|Park query()
 * @method static Builder|Park whereAddress($value)
 * @method static Builder|Park whereCityId($value)
 * @method static Builder|Park whereCreatedAt($value)
 * @method static Builder|Park whereDeletedAt($value)
 * @method static Builder|Park whereEntityId($value)
 * @method static Builder|Park whereFranchiseId($value)
 * @method static Builder|Park whereImage($value)
 * @method static Builder|Park whereName($value)
 * @method static Builder|Park whereOwnerId($value)
 * @method static Builder|Park whereParkId($value)
 * @method static Builder|Park whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Park withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Park withoutTrashed()
 * @mixin Eloquent
 * @property-read SystemWorker $manager
 * @property int|null $manager_id
 * @method static Builder|Park whereManagerId($value)
 * @method static Builder|ServiceModel cordDistance($latitude, $longitude)
 */
	class Park extends \Eloquent {}
}

namespace Src\Models\PayCards{
/**
 * Class PayCard
 *
 * @package Src\Models\PayCards
 * @property int $pay_card_id
 * @property int $temporary_pay_card_id
 * @property int $owner_id
 * @property string $owner_type
 * @property string|null $card_number
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $owner
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, int $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel except(array $values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static \Illuminate\Database\Eloquent\Builder|PayCard newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PayCard newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PayCard query()
 * @method static \Illuminate\Database\Eloquent\Builder|PayCard whereCardNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayCard whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayCard whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayCard whereOwnerType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayCard wherePayCardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayCard whereTemporaryPayCardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PayCard whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class PayCard extends \Eloquent {}
}

namespace Src\Models\PayCards{
/**
 * Class TemporaryPayCard
 *
 * @package Src\Models\PayCards
 * @property int $temporary_pay_card_id
 * @property int $owner_id
 * @property string $owner_type
 * @property string $transaction_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $owner
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, int $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel except(array $values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static \Illuminate\Database\Eloquent\Builder|TemporaryPayCard newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TemporaryPayCard newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TemporaryPayCard query()
 * @method static \Illuminate\Database\Eloquent\Builder|TemporaryPayCard whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TemporaryPayCard whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TemporaryPayCard whereOwnerType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TemporaryPayCard whereTemporaryPayCardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TemporaryPayCard whereTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TemporaryPayCard whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class TemporaryPayCard extends \Eloquent {}
}

namespace Src\Models\Penalty{
/**
 * Src\Models\Penalty\Penalty
 *
 * @property int $penalty_id
 * @property int $debt_id
 * @property int $offense_id
 * @property string $offense_date
 * @property string $offense_time
 * @property string $offense_location
 * @property string $pay_bill_date
 * @property string $last_bill_date
 * @property int $status
 * @property string $lat
 * @property string $lut
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Debt $debt
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, int $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel except(array $values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static \Illuminate\Database\Eloquent\Builder|Penalty newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Penalty newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Penalty query()
 * @method static \Illuminate\Database\Eloquent\Builder|Penalty whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Penalty whereDebtId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Penalty whereLastBillDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Penalty whereLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Penalty whereLut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Penalty whereOffenseDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Penalty whereOffenseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Penalty whereOffenseLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Penalty whereOffenseTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Penalty wherePayBillDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Penalty wherePenaltyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Penalty whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Penalty whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Penalty extends \Eloquent {}
}

namespace Src\Models\RatingSystem{
/**
 * Class DriverRatingPattern
 *
 * @package Src\Models\RatingSystem
 * @property int $driver_rating_pattern_id
 * @property int $type
 * @property string $name
 * @property string $alias
 * @property string $description
 * @property float $value
 * @property string $symbol
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|DriverRatingPattern newModelQuery()
 * @method static Builder|DriverRatingPattern newQuery()
 * @method static Builder|DriverRatingPattern query()
 * @method static Builder|DriverRatingPattern whereAlias($value)
 * @method static Builder|DriverRatingPattern whereCreatedAt($value)
 * @method static Builder|DriverRatingPattern whereDescription($value)
 * @method static Builder|DriverRatingPattern whereDriverRatingPatternId($value)
 * @method static Builder|DriverRatingPattern whereName($value)
 * @method static Builder|DriverRatingPattern whereSymbol($value)
 * @method static Builder|DriverRatingPattern whereType($value)
 * @method static Builder|DriverRatingPattern whereUpdatedAt($value)
 * @method static Builder|DriverRatingPattern whereValue($value)
 * @mixin Eloquent
 * @property string $inc_dec
 * @property-read Collection|DriverRating[] $rejected_order_rating
 * @property-read int|null $rejected_order_rating_count
 * @method static Builder|DriverRatingPattern whereIncDec($value)
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
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
 */
	class DriverRatingPattern extends \Eloquent {}
}

namespace Src\Models\RatingSystem{
/**
 * Src\Models\RatingSystem\EstimatedRating
 *
 * @property int $estimated_rating_id
 * @property int $order_id
 * @property int $driver_id
 * @property float $added_rating
 * @property float $remove_rating
 * @property array|null $added_patterns
 * @property array|null $remove_patterns
 * @property mixed|null $outcome
 * @property Carbon $created_at
 * @property-read Driver $driver
 * @property-read Order $order
 * @property-read Collection|OrderShippedDriver[] $ordering_shipments_driver
 * @property-read int|null $ordering_shipments_driver_count
 * @method static Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, int $distance = 1)
 * @method static Builder|ServiceModel except(array $values = [])
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|EstimatedRating newModelQuery()
 * @method static Builder|EstimatedRating newQuery()
 * @method static Builder|EstimatedRating query()
 * @method static Builder|EstimatedRating whereAddedPatterns($value)
 * @method static Builder|EstimatedRating whereAddedRating($value)
 * @method static Builder|EstimatedRating whereCreatedAt($value)
 * @method static Builder|EstimatedRating whereDriverId($value)
 * @method static Builder|EstimatedRating whereEstimatedRatingId($value)
 * @method static Builder|EstimatedRating whereOrderId($value)
 * @method static Builder|EstimatedRating whereOutcome($value)
 * @method static Builder|EstimatedRating whereRemovePatterns($value)
 * @method static Builder|EstimatedRating whereRemoveRating($value)
 * @mixin Eloquent
 */
	class EstimatedRating extends \Eloquent {}
}

namespace Src\Models\Role{
/**
 * Src\Models\Franchise\FranchiseRole
 *
 * @property int $franchise_role_id
 * @property int $franchise_module_id
 * @property int $franchise_id
 * @property int $role_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|FranchiseRole newModelQuery()
 * @method static Builder|FranchiseRole newQuery()
 * @method static Builder|FranchiseRole query()
 * @method static Builder|FranchiseRole whereCreatedAt($value)
 * @method static Builder|FranchiseRole whereFranchiseId($value)
 * @method static Builder|FranchiseRole whereFranchiseModuleId($value)
 * @method static Builder|FranchiseRole whereFranchiseRoleId($value)
 * @method static Builder|FranchiseRole whereRoleId($value)
 * @method static Builder|FranchiseRole whereUpdatedAt($value)
 * @mixin Eloquent
 * @property-read Role $role
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
 */
	class FranchiseRole extends \Eloquent {}
}

namespace Src\Models\Role{
/**
 * Src\Models\Views\MenuRole
 *
 * @property int $menu_role_id
 * @property int|null $role_id
 * @property int|null $menu_id
 * @method static Builder|MenuRole newModelQuery()
 * @method static Builder|MenuRole newQuery()
 * @method static Builder|MenuRole query()
 * @method static Builder|MenuRole whereMenuId($value)
 * @method static Builder|MenuRole whereMenuRoleId($value)
 * @method static Builder|MenuRole whereRoleId($value)
 * @mixin Eloquent
 * @property int|null $permission_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, int $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel except(array $values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|MenuRole whereCreatedAt($value)
 * @method static Builder|MenuRole wherePermissionId($value)
 */
	class MenuRole extends \Eloquent {}
}

namespace Src\Models\Role{
/**
 * Class Permission
 *
 * @property int $permission_id
 * @property int $role_id
 * @property int $homepage_route_id
 * @property string $name
 * @property string $guard_name
 * @property string|null $alias
 * @property string|null $description
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Role|null $role
 * @property-read \Illuminate\Database\Eloquent\Collection|Role[] $roles
 * @property-read int|null $roles_count
 * @property-read Route $route
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission newQuery()
 * @method static Builder|Permission onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereAlias($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereGuardName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereHomepageRouteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission wherePermissionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereRouteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereUpdatedAt($value)
 * @method static Builder|Permission withTrashed()
 * @method static Builder|Permission withoutTrashed()
 * @mixin Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel except($values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property int|null $route_id
 * @property-read \Illuminate\Database\Eloquent\Collection|Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|SystemWorker[] $users
 * @property-read int|null $users_count
 * @property string $text
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission role($roles, $guard = null)
 */
	class Permission extends \Eloquent implements \Src\Core\Contracts\PermissionModelContract {}
}

namespace Src\Models\Role{
/**
 * Class Role
 *
 * @package Src\Models\Role
 * @property int $role_id
 * @property int|null $module_id
 * @property int|null $homepage_route_id
 * @property string $name
 * @property string|null $alias
 * @property string|null $description
 * @property string $guard_name
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Route|null $homepage_route
 * @property-read Collection|\Src\Models\Menu[] $menus
 * @property-read int|null $menus_count
 * @property-read Module|null $module
 * @property-read Collection|Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read Collection|Route[] $routes
 * @property-read int|null $routes_count
 * @property-read Collection|SystemWorker[] $system_workers
 * @property-read int|null $system_workers_count
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|Role newModelQuery()
 * @method static Builder|Role newQuery()
 * @method static \Illuminate\Database\Query\Builder|Role onlyTrashed()
 * @method static Builder|Role permission($permissions)
 * @method static Builder|Role query()
 * @method static Builder|Role whereAlias($value)
 * @method static Builder|Role whereCreatedAt($value)
 * @method static Builder|Role whereDeletedAt($value)
 * @method static Builder|Role whereDescription($value)
 * @method static Builder|Role whereGuardName($value)
 * @method static Builder|Role whereHomepageRouteId($value)
 * @method static Builder|Role whereModuleId($value)
 * @method static Builder|Role whereName($value)
 * @method static Builder|Role whereRoleId($value)
 * @method static Builder|Role whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Role withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Role withoutTrashed()
 * @mixin Eloquent
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @property string $text
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static Builder|Role whereText($value)
 * @property-read Collection|FranchiseRole[] $franchise_roles
 * @property-read int|null $franchise_roles_count
 * @property-read Collection|Franchise[] $franchises
 * @property-read int|null $franchises_count
 * @property-read Collection|\Src\Models\Role\Permission[] $role_permissions
 * @property-read int|null $role_permissions_count
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 */
	class Role extends \Eloquent implements \Src\Core\Contracts\RoleModelContract {}
}

namespace Src\Models\Role{
/**
 * Class WorkerPermission
 *
 * @package Src\Models\Role
 * @property int $worker_permission_id
 * @property int $worker_id
 * @property int $permission_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Permission|null $permission
 * @property-read SystemWorker $worker
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|WorkerPermission newModelQuery()
 * @method static Builder|WorkerPermission newQuery()
 * @method static Builder|WorkerPermission query()
 * @method static Builder|WorkerPermission whereCreatedAt($value)
 * @method static Builder|WorkerPermission wherePermissionId($value)
 * @method static Builder|WorkerPermission whereUpdatedAt($value)
 * @method static Builder|WorkerPermission whereWorkerId($value)
 * @method static Builder|WorkerPermission whereWorkerPermissionId($value)
 * @mixin Eloquent
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property int $worker_role_id
 * @method static Builder|WorkerPermission whereWorkerRoleId($value)
 * @property int $system_worker_id
 * @method static Builder|WorkerPermission whereSystemWorkerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 */
	class WorkerPermission extends \Eloquent {}
}

namespace Src\Models\Role{
/**
 * Class WorkerRole
 *
 * @package Src\Models
 * @property int $worker_role_id
 * @property int|null $system_worker_id
 * @property int|null $role_id
 * @property mixed $permission_ids
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read Role|null $role
 * @property-read SystemWorker $worker
 * @method static Builder|WorkerRole newModelQuery()
 * @method static Builder|WorkerRole newQuery()
 * @method static Builder|WorkerRole query()
 * @method static Builder|WorkerRole whereCreatedAt($value)
 * @method static Builder|WorkerRole wherePermissionIds($value)
 * @method static Builder|WorkerRole whereRoleId($value)
 * @method static Builder|WorkerRole whereSystemWorkerId($value)
 * @method static Builder|WorkerRole whereUpdatedAt($value)
 * @method static Builder|WorkerRole whereWorkerRoleId($value)
 * @mixin Eloquent
 * @method static Builder|ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property-read Collection|WorkerPermission[] $worker_permissions
 * @property-read int|null $worker_permissions_count
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 */
	class WorkerRole extends \Eloquent {}
}

namespace Src\Models\Secure{
/**
 * Class ApiKey
 *
 * @package Src\Models
 * @property int $api_key_id
 * @property string $name
 * @property int $type
 * @property string $url
 * @property array $params
 * @property int|null $iterator
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, int $distance = 1)
 * @method static Builder|ServiceModel except(array $values = [])
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ApiKey newModelQuery()
 * @method static Builder|ApiKey newQuery()
 * @method static Builder|ApiKey query()
 * @method static Builder|ApiKey whereApiKeyId($value)
 * @method static Builder|ApiKey whereCreatedAt($value)
 * @method static Builder|ApiKey whereIterator($value)
 * @method static Builder|ApiKey whereName($value)
 * @method static Builder|ApiKey whereParams($value)
 * @method static Builder|ApiKey whereType($value)
 * @method static Builder|ApiKey whereUpdatedAt($value)
 * @method static Builder|ApiKey whereUrl($value)
 * @mixin Eloquent
 */
	class ApiKey extends \Eloquent {}
}

namespace Src\Models\Secure{
/**
 * Src\Models\Firewall
 *
 * @property int $firewall_id
 * @property int $ip
 * @property int $blocked
 * @property string|null $url
 * @property string $created_at
 * @method static Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, int $distance = 1)
 * @method static Builder|ServiceModel except(array $values = [])
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|Firewall newModelQuery()
 * @method static Builder|Firewall newQuery()
 * @method static Builder|Firewall query()
 * @method static Builder|Firewall whereBlocked($value)
 * @method static Builder|Firewall whereCreatedAt($value)
 * @method static Builder|Firewall whereFirewallId($value)
 * @method static Builder|Firewall whereIp($value)
 * @method static Builder|Firewall whereUrl($value)
 * @mixin Eloquent
 */
	class Firewall extends \Eloquent {}
}

namespace Src\Models\Secure{
/**
 * Src\Models\Versioning
 *
 * @property int $version_id
 * @property string|null $version
 * @property int|null $app
 * @property int|null $device
 * @property int|null $state
 * @property string|null $auth_key
 * @property Carbon|null $updated_at
 * @method static Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, int $distance = 1)
 * @method static Builder|ServiceModel except(array $values = [])
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|Versioning newModelQuery()
 * @method static Builder|Versioning newQuery()
 * @method static Builder|Versioning query()
 * @method static Builder|Versioning whereApp($value)
 * @method static Builder|Versioning whereAuthKey($value)
 * @method static Builder|Versioning whereDevice($value)
 * @method static Builder|Versioning whereState($value)
 * @method static Builder|Versioning whereUpdatedAt($value)
 * @method static Builder|Versioning whereVersion($value)
 * @method static Builder|Versioning whereVersionId($value)
 * @mixin Eloquent
 */
	class Versioning extends \Eloquent {}
}

namespace Src\Models\SystemUsers{
/**
 * Class ApiClient
 *
 * @package Src\Models\Api
 * @property int $api_client_id
 * @property string $name
 * @property string $secret
 * @property int $type
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Client[] $clients
 * @property-read int|null $clients_count
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read Token $oauth_access_token
 * @property-read Collection|Role[] $roles
 * @property-read int|null $roles_count
 * @property-read Collection|Token[] $tokens
 * @property-read int|null $tokens_count
 * @method static Builder|\Src\Models\Api\ApiClient newModelQuery()
 * @method static Builder|\Src\Models\Api\ApiClient newQuery()
 * @method static Builder|ServiceAuthenticable permission($permissions)
 * @method static Builder|\Src\Models\Api\ApiClient query()
 * @method static Builder|ServiceAuthenticable role($roles, $guard = null)
 * @method static Builder|\Src\Models\Api\ApiClient whereApiClientId($value)
 * @method static Builder|\Src\Models\Api\ApiClient whereCreatedAt($value)
 * @method static Builder|\Src\Models\Api\ApiClient whereName($value)
 * @method static Builder|\Src\Models\Api\ApiClient whereSecret($value)
 * @method static Builder|\Src\Models\Api\ApiClient whereType($value)
 * @method static Builder|\Src\Models\Api\ApiClient whereUpdatedAt($value)
 * @mixin Eloquent
 * @method static Builder|ServiceAuthenticable except($values = [])
 * @method static Builder|ServiceAuthenticable distance($latitude, $longitude)
 * @method static Builder|ServiceAuthenticable distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceAuthenticable geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceAuthenticable disatnceCordsss($latitude, $longitude, $distance)
 * @property-read Collection|Permission[] $permissions
 * @property-read int|null $permissions_count
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable comparison($geometryColumn, $geometry, $relationship)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable contains($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable cordDistance($latitude, $longitude)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable crosses($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable disjoint($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable distanceExcludingSelf($geometryColumn, $geometry, $distance)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable distanceSphere($geometryColumn, $geometry, $distance)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable distanceSphereExcludingSelf($geometryColumn, $geometry, $distance)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable distanceSphereValue($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable distanceValue($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable doesTouch($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable equals($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable intersects($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable orderByDistance($geometryColumn, $geometry, $direction = 'asc')
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable orderByDistanceSphere($geometryColumn, $geometry, $direction = 'asc')
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable orderBySpatial($geometryColumn, $geometry, $orderFunction, $direction = 'asc')
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable overlaps($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable within($geometryColumn, $polygon)
 */
	class ApiClient extends \Eloquent {}
}

namespace Src\Models\SystemUsers{
/**
 * Class SuperFranchiser
 *
 * @package Src\Models
 * @property-read ElasticquentCollection|SystemWorker[] $franchisers
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @method static Builder|SuperAdmin newModelQuery()
 * @method static Builder|SuperAdmin newQuery()
 * @method static Builder|SuperAdmin query()
 * @mixin Eloquent
 * @property int $super_admin_id
 * @property string|null $name
 * @property string|null $email
 * @property string|null $remember_token
 * @property string|null $password
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Client[] $clients
 * @property-read int|null $clients_count
 * @property-read Collection|Franchise[] $franchisee
 * @property-read int|null $franchisee_count
 * @property-read int|null $notifications_count
 * @property-read Image $profile_image
 * @property-read Collection|Role[] $roles
 * @property-read int|null $roles_count
 * @property-read Collection|Token[] $tokens
 * @property-read int|null $tokens_count
 * @method static Builder|ServiceAuthenticable permission($permissions)
 * @method static Builder|ServiceAuthenticable role($roles, $guard = null)
 * @method static Builder|SuperAdmin whereCreatedAt($value)
 * @method static Builder|SuperAdmin whereEmail($value)
 * @method static Builder|SuperAdmin whereName($value)
 * @method static Builder|SuperAdmin wherePassword($value)
 * @method static Builder|SuperAdmin whereRememberToken($value)
 * @method static Builder|SuperAdmin whereSuperAdminId($value)
 * @method static Builder|SuperAdmin whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceAuthenticable disatnceCordsss($latitude, $longitude, $distance)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceAuthenticable distance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceAuthenticable distanceCord($latitude, $longitude, $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceAuthenticable except($values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceAuthenticable geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @property-read Collection|\Src\Models\Role\Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read FcmClient|null $fcm
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable comparison($geometryColumn, $geometry, $relationship)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable contains($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable cordDistance($latitude, $longitude)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable crosses($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable disjoint($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable distanceExcludingSelf($geometryColumn, $geometry, $distance)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable distanceSphere($geometryColumn, $geometry, $distance)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable distanceSphereExcludingSelf($geometryColumn, $geometry, $distance)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable distanceSphereValue($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable distanceValue($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable doesTouch($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable equals($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable intersects($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable orderByDistance($geometryColumn, $geometry, $direction = 'asc')
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable orderByDistanceSphere($geometryColumn, $geometry, $direction = 'asc')
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable orderBySpatial($geometryColumn, $geometry, $orderFunction, $direction = 'asc')
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable overlaps($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable within($geometryColumn, $polygon)
 */
	class SuperAdmin extends \Eloquent {}
}

namespace Src\Models\SystemUsers{
/**
 * Class SystemWorker
 *
 * @package Src\Models
 * @property int $system_worker_id
 * @property int|null $franchise_id
 * @property int|null $is_admin
 * @property int|null $schedule_id график работы
 * @property string|null $name
 * @property string|null $surname
 * @property string|null $patronymic
 * @property string|null $nickname
 * @property string|null $email
 * @property string|null $remember_token
 * @property string|null $password
 * @property string|null $phone
 * @property string|null $description
 * @property string|null $photo
 * @property float|null $salary
 * @property float|null $prize
 * @property int|null $rating
 * @property int $logged
 * @property int $in_session
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|CanceledOrder[] $canceled_orders
 * @property-read int|null $canceled_orders_count
 * @property-read Collection|DriverCandidate[] $candidate_tutor
 * @property-read int|null $candidate_tutor_count
 * @property-read int|null $chat_participants_count
 * @property-read Collection|Room[] $chat_rooms
 * @property-read int|null $chat_rooms_count
 * @property-read Collection|Message[] $chat_sender
 * @property-read int|null $chat_sender_count
 * @property-read Collection|Client[] $clients
 * @property-read int|null $clients_count
 * @property-read CanceledOrder|null $current_canceled_order
 * @property-read Franchise|null $franchise
 * @property-read Franchise $franchise_worker
 * @property-read Franchise|null $franchisee_owner
 * @property-read Collection|WorkerSession[] $logged_sessions
 * @property-read int|null $logged_sessions_count
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read Collection|Order[] $orders
 * @property-read int|null $orders_count
 * @property-read Collection|Park[] $parks_owner_admin
 * @property-read int|null $parks_owner_admin_count
 * @property-read Image|null $profile_image
 * @property-read Collection|WorkerSession[] $quit_sessions
 * @property-read int|null $quit_sessions_count
 * @property-read Collection|Role[] $roles
 * @property-read int|null $roles_count
 * @property-read WorkerGraphic|null $schedule
 * @property-read Collection|Token[] $tokens
 * @property-read int|null $tokens_count
 * @property-read Collection|Waybill[] $waybills
 * @property-read int|null $waybills_count
 * @property-read WorkerDispatcher|null $worker_dispatcher
 * @property-read WorkerOperator|null $worker_operator
 * @property-read Collection|Permission[] $worker_permissions
 * @property-read int|null $worker_permissions_count
 * @property-read Collection|WorkerRole[] $worker_role_ids
 * @property-read int|null $worker_role_ids_count
 * @property-read Collection|Role[] $worker_roles
 * @property-read int|null $worker_roles_count
 * @method static Builder|ServiceAuthenticable disatnceCordsss($latitude, $longitude, $distance)
 * @method static Builder|ServiceAuthenticable distance($latitude, $longitude)
 * @method static Builder|ServiceAuthenticable distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceAuthenticable except($values = [])
 * @method static Builder|ServiceAuthenticable geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|SystemWorker newModelQuery()
 * @method static Builder|SystemWorker newQuery()
 * @method static \Illuminate\Database\Query\Builder|SystemWorker onlyTrashed()
 * @method static Builder|ServiceAuthenticable permission($permissions)
 * @method static Builder|SystemWorker query()
 * @method static Builder|ServiceAuthenticable role($roles, $guard = null)
 * @method static Builder|SystemWorker whereCreatedAt($value)
 * @method static Builder|SystemWorker whereDeletedAt($value)
 * @method static Builder|SystemWorker whereDescription($value)
 * @method static Builder|SystemWorker whereEmail($value)
 * @method static Builder|SystemWorker whereFranchiseId($value)
 * @method static Builder|SystemWorker whereInSession($value)
 * @method static Builder|SystemWorker whereIsAdmin($value)
 * @method static Builder|SystemWorker whereLogged($value)
 * @method static Builder|SystemWorker whereName($value)
 * @method static Builder|SystemWorker whereNickname($value)
 * @method static Builder|SystemWorker wherePassword($value)
 * @method static Builder|SystemWorker wherePatronymic($value)
 * @method static Builder|SystemWorker wherePhone($value)
 * @method static Builder|SystemWorker wherePhoto($value)
 * @method static Builder|SystemWorker wherePrize($value)
 * @method static Builder|SystemWorker whereRating($value)
 * @method static Builder|SystemWorker whereRememberToken($value)
 * @method static Builder|SystemWorker whereSalary($value)
 * @method static Builder|SystemWorker whereScheduleId($value)
 * @method static Builder|SystemWorker whereSurname($value)
 * @method static Builder|SystemWorker whereSystemWorkerId($value)
 * @method static Builder|SystemWorker whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|SystemWorker withTrashed()
 * @method static \Illuminate\Database\Query\Builder|SystemWorker withoutTrashed()
 * @mixin Eloquent
 * @property-read Collection|Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property int|null $graphic_id график работы
 * @method static Builder|SystemWorker whereGraphicId($value)
 * @property-read FcmClient|null $fcm
 * @property-read Collection|FranchiseTransaction[] $transactions
 * @property-read int|null $transactions_count
 * @property-read string $full_name
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceAuthenticable cordDistance($latitude, $longitude)
 */
	class SystemWorker extends \Eloquent {}
}

namespace Src\Models\SystemUsers{
/**
 * Src\Models\SystemUsers\WorkerDispatcher
 *
 * @property int $worker_dispatcher_id
 * @property int $system_worker_id
 * @property int $franchise_sub_phone_id
 * @property int $atc_logged
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read FranchiseSubPhone $sub_phone
 * @property-read SystemWorker $system_worker
 * @method static Builder|WorkerDispatcher newModelQuery()
 * @method static Builder|WorkerDispatcher newQuery()
 * @method static Builder|WorkerDispatcher query()
 * @method static Builder|WorkerDispatcher whereAtcLogged($value)
 * @method static Builder|WorkerDispatcher whereCreatedAt($value)
 * @method static Builder|WorkerDispatcher whereFranchiseSubPhoneId($value)
 * @method static Builder|WorkerDispatcher whereSystemWorkerId($value)
 * @method static Builder|WorkerDispatcher whereUpdatedAt($value)
 * @method static Builder|WorkerDispatcher whereWorkerDispatcherId($value)
 * @mixin Eloquent
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 */
	class WorkerDispatcher extends \Eloquent {}
}

namespace Src\Models\SystemUsers{
/**
 * Class WorkerGraphic
 *
 * @package Src\Models
 * @property int $worker_graphic_id
 * @property int $work_days_count
 * @property string $work_days
 * @property int $weekend_days_count
 * @property string $weekend_days enum('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')
 * @property array $opening_hours
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|SystemWorker[] $workers
 * @property-read int|null $workers_count
 * @method static Builder|WorkerGraphic newModelQuery()
 * @method static Builder|WorkerGraphic newQuery()
 * @method static Builder|WorkerGraphic query()
 * @method static Builder|WorkerGraphic whereCreatedAt($value)
 * @method static Builder|WorkerGraphic whereOpeningHours($value)
 * @method static Builder|WorkerGraphic whereUpdatedAt($value)
 * @method static Builder|WorkerGraphic whereWeekendDays($value)
 * @method static Builder|WorkerGraphic whereWeekendDaysCount($value)
 * @method static Builder|WorkerGraphic whereWorkDays($value)
 * @method static Builder|WorkerGraphic whereWorkDaysCount($value)
 * @method static Builder|WorkerGraphic whereWorkerGraphicId($value)
 * @mixin Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\ServiceEntity\Models\ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static \Illuminate\Database\Eloquent\Builder|\ServiceEntity\Models\ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static \Illuminate\Database\Eloquent\Builder|\ServiceEntity\Models\ServiceModel except($values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
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
 */
	class WorkerGraphic extends \Eloquent {}
}

namespace Src\Models\SystemUsers{
/**
 * Src\Models\SystemUsers\WorkerOperator
 *
 * @property int $worker_operator_id
 * @property int $system_worker_id
 * @property int $franchise_sub_phone_id
 * @property int $active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|WorkerOperator newModelQuery()
 * @method static Builder|WorkerOperator newQuery()
 * @method static Builder|WorkerOperator query()
 * @method static Builder|WorkerOperator whereActive($value)
 * @method static Builder|WorkerOperator whereCreatedAt($value)
 * @method static Builder|WorkerOperator whereFranchiseSubPhoneId($value)
 * @method static Builder|WorkerOperator whereSystemWorkerId($value)
 * @method static Builder|WorkerOperator whereUpdatedAt($value)
 * @method static Builder|WorkerOperator whereWorkerOperatorId($value)
 * @mixin Eloquent
 * @property int $atc_logged
 * @property-read FranchiseSubPhone $sub_phone
 * @property-read SystemWorker $system_worker
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|WorkerOperator whereAtcLogged($value)
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static Builder|ServiceModel cordDistance($latitude, $longitude)
 */
	class WorkerOperator extends \Eloquent {}
}

namespace Src\Models\SystemWorker{
/**
 * Class WorkerSession
 *
 * @package Src\Models\SystemWorker
 * @property int $worker_session_id
 * @property int|null $quit_worker_id
 * @property int|null $logged_worker_id
 * @property string $token
 * @property \Illuminate\Support\Carbon|null $quit_time
 * @property \Illuminate\Support\Carbon|null $logged_time
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\ServiceEntity\Models\ServiceModel except($values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\SystemWorker\WorkerSession newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\SystemWorker\WorkerSession newQuery()
 * @method static \Illuminate\Database\Query\Builder|\Src\Models\SystemWorker\WorkerSession onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\SystemWorker\WorkerSession query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\SystemWorker\WorkerSession whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\SystemWorker\WorkerSession whereLoggedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\SystemWorker\WorkerSession whereLoggedWorkerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\SystemWorker\WorkerSession whereQuitTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\SystemWorker\WorkerSession whereQuitWorkerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\SystemWorker\WorkerSession whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\SystemWorker\WorkerSession whereWorkerSessionId($value)
 * @method static \Illuminate\Database\Query\Builder|\Src\Models\SystemWorker\WorkerSession withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\Src\Models\SystemWorker\WorkerSession withoutTrashed()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
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
 */
	class WorkerSession extends \Eloquent {}
}

namespace Src\Models\Tariff{
/**
 * Class CompanyTariff
 *
 * @package Src\Models
 * @property int $company_tariff_id
 * @property int $company_id
 * @property int $tariff_id
 * @property int $franchise_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|CompanyTariff newModelQuery()
 * @method static Builder|CompanyTariff newQuery()
 * @method static Builder|CompanyTariff query()
 * @method static Builder|CompanyTariff whereCompanyId($value)
 * @method static Builder|CompanyTariff whereCompanyTariffId($value)
 * @method static Builder|CompanyTariff whereCreatedAt($value)
 * @method static Builder|CompanyTariff whereFranchiseId($value)
 * @method static Builder|CompanyTariff whereTariffId($value)
 * @method static Builder|CompanyTariff whereUpdatedAt($value)
 * @mixin Eloquent
 * @method static Builder|ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
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
 */
	class CompanyTariff extends \Eloquent {}
}

namespace Src\Models\Tariff{
/**
 * Class Tariff
 *
 * @package Src\Models
 * @property int $tariff_id
 * @property int $region_id
 * @property int $car_class_id
 * @property int $tariff_type_id
 * @property string $name
 * @property int $is_default
 * @property int|null $price
 * @property int|null $price_minute
 * @property int|null $minimal_price
 * @property int|null $intent_minute
 * @property int|null $intent
 * @property int|null $sit_price_km
 * @property int|null $sit_price_minute
 * @property int|null $free_wait_every_stop
 * @property int|null $paid_wait_every_stop
 * @property int|null $enable_speed_wait
 * @property string|null $rounding_price
 * @property int|null $free_wait
 * @property int|null $paid_wait
 * @property int $tool_roads_client
 * @property int $paid_parking_client
 * @property string|null $date_to
 * @property int $status
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read TariffRegionCity $behindCity
 * @property-read TariffRegionBehind $behindMkad
 * @property-read CarClass $cars
 * @property-read Collection|City[] $cities
 * @property-read int|null $cities_count
 * @property-read Collection|Company[] $companies
 * @property-read int|null $companies_count
 * @property-read Collection|TariffDestination[] $destinations
 * @property-read int|null $destinations_count
 * @property-read Region $region
 * @property-read TariffPriceType $tariffType
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff newQuery()
 * @method static Builder|Tariff onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff whereCarClassId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff whereDateTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff whereEnableSpeedWait($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff whereFreeWait($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff whereFreeWaitEveryStop($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff whereIntent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff whereIntentMinute($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff whereIsDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff whereMinimalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff wherePaidParkingClient($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff wherePaidWait($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff wherePaidWaitEveryStop($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff wherePriceMinute($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff whereRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff whereRoundingPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff whereSitPriceKm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff whereSitPriceMinute($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff whereTariffId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff whereTariffTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff whereToolRoadsClient($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff whereUpdatedAt($value)
 * @method static Builder|Tariff withTrashed()
 * @method static Builder|Tariff withoutTrashed()
 * @mixin Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @property int $payment_type_id
 * @property array|null $city
 * @property int|null $tariffable_id
 * @property string|null $tariffable_type
 * @property int|null $free_wait_minutes initial wait minutes
 * @property float|null $paid_wait_minute initial wait price every minute
 * @property string|null $date_from
 * @property-read Model|Eloquent $current_tariff
 * @property-read OrderInitialData|null $initial
 * @property-read OrderInitialData|null $secondary
 * @property-read TariffRegionBehind|null $tariff_behind
 * @property-read Collection|TariffDestination[] $tariff_destinations
 * @property-read int|null $tariff_destinations_count
 * @property-read TariffRegionCity|null $tariff_region
 * @property-read TariffPriceType $tariff_type
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel except($values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff whereDateFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff whereFreeWaitMinutes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff wherePaidWaitMinute($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff wherePaymentTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff whereTariffableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff whereTariffableType($value)
 * @property int|null $country_id
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff whereCountryId($value)
 * @property-read Country|null $country
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property-read TariffRegionCity|null $tariff_behind_region
 * @property-read CarClass $car_class
 * @property-read ClassOptionTariff $class_option
 * @property float|null $diff_percent
 * @property-read TariffRent|null $rent
 * @property-read TariffDestination|null $tariff_destination
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff whereDiffPercent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff whereRegion($value)
 * @property string|null $limit_manually_cost
 * @property-read int|null $class_option_count
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff getArea($tariff_id)
 * @method static \Illuminate\Database\Eloquent\Builder|Tariff whereLimitManuallyCost($value)
 */
	class Tariff extends \Eloquent {}
}

namespace Src\Models\Tariff{
/**
 * Class TariffDestination
 *
 * @package Src\Models
 * @property int $destination_id
 * @property int $tariff_id
 * @property int $car_class_id
 * @property int $price
 * @property int|null $free_wait
 * @property int|null $paid_wait
 * @property string $address_from
 * @property string $address_to
 * @property object|null $locations
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read CarClass $carClass
 * @property-read Tariff $tariff
 * @method static Builder|TariffDestination newModelQuery()
 * @method static Builder|TariffDestination newQuery()
 * @method static Builder|TariffDestination query()
 * @method static Builder|TariffDestination whereAddressFrom($value)
 * @method static Builder|TariffDestination whereAddressTo($value)
 * @method static Builder|TariffDestination whereCarClassId($value)
 * @method static Builder|TariffDestination whereCreatedAt($value)
 * @method static Builder|TariffDestination whereDestinationId($value)
 * @method static Builder|TariffDestination whereFreeWait($value)
 * @method static Builder|TariffDestination whereLocations($value)
 * @method static Builder|TariffDestination wherePaidWait($value)
 * @method static Builder|TariffDestination wherePrice($value)
 * @method static Builder|TariffDestination whereTariffId($value)
 * @method static Builder|TariffDestination whereUpdatedAt($value)
 * @mixin Eloquent
 * @method static Builder|ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @property int $tariff_destination_id
 * @property int|null $destination_from_id
 * @property int|null $destination_to_id
 * @property int|null $free_wait_stop_minutes
 * @property float|null $paid_wait_stop_minute
 * @property-read Collection|Area[] $areas
 * @property-read int|null $areas_count
 * @property-read Area|null $from_area
 * @property-read Area|null $to_area
 * @property-read Tariff|null $to_tariff
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|TariffDestination whereDestinationFromId($value)
 * @method static Builder|TariffDestination whereDestinationToId($value)
 * @method static Builder|TariffDestination whereFreeWaitStopMinutes($value)
 * @method static Builder|TariffDestination wherePaidWaitStopMinute($value)
 * @method static Builder|TariffDestination whereTariffDestinationId($value)
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property int|null $change_initial_price_percent
 * @method static Builder|TariffDestination whereChangeInitialPricePercent($value)
 * @property int $price_type_id
 * @property int $sitting_fee
 * @property string $cancel_fee
 * @property string|null $sit_price_km
 * @property string|null $sit_fix_price
 * @property string|null $sit_price_minute
 * @method static Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static Builder|TariffDestination whereCancelFee($value)
 * @method static Builder|TariffDestination wherePriceTypeId($value)
 * @method static Builder|TariffDestination whereSitFixPrice($value)
 * @method static Builder|TariffDestination whereSitPriceKm($value)
 * @method static Builder|TariffDestination whereSitPriceMinute($value)
 * @method static Builder|TariffDestination whereSittingFee($value)
 */
	class TariffDestination extends \Eloquent {}
}

namespace Src\Models\Tariff{
/**
 * Class TariffPriceType
 *
 * @package Src\Models\Tariff
 * @property int $tariff_type_id
 * @property string $name
 * @property string $type
 * @property int $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read TariffRegionBehind $behindMkad
 * @property-read Collection|Tariff[] $tariffs
 * @property-read int|null $tariffs_count
 * @method static Builder|TariffPriceType newModelQuery()
 * @method static Builder|TariffPriceType newQuery()
 * @method static Builder|TariffPriceType query()
 * @method static Builder|TariffPriceType whereCreatedAt($value)
 * @method static Builder|TariffPriceType whereName($value)
 * @method static Builder|TariffPriceType whereStatus($value)
 * @method static Builder|TariffPriceType whereTariffTypeId($value)
 * @method static Builder|TariffPriceType whereType($value)
 * @method static Builder|TariffPriceType whereUpdatedAt($value)
 * @mixin Eloquent
 * @method static Builder|ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
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
 */
	class TariffPriceType extends \Eloquent {}
}

namespace Src\Models\Tariff{
/**
 * Class TariffRegionBehind
 *
 * @package Src\Models\Tariff
 * @property int $tariff_behind_mkad_id
 * @property int $tariff_id
 * @property int $tariff_type_id
 * @property int|null $price_distance_1_15
 * @property int|null $price_distance_16_30
 * @property int|null $price_distance_31_60
 * @property int|null $price_distance_61_more
 * @property int|null $price_distance_1_15_minute
 * @property int|null $price_distance_16_30_minute
 * @property int|null $price_distance_31_60_minute
 * @property int|null $price_distance_61_more_minute
 * @property int|null $back
 * @property int $back_minute
 * @property int $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read TariffPriceType $tariffType
 * @property-read Tariff $tariffs
 * @method static Builder|TariffRegionBehind newModelQuery()
 * @method static Builder|TariffRegionBehind newQuery()
 * @method static Builder|TariffRegionBehind query()
 * @method static Builder|TariffRegionBehind whereBack($value)
 * @method static Builder|TariffRegionBehind whereBackMinute($value)
 * @method static Builder|TariffRegionBehind whereCreatedAt($value)
 * @method static Builder|TariffRegionBehind wherePriceDistance115($value)
 * @method static Builder|TariffRegionBehind wherePriceDistance115Minute($value)
 * @method static Builder|TariffRegionBehind wherePriceDistance1630($value)
 * @method static Builder|TariffRegionBehind wherePriceDistance1630Minute($value)
 * @method static Builder|TariffRegionBehind wherePriceDistance3160($value)
 * @method static Builder|TariffRegionBehind wherePriceDistance3160Minute($value)
 * @method static Builder|TariffRegionBehind wherePriceDistance61More($value)
 * @method static Builder|TariffRegionBehind wherePriceDistance61MoreMinute($value)
 * @method static Builder|TariffRegionBehind whereStatus($value)
 * @method static Builder|TariffRegionBehind whereTariffBehindMkadId($value)
 * @method static Builder|TariffRegionBehind whereTariffId($value)
 * @method static Builder|TariffRegionBehind whereTariffTypeId($value)
 * @method static Builder|TariffRegionBehind whereUpdatedAt($value)
 * @mixin Eloquent
 * @method static Builder|ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @property float $zone_distance
 * @property float $price_km
 * @property float $price_min
 * @property int|null $free_wait_stop_minutes
 * @property float|null $paid_wait_stop_minute
 * @property int|null $enable_speed_wait
 * @property int|null $speed_wait_limit
 * @property float|null $speed_wait_price_minute
 * @property-read Tariff $tariff
 * @property-read Tariff|null $to_tariff
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|TariffRegionBehind whereEnableSpeedWait($value)
 * @method static Builder|TariffRegionBehind whereFreeWaitStopMinutes($value)
 * @method static Builder|TariffRegionBehind wherePaidWaitStopMinute($value)
 * @method static Builder|TariffRegionBehind wherePriceKm($value)
 * @method static Builder|TariffRegionBehind wherePriceMin($value)
 * @method static Builder|TariffRegionBehind whereSpeedWaitLimit($value)
 * @method static Builder|TariffRegionBehind whereSpeedWaitPriceMinute($value)
 * @method static Builder|TariffRegionBehind whereZoneDistance($value)
 * @property int|null $sitting_fee
 * @property float|null $sit_price_km
 * @property float|null $sit_price_minute
 * @method static Builder|TariffRegionBehind whereSitPriceKm($value)
 * @method static Builder|TariffRegionBehind whereSitPriceMinute($value)
 * @method static Builder|TariffRegionBehind whereSittingFee($value)
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property int|null $change_initial_price_percent
 * @property int $merge_km_minute
 * @method static Builder|TariffRegionBehind whereChangeInitialPricePercent($value)
 * @method static Builder|TariffRegionBehind whereMergeKmMinute($value)
 * @property int $tariff_region_behind_id
 * @property int|null $tariff_region_id
 * @property int $price_type_id
 * @property int $sit_type_id
 * @property string|null $sit_fix_price
 * @property int $minimal_distance_value KM
 * @property int $minimal_duration_value MINUTE
 * @property-read TariffRegionCity|null $tariff_region
 * @property-read TariffPriceType $tariff_type
 * @property string $cancel_fee
 * @property-read Collection|TariffRent[] $rent_behind
 * @property-read int|null $rent_behind_count
 * @method static Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static Builder|TariffRegionBehind whereCancelFee($value)
 * @method static Builder|TariffRegionBehind whereMinimalDistanceValue($value)
 * @method static Builder|TariffRegionBehind whereMinimalDurationValue($value)
 * @method static Builder|TariffRegionBehind wherePriceTypeId($value)
 * @method static Builder|TariffRegionBehind whereSitFixPrice($value)
 * @method static Builder|TariffRegionBehind whereSitTypeId($value)
 * @method static Builder|TariffRegionBehind whereTariffRegionBehindId($value)
 * @method static Builder|TariffRegionBehind whereTariffRegionId($value)
 * @property-read Collection|\Src\Models\Tariff\TariffRent[] $tariff_rents
 * @property-read int|null $tariff_rents_count
 * @property-read \Src\Models\Tariff\TariffRentAlt|null $rent_alt
 */
	class TariffRegionBehind extends \Eloquent {}
}

namespace Src\Models\Tariff{
/**
 * Class TariffRegionCity
 *
 * @package Src\Models\Tariff
 * @property int $tariff_behind_city_id
 * @property int $tariff_id
 * @property int|null $price
 * @property int|null $price_minute
 * @property int|null $minimal_price
 * @property int|null $intent
 * @property int|null $sit_price_km
 * @property int|null $sit_price_minute
 * @property int|null $free_wait_every_stop
 * @property int|null $paid_wait_every_stop
 * @property int|null $free_wait
 * @property int|null $paid_wait
 * @property int|null $back
 * @property int|null $back_minute
 * @property string|null $rounding_price
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Tariff $tariffs
 * @method static Builder|TariffRegionCity newModelQuery()
 * @method static Builder|TariffRegionCity newQuery()
 * @method static Builder|TariffRegionCity query()
 * @method static Builder|TariffRegionCity whereBack($value)
 * @method static Builder|TariffRegionCity whereBackMinute($value)
 * @method static Builder|TariffRegionCity whereCreatedAt($value)
 * @method static Builder|TariffRegionCity whereFreeWait($value)
 * @method static Builder|TariffRegionCity whereFreeWaitEveryStop($value)
 * @method static Builder|TariffRegionCity whereIntent($value)
 * @method static Builder|TariffRegionCity whereMinimalPrice($value)
 * @method static Builder|TariffRegionCity wherePaidWait($value)
 * @method static Builder|TariffRegionCity wherePaidWaitEveryStop($value)
 * @method static Builder|TariffRegionCity wherePrice($value)
 * @method static Builder|TariffRegionCity wherePriceMinute($value)
 * @method static Builder|TariffRegionCity whereRoundingPrice($value)
 * @method static Builder|TariffRegionCity whereSitPriceKm($value)
 * @method static Builder|TariffRegionCity whereSitPriceMinute($value)
 * @method static Builder|TariffRegionCity whereTariffBehindCityId($value)
 * @method static Builder|TariffRegionCity whereTariffId($value)
 * @method static Builder|TariffRegionCity whereUpdatedAt($value)
 * @mixin Eloquent
 * @method static Builder|ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @property int $tariff_region_city_id
 * @property float|null $price_km
 * @property float|null $price_min
 * @property int|null $sitting_fee
 * @property int|null $free_wait_stop_minutes
 * @property float|null $paid_wait_stop_minute
 * @property int|null $enable_speed_wait
 * @property int|null $speed_wait_limit
 * @property float|null $speed_wait_price_minute
 * @property-read Tariff $tariff
 * @property-read Tariff|null $to_tariff
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|TariffRegionCity whereEnableSpeedWait($value)
 * @method static Builder|TariffRegionCity whereFreeWaitStopMinutes($value)
 * @method static Builder|TariffRegionCity wherePaidWaitStopMinute($value)
 * @method static Builder|TariffRegionCity wherePriceKm($value)
 * @method static Builder|TariffRegionCity wherePriceMin($value)
 * @method static Builder|TariffRegionCity whereSittingFee($value)
 * @method static Builder|TariffRegionCity whereSpeedWaitLimit($value)
 * @method static Builder|TariffRegionCity whereSpeedWaitPriceMinute($value)
 * @method static Builder|TariffRegionCity whereTariffRegionCityId($value)
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property int|null $minimal_distance_value
 * @property int|null $minimal_duration_value
 * @method static Builder|TariffRegionCity whereMinimalDistanceValue($value)
 * @method static Builder|TariffRegionCity whereMinimalDurationValue($value)
 * @property int|null $change_initial_price_percent
 * @property int $merge_km_minute
 * @method static Builder|TariffRegionCity whereChangeInitialPricePercent($value)
 * @method static Builder|TariffRegionCity whereMergeKmMinute($value)
 * @property int|null $area_id
 * @property int $price_type_id
 * @property int $sit_type_id
 * @property string|null $sit_fix_price
 * @property-read Area|null $area
 * @property-read TariffRegionBehind|null $behind
 * @property-read TariffPriceType $tariff_type
 * @property string $cancel_fee
 * @method static Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static Builder|TariffRegionCity whereAreaId($value)
 * @method static Builder|TariffRegionCity whereCancelFee($value)
 * @method static Builder|TariffRegionCity wherePriceTypeId($value)
 * @method static Builder|TariffRegionCity whereSitFixPrice($value)
 * @method static Builder|TariffRegionCity whereSitTypeId($value)
 * @property-read \Src\Models\Tariff\TariffRentAlt|null $rent_alt
 * @property-read \Illuminate\Database\Eloquent\Collection|\Src\Models\Tariff\TariffRent[] $tariff_rents
 * @property-read int|null $tariff_rents_count
 */
	class TariffRegionCity extends \Eloquent {}
}

namespace Src\Models\Tariff{
/**
 * Class TariffRent
 *
 * @package Src\Models\Tariff
 * @property int $tariff_rent_id
 * @property int $tariff_id
 * @property int|null $area_id
 * @property int|null $cancel_fee
 * @property float $zone_distance
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Tariff $tariff
 * @mixin Eloquent
 * @property int|null $price_type_id
 * @property int|null $sitting_fee
 * @property string|null $sit_fix_price
 * @property string|null $sit_price_km
 * @property string|null $sit_price_minute
 * @property int $hours
 * @property-read Area|null $area
 * @property-read Collection|TariffRegionBehind[] $behind
 * @property-read int|null $behind_count
 * @property-read Collection|TariffDestination[] $destination
 * @property-read int|null $destination_count
 * @property-read Collection|TariffRegionCity[] $region
 * @property-read int|null $region_count
 * @method static Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, int $distance = 1)
 * @method static Builder|ServiceModel except(array $values = [])
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|TariffRent newModelQuery()
 * @method static Builder|TariffRent newQuery()
 * @method static Builder|TariffRent query()
 * @method static Builder|TariffRent whereAreaId($value)
 * @method static Builder|TariffRent whereCancelFee($value)
 * @method static Builder|TariffRent whereCreatedAt($value)
 * @method static Builder|TariffRent whereHours($value)
 * @method static Builder|TariffRent wherePriceTypeId($value)
 * @method static Builder|TariffRent whereSitFixPrice($value)
 * @method static Builder|TariffRent whereSitPriceKm($value)
 * @method static Builder|TariffRent whereSitPriceMinute($value)
 * @method static Builder|TariffRent whereSittingFee($value)
 * @method static Builder|TariffRent whereTariffId($value)
 * @method static Builder|TariffRent whereTariffRentId($value)
 * @method static Builder|TariffRent whereUpdatedAt($value)
 * @method static Builder|TariffRent whereZoneDistance($value)
 * @property-read Collection|\Src\Models\Tariff\TariffRegionBehind[] $alt_behind
 * @property-read int|null $alt_behind_count
 * @property-read Collection|\Src\Models\Tariff\TariffDestination[] $alt_destination
 * @property-read int|null $alt_destination_count
 * @property-read Collection|\Src\Models\Tariff\TariffRegionCity[] $alt_region
 * @property-read int|null $alt_region_count
 * @property-read Collection|\Src\Models\Tariff\TariffRentAlt[] $rent_alts
 * @property-read int|null $rent_alts_count
 * @property-read \Src\Models\Tariff\Tariff|null $to_tariff
 */
	class TariffRent extends \Eloquent {}
}

namespace Src\Models\Tariff{
/**
 * Src\Models\Tariff\TariffRentAlt
 *
 * @property int $tariff_rent_alt_id
 * @property int $rent_id
 * @property int $alt_id
 * @property string|null $alt_type
 * @property int|null $type
 * @property string $created_at
 * @method static Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, int $distance = 1)
 * @method static Builder|ServiceModel except(array $values = [])
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|TariffRentAlt newModelQuery()
 * @method static Builder|TariffRentAlt newQuery()
 * @method static Builder|TariffRentAlt query()
 * @method static Builder|TariffRentAlt whereAltId($value)
 * @method static Builder|TariffRentAlt whereAltType($value)
 * @method static Builder|TariffRentAlt whereCreatedAt($value)
 * @method static Builder|TariffRentAlt whereRentId($value)
 * @method static Builder|TariffRentAlt whereTariffRentAltId($value)
 * @method static Builder|TariffRentAlt whereType($value)
 * @mixin Eloquent
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $altable
 * @property-read \Src\Models\Tariff\TariffRent $rent
 */
	class TariffRentAlt extends \Eloquent {}
}

namespace Src\Models{
/**
 * Class Task
 *
 * @package Src\Models
 * @property int $task_id
 * @property string $command
 * @property mixed $every
 * @property mixed|null $params
 * @property Carbon $created_at
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, int $distance = 1)
 * @method static Builder|ServiceModel except(array $values = [])
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|Task newModelQuery()
 * @method static Builder|Task newQuery()
 * @method static Builder|Task query()
 * @method static Builder|Task whereCommand($value)
 * @method static Builder|Task whereCreatedAt($value)
 * @method static Builder|Task whereEvery($value)
 * @method static Builder|Task whereParams($value)
 * @method static Builder|Task whereTaskId($value)
 * @mixin Eloquent
 * @property bool $status
 * @method static Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static Builder|Task whereStatus($value)
 */
	class Task extends \Eloquent {}
}

namespace Src\Models\Terminal{
/**
 * Class DebtRepayment
 *
 * @package Src\Models\Terminal
 * @property int $debt_repayment_id
 * @property int|null $amount
 * @property string|null $min_debt
 * @property string|null $max_debt
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|DebtRepayment newModelQuery()
 * @method static Builder|DebtRepayment newQuery()
 * @method static Builder|DebtRepayment query()
 * @method static Builder|DebtRepayment whereAmount($value)
 * @method static Builder|DebtRepayment whereDebtRepaymentId($value)
 * @method static Builder|DebtRepayment whereMaxDebt($value)
 * @method static Builder|DebtRepayment whereMinDebt($value)
 * @mixin Eloquent
 * @property-read Collection|Debt[] $debt
 * @property-read int|null $debt_count
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 */
	class DebtRepayment extends \Eloquent {}
}

namespace Src\Models\Terminal{
/**
 * Class Terminal
 *
 * @package Src\Models\Terminal
 * @property int $terminal_id
 * @property int|null $park_id
 * @property string|null $name
 * @property string|null $hash
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Client[] $clients
 * @property-read int|null $clients_count
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read Park|null $park
 * @property-read Collection|Role[] $roles
 * @property-read int|null $roles_count
 * @property-read Collection|Token[] $tokens
 * @property-read int|null $tokens_count
 * @method static Builder|ServiceAuthenticable except($values = [])
 * @method static Builder|Terminal newModelQuery()
 * @method static Builder|Terminal newQuery()
 * @method static Builder|ServiceAuthenticable permission($permissions)
 * @method static Builder|Terminal query()
 * @method static Builder|ServiceAuthenticable role($roles, $guard = null)
 * @method static Builder|Terminal whereCreatedAt($value)
 * @method static Builder|Terminal whereHash($value)
 * @method static Builder|Terminal whereName($value)
 * @method static Builder|Terminal whereParkId($value)
 * @method static Builder|Terminal whereTerminalId($value)
 * @method static Builder|Terminal whereUpdatedAt($value)
 * @mixin Eloquent
 * @property-read Collection|Waybill[] $waybills
 * @property-read int|null $waybills_count
 * @method static Builder|ServiceAuthenticable distance($latitude, $longitude)
 * @method static Builder|ServiceAuthenticable distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceAuthenticable geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceAuthenticable disatnceCordsss($latitude, $longitude, $distance)
 * @property int|null $auth_driver_id
 * @property string $password
 * @property-read Driver $auth_driver
 * @method static Builder|Terminal whereAuthDriverId($value)
 * @method static Builder|Terminal wherePassword($value)
 * @property-read Collection|Permission[] $permissions
 * @property-read int|null $permissions_count
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable comparison($geometryColumn, $geometry, $relationship)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable contains($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable cordDistance($latitude, $longitude)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable crosses($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable disjoint($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable distanceExcludingSelf($geometryColumn, $geometry, $distance)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable distanceSphere($geometryColumn, $geometry, $distance)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable distanceSphereExcludingSelf($geometryColumn, $geometry, $distance)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable distanceSphereValue($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable distanceValue($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable doesTouch($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable equals($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable intersects($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable orderByDistance($geometryColumn, $geometry, $direction = 'asc')
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable orderByDistanceSphere($geometryColumn, $geometry, $direction = 'asc')
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable orderBySpatial($geometryColumn, $geometry, $orderFunction, $direction = 'asc')
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable overlaps($geometryColumn, $geometry)
 * @method static \Grimzy\LaravelMysqlSpatial\Eloquent\Builder|ServiceAuthenticable within($geometryColumn, $polygon)
 */
	class Terminal extends \Eloquent {}
}

namespace Src\Models\Terminal{
/**
 * Class Waybill
 *
 * @package Src\Models\Models\Terminal
 * @property int $waybill_id
 * @property int|null $car_id
 * @property int|null $driver_id
 * @property int|null $waybill_transaction_id
 * @property int|null $system_worker_id relation system_workers
 * @property string|null $number
 * @property string|Carbon $start_time
 * @property string|Carbon $end_time
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|Waybill newModelQuery()
 * @method static Builder|Waybill newQuery()
 * @method static Builder|Waybill query()
 * @method static Builder|Waybill whereCarId($value)
 * @method static Builder|Waybill whereCreatedAt($value)
 * @method static Builder|Waybill whereDeletedAt($value)
 * @method static Builder|Waybill whereDriverId($value)
 * @method static Builder|Waybill whereEndTime($value)
 * @method static Builder|Waybill whereNumber($value)
 * @method static Builder|Waybill whereStartTime($value)
 * @method static Builder|Waybill whereSystemWorkerId($value)
 * @method static Builder|Waybill whereUpdatedAt($value)
 * @method static Builder|Waybill whereWaybillId($value)
 * @method static Builder|Waybill whereWaybillTransactionId($value)
 * @mixin Eloquent
 * @property-read Car|null $car
 * @property-read Driver|null $driver
 * @property int $verified
 * @property int $signed
 * @property Carbon|null $comment
 * @property-read Collection|CarReport[] $car_report
 * @property-read int|null $car_report_count
 * @property-read Collection|CarReportImage[] $car_report_images
 * @property-read int|null $car_report_images_count
 * @method static Builder|Waybill whereComment($value)
 * @method static Builder|Waybill whereSigned($value)
 * @method static Builder|Waybill whereVerified($value)
 * @property int|null $transaction_id
 * @property string|null $waybill
 * @property-read Collection|CarReport[] $car_reports
 * @property-read int|null $car_reports_count
 * @method static \Illuminate\Database\Query\Builder|Waybill onlyTrashed()
 * @method static Builder|Waybill whereTransactionId($value)
 * @method static Builder|Waybill whereWaybill($value)
 * @method static \Illuminate\Database\Query\Builder|Waybill withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Waybill withoutTrashed()
 * @property int|null $terminal_id
 * @property float $price
 * @property-read FranchiseTransaction|null $transaction
 * @property-read SystemWorker|null $worker
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static Builder|Waybill wherePrice($value)
 * @method static Builder|Waybill whereTerminalId($value)
 */
	class Waybill extends \Eloquent {}
}

namespace Src\Models\TransportStations{
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
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 */
	class Airport extends \Eloquent {}
}

namespace Src\Models\TransportStations{
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
	class Metro extends \Eloquent {}
}

namespace Src\Models\TransportStations{
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
	class RailwayStation extends \Eloquent {}
}

namespace Src\Models\Views{
/**
 * Class EmailTemplate
 *
 * @package Src\Models
 * @property int $email_template_id
 * @property int|null $type
 * @property string|null $subject
 * @property string|null $body
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|EmailTemplate newModelQuery()
 * @method static Builder|EmailTemplate newQuery()
 * @method static Builder|EmailTemplate query()
 * @method static Builder|EmailTemplate whereBody($value)
 * @method static Builder|EmailTemplate whereCreatedAt($value)
 * @method static Builder|EmailTemplate whereDescription($value)
 * @method static Builder|EmailTemplate whereEmailTemplateId($value)
 * @method static Builder|EmailTemplate whereSubject($value)
 * @method static Builder|EmailTemplate whereType($value)
 * @method static Builder|EmailTemplate whereUpdatedAt($value)
 * @mixin Eloquent
 * @method static Builder|ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel except($values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
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
 */
	class EmailTemplate extends \Eloquent {}
}

namespace Src\Models\Views{
/**
 * Class Image
 *
 * @package Src\Models
 * @property int $image_id
 * @property int|null $imageable_id
 * @property string|null $imageable_type
 * @property string|null $name
 * @property string|null $ext enum('jpg', 'jpeg', 'png')
 * @property string|null $path
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Image[] $imageable
 * @method static Builder|Image newModelQuery()
 * @method static Builder|Image newQuery()
 * @method static Builder|Image query()
 * @method static Builder|Image whereCreatedAt($value)
 * @method static Builder|Image whereExt($value)
 * @method static Builder|Image whereImageId($value)
 * @method static Builder|Image whereImageableId($value)
 * @method static Builder|Image whereImageableType($value)
 * @method static Builder|Image whereName($value)
 * @method static Builder|Image wherePath($value)
 * @method static Builder|Image whereUpdatedAt($value)
 * @mixin Eloquent
 * @property-read Collection|Image[] $franchise_logo
 * @property-read Collection|Image[] $candidate_img
 * @property-read Collection|Image[] $worker_img
 * @method static Builder|ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
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
 */
	class Image extends \Eloquent {}
}

namespace Src\Models\Views{
/**
 * Class Menu
 *
 * @package Src\Models
 * @property int $menu_id
 * @property int|null $route_id
 * @property int|null $parent_id
 * @property string|null $title
 * @property string|null $description
 * @property string|null $icon
 * @method static Builder|Menu newModelQuery()
 * @method static Builder|Menu newQuery()
 * @method static Builder|Menu query()
 * @method static Builder|Menu whereDescription($value)
 * @method static Builder|Menu whereIcon($value)
 * @method static Builder|Menu whereMenuId($value)
 * @method static Builder|Menu whereParentId($value)
 * @method static Builder|Menu whereRouteId($value)
 * @method static Builder|Menu whereTitle($value)
 * @mixin Eloquent
 * @property-read Collection|Role[] $roles
 * @property-read int|null $roles_count
 * @property-read Route|null $route
 * @method static Builder|ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|ServiceModel except($values = [])
 * @method static Builder|ServiceModel distance($latitude, $longitude)
 * @method static Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Menu whereCreatedAt($value)
 * @method static Builder|Menu whereUpdatedAt($value)
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
 * @property-read Collection|Menu[] $child
 * @property-read int|null $child_count
 * @property-read Menu|null $parent
 */
	class Menu extends \Eloquent {}
}

namespace Src\Models\Views{
/**
 * Class Route
 *
 * @property mixed $url
 * @package Src\Models\Views
 * @property int $route_id
 * @property string|null $namespace
 * @property string|null $name
 * @property string|null $type
 * @property string|null $alias
 * @property string|null $as
 * @property mixed|null $middleware
 * @property string|null $prefix
 * @property-read Collection|Role[] $role
 * @property-read int|null $role_count
 * @method static Builder|Route newModelQuery()
 * @method static Builder|Route newQuery()
 * @method static Builder|Route query()
 * @method static Builder|Route whereAlias($value)
 * @method static Builder|Route whereAs($value)
 * @method static Builder|Route whereMiddleware($value)
 * @method static Builder|Route whereName($value)
 * @method static Builder|Route whereNamespace($value)
 * @method static Builder|Route wherePrefix($value)
 * @method static Builder|Route whereRouteId($value)
 * @method static Builder|Route whereType($value)
 * @method static Builder|Route whereUrl($value)
 * @mixin Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\ServiceEntity\Models\ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static \Illuminate\Database\Eloquent\Builder|\ServiceEntity\Models\ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static \Illuminate\Database\Eloquent\Builder|\ServiceEntity\Models\ServiceModel except($values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
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
 */
	class Route extends \Eloquent {}
}

