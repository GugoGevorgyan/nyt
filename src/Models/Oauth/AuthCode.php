<?php
declare(strict_types=1);

namespace Src\Models\Oauth;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Laravel\Passport\AuthCode as BaseAuthCode;

/**
 * Class AuthCode
 *
 * @package Src\Models\Oauth
 * @property string $id
 * @property int $user_id
 * @property int $client_id
 * @property string|null $scopes
 * @property bool $revoked
 * @property Carbon|null $expires_at
 * @property-read Client $client
 * @method static Builder|AuthCode newModelQuery()
 * @method static Builder|AuthCode newQuery()
 * @method static Builder|AuthCode query()
 * @method static Builder|AuthCode whereClientId($value)
 * @method static Builder|AuthCode whereExpiresAt($value)
 * @method static Builder|AuthCode whereId($value)
 * @method static Builder|AuthCode whereRevoked($value)
 * @method static Builder|AuthCode whereScopes($value)
 * @method static Builder|AuthCode whereUserId($value)
 * @mixin Eloquent
 */
class AuthCode extends BaseAuthCode
{
    protected $dateFormat = 'Y-m-d H:i:s.u';

}
