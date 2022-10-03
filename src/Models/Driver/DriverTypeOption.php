<?php

declare(strict_types=1);

namespace Src\Models\Driver;


use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;

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
class DriverTypeOption extends ServiceModel
{


    public $timestamps = false;
    /**
     * @var string
     */
    protected $table = 'driver_type_option';
    /**
     * @var string
     */
    protected $primaryKey = 'driver_type_option_id';
    /**
     * @var string[]
     */
    protected $fillable = ['franchise_id', 'driver_type_id', 'driver_type_optional_id', 'percent_value'];
    /**
     * @var string[]
     */
    protected $dates = ['created_at'];
    /**
     * @var string[]
     */
    protected $casts = ['percent_value' => 'int'];

    /**
     * @return BelongsTo
     */
    public function option(): BelongsTo
    {
        return $this->belongsTo(DriverTypeOptional::class, 'driver_type_optional_id', 'driver_type_optional_id');
    }
}
