<?php

declare(strict_types=1);

namespace Src\Models\Driver;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;

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
class DriverTypeOptional extends ServiceModel
{
    protected $table = 'driver_type_optionals';
    /**
     * @var string
     */
    protected $primaryKey = 'driver_type_optional_id';
    /**
     * @var string[]
     */
    protected $fillable = ['name', 'description', 'valued'];
    /**
     * @var string[]
     */
    protected $casts = ['valued' => 'boolean'];

    /**
     * Get the user's first name.
     *
     * @param  string  $value
     * @return string
     */
    public function getNameAttribute($value): string
    {
        return trans("words.driver_type_options.$value");
    }
}
