<?php

declare(strict_types=1);

namespace Src\Models\Secure;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;

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
class Versioning extends ServiceModel
{
    /**
     * @var bool
     */
    public $timestamps = false;
    /**
     * @var string
     */
    protected $table = 'versioning';
    /**
     * @var string
     */
    protected $primaryKey = 'version_id';
    /**
     * @var string[]
     */
    protected $fillable = ['version', 'device', 'app', 'state', 'updated_at'];
    /**
     * @var string[]
     */
    protected $dates = ['updated_at'];
}
