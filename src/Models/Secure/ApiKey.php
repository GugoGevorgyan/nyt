<?php

declare(strict_types=1);

namespace Src\Models\Secure;


use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;

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
class ApiKey extends ServiceModel
{
    /**
     * @var string
     */
    protected $table = 'api_keys';
    /**
     * @var string
     */
    protected $primaryKey = 'api_key_id';
    /**
     * @var string[]
     */
    protected $fillable = ['name', 'type', 'url', 'params', 'iterator'];
    /**
     * @var string[]
     */
    protected $casts = ['params' => 'array'];
}
