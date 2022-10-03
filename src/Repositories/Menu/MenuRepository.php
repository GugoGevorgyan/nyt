<?php

declare(strict_types=1);


namespace Src\Repositories\Menu;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Views\Menu;

/**
 * Class MenuRepository
 * @package Src\Repositories\Menu
 */
class MenuRepository extends BaseRepository implements MenuContract
{
    /**
     * MenuRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(Menu::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('menus');
    }
}
