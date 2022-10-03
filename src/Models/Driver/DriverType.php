<?php

declare(strict_types=1);

namespace Src\Models\Driver;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;

use function defined;

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
class DriverType extends ServiceModel
{
    /**
     * @var string
     */
    protected $table = 'driver_types';
    /**
     * @var string
     */
    protected $primaryKey = 'driver_type_id';
    /**
     * @var string[]
     */
    protected $fillable = ['type', 'name', 'description', 'worked'];
    /**
     * @var string[]
     */
    protected $casts = ['worked' => 'bool'];

    /**
     * @return HasMany
     */
    public function subtypes(): HasMany
    {
        return $this->hasMany(DriverSubtype::class, 'driver_type_id', 'driver_type_id');
    }

    /**
     * @return HasMany
     */
    public function drivers(): HasMany
    {
        return $this->hasMany(DriverContract::class, 'driver_type_id', 'driver_type_id');
    }

    /**
     * @return BelongsToMany
     */
    public function franchise_options(): BelongsToMany
    {
        return $this
            ->belongsToMany(DriverTypeOptional::class, DriverTypeOption::class, 'driver_type_id', 'driver_type_optional_id')
            ->withPivot('percent_value')
            ->when(defined('FRANCHISE_ID'), fn($query) => $query->where('franchise_id', '=', FRANCHISE_ID));
    }
}
