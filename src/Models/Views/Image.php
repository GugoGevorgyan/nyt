<?php

declare(strict_types=1);

namespace Src\Models\Views;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;
use ServiceEntity\Models\ServiceModel;

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
 */
class Image extends ServiceModel
{
    /**
     * @var string
     */
    protected $table = 'images';
    /**
     * @var string
     */
    protected $primaryKey = 'image_id';
    /**
     * @var array
     */
    protected $fillable = ['name', 'ext', 'path'];

    /**
     * @return MorphTo
     */
    public function franchise_logo(): MorphTo
    {
        return $this->morphTo('franchise_logo', 'imageable_type', 'imageable_id', 'franchise_id');
    }

    /**
     * @return MorphTo
     */
    public function candidate_img(): MorphTo
    {
        return $this->morphTo('candidate_img', 'imageable_type', 'imageable_id', 'driver_candidate_id');
    }

    /**
     * @return MorphTo
     */
    public function worker_img(): MorphTo
    {
        return $this->morphTo('worker_img', 'imageable_type', 'imageable_id', 'system_worker_id');
    }
}
