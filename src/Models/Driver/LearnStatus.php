<?php

namespace Src\Models\Driver;


use ServiceEntity\Models\ServiceModel;

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
class LearnStatus extends ServiceModel
{

}
