<?php
declare(strict_types=1);

namespace Src\Models\Oauth;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Laravel\Passport\PersonalAccessClient as BasePersonalAccessClient;

/**
 * Class PersonalAccessClient
 *
 * @package Src\Models\Oauth
 * @property int $id
 * @property int $client_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Client $client
 * @method static Builder|PersonalAccessClient newModelQuery()
 * @method static Builder|PersonalAccessClient newQuery()
 * @method static Builder|PersonalAccessClient query()
 * @method static Builder|PersonalAccessClient whereClientId($value)
 * @method static Builder|PersonalAccessClient whereCreatedAt($value)
 * @method static Builder|PersonalAccessClient whereId($value)
 * @method static Builder|PersonalAccessClient whereUpdatedAt($value)
 * @mixin Eloquent
 */
class PersonalAccessClient extends BasePersonalAccessClient
{
    protected $dateFormat = 'Y-m-d H:i:s.u';

}
