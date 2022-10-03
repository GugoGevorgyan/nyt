<?php

declare(strict_types=1);


namespace Src\Repositories\ClientSetting;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Client\ClientSetting;

/**
 * Class ClientSettingRepository
 * @package Src\Repositories\ClientSetting
 */
class ClientSettingRepository extends BaseRepository implements ClientSettingContract
{
    /**
     * CarRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(ClientSetting::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('client_settings');
    }
}
