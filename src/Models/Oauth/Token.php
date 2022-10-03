<?php

declare(strict_types=1);

namespace Src\Models\Oauth;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Laravel\Passport\Token as BaseToken;

/**
 * Class Token
 *
 * @package Src\Models\Oauth
 * @property string $id
 * @property int|null $user_id
 * @property int $client_id
 * @property string|null $name
 * @property array|null $scopes
 * @property bool $revoked
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property \Illuminate\Support\Carbon|null $expires_at
 * @property-read \Src\Models\Oauth\Client $client
 * @property-read \Src\Models\Oauth\Refresh|null $refresh_token
 * @method static \Illuminate\Database\Eloquent\Builder|Token newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Token newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Token query()
 * @method static \Illuminate\Database\Eloquent\Builder|Token whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Token whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Token whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Token whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Token whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Token whereRevoked($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Token whereScopes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Token whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Token whereUserId($value)
 * @mixin \Eloquent
 */
class Token extends BaseToken
{
    protected $dateFormat = 'Y-m-d H:i:s.u';

    /**
     * @return HasOne
     */
    public function refresh_token(): HasOne
    {
        return $this->hasOne(Refresh::class, 'access_token_id');
    }
}
