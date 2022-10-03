<?php

declare(strict_types=1);

namespace Src\Models\Oauth;

use Detection\MobileDetect;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Laravel\Passport\Client as BaseClient;
use Laravel\Passport\Passport;

use Src\Core\Additional\Devicer;

use function is_null;

/**
 * Class ClientMessage
 *
 * @package Src\Models\Oauth
 * @property int $id
 * @property int|null $user_id
 * @property string $name
 * @property string $secret
 * @property string|null $provider
 * @property string|null $device
 * @property string $redirect
 * @property bool $personal_access_client
 * @property bool $password_client
 * @property bool $revoked
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|AuthCode[] $authCodes
 * @property-read int|null $auth_codes_count
 * @property-read string|null $plain_secret
 * @property-read Collection|Token[] $tokens
 * @property-read int|null $tokens_count
 * @method static Builder|Client newModelQuery()
 * @method static Builder|Client newQuery()
 * @method static Builder|Client query()
 * @method static Builder|Client whereCreatedAt($value)
 * @method static Builder|Client whereDevice($value)
 * @method static Builder|Client whereId($value)
 * @method static Builder|Client whereName($value)
 * @method static Builder|Client wherePasswordClient($value)
 * @method static Builder|Client wherePersonalAccessClient($value)
 * @method static Builder|Client whereProvider($value)
 * @method static Builder|Client whereRedirect($value)
 * @method static Builder|Client whereRevoked($value)
 * @method static Builder|Client whereSecret($value)
 * @method static Builder|Client whereUpdatedAt($value)
 * @method static Builder|Client whereUserId($value)
 * @mixin Eloquent
 */
class Client extends BaseClient
{
//    protected $fillable = ['device'];
    protected $dateFormat = 'Y-m-d H:i:s.u';

    // @todo
    /**
     * Set the value of the secret attribute.
     *
     * @param  string|null  $value
     * @return void
     */
//    public function setSecretAttribute($value)
//    {
//        $this->plainSecret = $value;
//
//        if (is_null($value) || !Passport::$hashesClientSecrets) {
//            $plaintext = $value;
//            $crypto_strong = bcrypt('jho5l4235435hg34li5kbh4lihg5li23h4b213i4g32oh4ib');
//            $key = openssl_random_pseudo_bytes(256, $crypto_strong);
//            $cipher = "aes-128-gcm";
//            $ivlen = openssl_cipher_iv_length($cipher);
//            $iv = openssl_random_pseudo_bytes($ivlen);
//            $ciphertext = openssl_encrypt($plaintext, $cipher, $key, $options = 0, $iv, $tag);
//            $original_plaintext = openssl_decrypt($ciphertext, $cipher, $key, $options=0, $iv, $tag);
//            $this->attributes['secret'] = $original_plaintext;
//
////            dd($this->attributes['secret']);
//            return;
//        }
//
//        $this->attributes['secret'] = password_hash($value, PASSWORD_BCRYPT);
//    }

    public function setDeviceAttribute(): void
    {
        $this->attributes['device'] = (new Devicer())->device();
    }

    /**
     * Set the value of the secret attribute.
     *
     * @param  string|null  $value
     * @return void
     */
    public function setSecretAttribute($value)
    {
        $value = Str::random(64);

        $this->plainSecret = $value;

        if (is_null($value) || !Passport::$hashesClientSecrets) {
            $this->attributes['secret'] = $value;

            return;
        }

        $this->attributes['secret'] = password_hash($value, PASSWORD_BCRYPT);
    }
}
