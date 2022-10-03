<?php

namespace Src\Models\Car;


use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;

/**
 * Src\Models\Car\CarStatus
 *
 * @property int $car_status_id
 * @property string $value
 * @property string $text
 * @property string $color
 * @property string $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|CarStatus newModelQuery()
 * @method static Builder|CarStatus newQuery()
 * @method static Builder|CarStatus query()
 * @method static Builder|CarStatus whereCarStatusId($value)
 * @method static Builder|CarStatus whereColor($value)
 * @method static Builder|CarStatus whereCreatedAt($value)
 * @method static Builder|CarStatus whereDescription($value)
 * @method static Builder|CarStatus whereText($value)
 * @method static Builder|CarStatus whereUpdatedAt($value)
 * @method static Builder|CarStatus whereValue($value)
 * @mixin Eloquent
 */
class CarStatus extends ServiceModel
{

}
