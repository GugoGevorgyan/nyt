<?php

declare(strict_types=1);


namespace Src\Repositories\Module;


use Illuminate\Contracts\Container\Container;
use Repository\Exceptions\RepositoryException;
use Repository\Repositories\BaseRepository;
use Src\Models\Franchise\Module;

/**
 * Class ModuleRepository
 * @package Src\Repositories\Module
 */
class ModuleRepository extends BaseRepository implements ModuleContract
{
    /**
     * CarRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(Module::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('modules');
    }

    /**
     * @param $module_id
     * @return mixed
     * @throws RepositoryException
     */
    public function getModuleRoles($module_id)
    {
        return $this->model()->where('module_id', '=', $module_id)->with(
            [
                'roles' => static function ($role_query) {
                    $role_query->select(['module_id', 'role_id', 'name']);
                }
            ]
        )->get();
    }
}
