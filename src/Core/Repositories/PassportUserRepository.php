<?php

declare(strict_types=1);


namespace Src\Core\Repositories;

use Laravel\Passport\Bridge\User;
use Laravel\Passport\Bridge\UserRepository;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\UserEntityInterface;
use RuntimeException;
use Src\Exceptions\Passport\NotGuardException;

/**
 * Class PassportUserRepository
 * @package Src\Additional
 */
class PassportUserRepository extends UserRepository
{

    /**
     * @param  string  $username
     * @param  string  $password
     * @param  string  $grantType
     * @param  ClientEntityInterface  $clientEntity
     * @return User|UserEntityInterface|void
     * @throws NotGuardException
     */
    public function getUserEntityByUserCredentials(
        $username,
        $password,
        $grantType,
        ClientEntityInterface $clientEntity
    ) {
        $guard = $_REQUEST['guard'] ?? $clientEntity->provider.'_api';

        if (!$guard) {
            throw new NotGuardException('Guard field is required');
        }

        $provider = config("auth.guards.{$guard}.provider");

        if (null === $model = config("auth.providers.{$provider}.model")) {
            throw new RuntimeException('Unable to determine user model from configuration.');
        }

        if (method_exists($model, 'findForPassport')) {
            $user = (new $model())->findForPassport($username);
        } else {
            $user = (new $model())->where('email', $username)->first();
        }

        if (!$user) {
            return;
        }

        if (method_exists($user, 'validateForPassportPasswordGrant')) {
            if (!$user->validateForPassportPasswordGrant($password)) {
                return;
            }
        } elseif (!$this->hasher->check($password, $user->password)) {
            return;
        }

        return new User($user->getAuthIdentifier());
    }
}
