<?php

declare(strict_types=1);

namespace Src\Repositories\FranchiseeOption;

use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Entities\FranchiseeOption\FranchiseeOption;
use Src\Models\Franchise\FranchiseOption;

/**
 * Class FranchiseeOptionRepositoryEloquent.
 *
 * @package namespace Src\Repositories\FranchiseeOption;
 */
class FranchiseOptionRepository extends BaseRepository implements FranchiseOptionContract
{
    /**
     * FranchiseModuleRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(FranchiseOption::class)
            ->setCacheDriver('redis')
            ->setCacheLifetime(0)
            ->setRepositoryId('franchise_options');
    }
}
