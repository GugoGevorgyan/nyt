<?php

declare(strict_types=1);

namespace Repository\Tests\Stubs;

use Rinvex\Repository\Repositories\EloquentRepository;

class EloquentPostRepository extends EloquentRepository
{
    protected $model = EloquentPost::class;

    protected $repositoryId = 'repository.post';
}
