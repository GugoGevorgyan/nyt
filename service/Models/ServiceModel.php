<?php

declare(strict_types=1);

namespace ServiceEntity\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Redis\Connections\Connection;
use Illuminate\Support\Facades\Redis;
use ServiceEntity\Custom\HasCustomRelations;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;
use Znck\Eloquent\Traits\BelongsToThrough;

/**
 * Class ServiceModel
 * @package ServiceEntityStory\Models
 * @method static first($attributes = ['*'])
 */
class ServiceModel extends Model
{
    use HasFactory;
    use BelongsToThrough;
    use HasJsonRelationships;
    use HasRelationships;
    use ScopeHelpers;
    use HasCustomRelations;

    /**
     * @var string
     */
    protected $dateFormat = 'Y-m-d H:i:s.u';
    /**
     * @var string
     */
    protected string $map = '';

    /**
     * @return string
     */
    public function getMap(): string
    {
        return (new static())->map;
    }

    /**
     * @param  string  $connection
     * @return Connection
     */
    public function redis(string $connection = 'app'): Connection
    {
        return Redis::connection($connection);
    }
}
