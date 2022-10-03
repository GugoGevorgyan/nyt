<?php

declare(strict_types=1);

namespace ServiceEntity\Models;

use Illuminate\Foundation\Auth\User as Authenticable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Redis\Connections\Connection;
use Illuminate\Support\Facades\Redis;
use Laravel\Passport\HasApiTokens;
use ServiceEntity\Custom\HasCustomRelations;
use Src\Core\Traits\HasFranchise;
use Src\Core\Traits\HasRoles;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;
use Znck\Eloquent\Traits\BelongsToThrough;

/**
 * Class ServiceAuthenticable
 * @package ServiceEntity\Models
 */
class ServiceAuthenticable extends Authenticable
{
    use BelongsToThrough;
    use HasApiTokens;
    use HasFranchise;
    use HasJsonRelationships;
    use HasRelationships;
    use HasRoles;
    use Notifiable;
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
     * @var string[]
     */
    protected $spatialFields = [];
    /**
     * @var string[]
     */
    public $socketAuth = [];

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

    /**
     * Specifies the user's FCM tokens
     *
     * @return string|null
     */
    public function routeNotificationForFcm(): ?string
    {
        return $this->fcm()->first()->key ?? null;
    }
}
