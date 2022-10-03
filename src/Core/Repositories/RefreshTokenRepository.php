<?php

declare(strict_types=1);

namespace Src\Core\Repositories;

use Illuminate\Contracts\Events\Dispatcher;
use JetBrains\PhpStorm\Pure;
use Laravel\Passport\Bridge\RefreshToken;
use Laravel\Passport\Events\RefreshTokenCreated;
use Laravel\Passport\RefreshTokenRepository as PassportRefreshTokenRepository;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;

/**
 * Class RefreshTokenRepository
 * @package Src\Core\Passport\Repositories
 */
class RefreshTokenRepository extends \Laravel\Passport\Bridge\RefreshTokenRepository
{
    /**
     * The refresh token repository instance.
     *
     * @var Connection
     */
    protected $refreshTokenRepository;
    /**
     * The event dispatcher instance.
     *
     * @var Dispatcher
     */
    protected $events;

    /**
     * Create a new repository instance.
     *
     * @param  PassportRefreshTokenRepository  $refreshTokenRepository
     * @param  Dispatcher  $events
     * @return void
     */
    public function __construct(PassportRefreshTokenRepository $refreshTokenRepository, Dispatcher $events)
    {
        $this->events = $events;
        $this->refreshTokenRepository = $refreshTokenRepository;

        parent::__construct($refreshTokenRepository, $events);
    }

    /**
     * {@inheritdoc}
     */
    public function getNewRefreshToken()
    {
        return new RefreshToken();
    }

    /**
     * {@inheritdoc}
     */
    public function persistNewRefreshToken(RefreshTokenEntityInterface $refreshTokenEntity)
    {
        $this->refreshTokenRepository->create(
            [
                'id' => $id = $refreshTokenEntity->getIdentifier(),
                'access_token_id' => $accessTokenId = $refreshTokenEntity->getAccessToken()->getIdentifier(),
                'revoked' => false,
                'expires_at' => $refreshTokenEntity->getExpiryDateTime(),
            ]
        );

        $this->events->dispatch(new RefreshTokenCreated($id, $accessTokenId));
    }

    /**
     * {@inheritdoc}
     */
    public function revokeRefreshToken($tokenId)
    {
        $this->refreshTokenRepository->revokeRefreshToken($tokenId);
    }

    /**
     * {@inheritdoc}
     */
    public function isRefreshTokenRevoked($tokenId)
    {
        return $this->refreshTokenRepository->isRefreshTokenRevoked($tokenId);
    }
}
