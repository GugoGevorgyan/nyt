<?php

declare(strict_types=1);

namespace Repository\Tests\Stubs;

use Rinvex\Repository\Repositories\EloquentRepository;
use Rinvex\Repository\Traits\Criteriable;

class EloquentUserRepository extends EloquentRepository
{
    use Criteriable;

    protected $model = EloquentUser::class;

    protected $repositoryId = 'repository.user';
}
