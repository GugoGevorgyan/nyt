<?php

declare(strict_types=1);


namespace Src\Models\Oauth;


use Laravel\Passport\RefreshToken;

/**
 * Class Refresh
 *
 * @package Src\Models\Oauth
 * @property string $id
 * @property string $access_token_id
 * @property bool $revoked
 * @property \Illuminate\Support\Carbon|null $expires_at
 * @property-read \Src\Models\Oauth\Token $accessToken
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Oauth\Refresh newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Oauth\Refresh newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Oauth\Refresh query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Oauth\Refresh whereAccessTokenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Oauth\Refresh whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Oauth\Refresh whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Src\Models\Oauth\Refresh whereRevoked($value)
 * @mixin \Eloquent
 */
class Refresh extends RefreshToken
{
    protected $dateFormat = 'Y-m-d H:i:s.u';

}
