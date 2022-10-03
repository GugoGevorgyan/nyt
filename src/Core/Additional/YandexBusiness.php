<?php

declare(strict_types=1);

namespace Src\Core\Additional;

/**
 * @notice
 */
class YandexBusiness
{
    /**
     * @var Guzzle
     */
    protected Guzzle $client;
    /**
     * @var array
     */
    protected array $authPayload = [];
    /**
     * @var string
     */
    private string $authUrl = 'https://business.taxi.yandex.ru/api/auth';

    public function __construct()
    {
        $this->client = app(Guzzle::class);
//        $payload = app(ExternalBoardContract::class)
//            ->whereJsonLength('oauth_payload->client_id', $attributes, $operator)
//            ->whereHas('key',fn(Builder $query) => $query->where('type','=', ConstApiKey::Y_BUSINESS()->getValue()))
//            ->findFirst();
    }

    public function draftOrder()
    {
    }

    public function draftChangeOrder()
    {
    }

    public function processingOrder()
    {
    }

    public function cancelOrder()
    {
    }

    public function statusOrder()
    {
    }

    public function infoOrder()
    {
    }
}
