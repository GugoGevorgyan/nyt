<?php

declare(strict_types=1);

namespace Src\Models\Terminal;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use ServiceEntity\Models\ServiceModel;
use Src\Models\Car\Car;
use Src\Models\CarReport\CarReport;
use Src\Models\CarReport\CarReportImage;
use Src\Models\Driver\Driver;
use Src\Models\Driver\DriverInfo;
use Src\Models\Franchise\FranchiseTransaction;
use Src\Models\Park;
use Src\Models\SystemUsers\SystemWorker;
use Znck\Eloquent\Relations\BelongsToThrough;

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
class Waybill extends ServiceModel
{

    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'waybills';
    /**
     * @var string
     */
    protected $primaryKey = 'waybill_id';
    /**
     * @var string[]
     */
    protected $fillable = [
        'car_id',
        'driver_id',
        'terminal_id',
        'transaction_id',
        'system_worker_id',
        'start_time',
        'end_time',
        'number',
        'waybill',
        'verified',
        'signed',
        'price'
    ];
    /**
     * @var string[]
     */
    protected $casts = ['price' => 'float', 'verified' => 'bool', 'signed' => 'bool'];
    /**
     * @var string[]
     */
    protected $dates = ['start_time', 'end_time'];
    /**
     * @var string
     */
    protected string $map = 'waybill';


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($waybill) {
            $last_number = (new static())::withTrashed()->latest('waybill_id')->first(['waybill_id', 'number'])->number ?? '000000000000';
            ++$last_number;

            if (Str::startsWith($last_number, '0')) {
                preg_match('/[0]+/', $last_number, $matches);
                $new_number = $matches[0].($last_number);
                $new_number = \strlen($new_number) > 10 ? substr($new_number, \strlen($new_number) - 10) : $new_number;
            } else {
                $new_number = $last_number;
            }

            $waybill->number = $new_number;
        });
    }

    /**
     * @return BelongsTo
     */
    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }

    /**
     * @return BelongsToThrough
     */
    public function driver_info(): BelongsToThrough
    {
        return $this->belongsToThrough(DriverInfo::class, Driver::class, 'driver_id', '',
            [DriverInfo::class => 'driver_info_id', Driver::class => 'driver_id']);
    }

    /**
     * @return BelongsTo
     */
    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class, 'car_id');
    }

    /**
     * @return HasMany
     */
    public function car_reports(): HasMany
    {
        return $this->hasMany(CarReport::class, $this->primaryKey);
    }

    /**
     * @return HasManyThrough
     */
    public function car_report_images(): HasManyThrough
    {
        return $this->hasManyThrough(CarReportImage::class, CarReport::class, 'car_report_id', 'report_id', 'waybill_id', 'car_report_id');
    }

    /**
     * @return BelongsToThrough
     */
    public function park(): BelongsToThrough
    {
        return $this->belongsToThrough(Park::class, Car::class, 'car_id', '', [Park::class => 'park_id', Car::class => 'car_id']);
    }

    /**
     * @return BelongsTo
     */
    public function worker(): BelongsTo
    {
        return $this->belongsTo(SystemWorker::class, 'system_worker_id', 'system_worker_id');
    }

    /**
     * @return MorphOne
     */
    public function transaction(): MorphOne
    {
        return $this->morphOne(FranchiseTransaction::class, 'reason', 'reason_type', 'reason_id');
    }
}
