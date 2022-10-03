<?php

declare(strict_types=1);

namespace Src\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;

use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;

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
class Task extends ServiceModel
{
    /**
     * @var string
     */
    protected $table = 'tasks';
    /**
     * @var string
     */
    protected $primaryKey = 'task_id';
    /**
     * @var string[]
     */
    protected $fillable = ['command', 'every', 'params', 'status'];
    /**
     * @var string[]
     */
    protected $casts = ['params' => 'array', 'status' => 'boolean'];
}
