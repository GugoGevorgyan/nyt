<?php

declare(strict_types=1);

namespace Repository\Repositories;

use Closure;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Container\Container;
use Illuminate\Database\Eloquent\Model;
use JsonException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Repository\Contracts\BaseCacheContract;
use Repository\Contracts\BaseRepositoryContract;
use Repository\Exceptions\RepositoryException;
use Repository\Traits\Cache;
use Repository\Traits\Criteria;
use Repository\Traits\Magick;

use function call_user_func;
use function is_string;

/**
 * Class Repository
 * @package Repository\Repositories
 */
abstract class Repository implements BaseRepositoryContract, BaseCacheContract
{
    use Cache;
    use Criteria;
    use Magick;

    /**
     * The IoC container instance.
     * @var Container
     */
    protected Container $container;
    /**
     * The connection name for the repository.
     *
     * @var string
     */
    protected string $connection;
    /**
     * The repository identifier.
     *
     * @var string
     */
    protected string $repositoryId;
    /**
     * The repository model.
     *
     * @var string
     */
    protected string $model;
    /**
     * The repository model search data structure.
     *
     * @var array
     */
    protected array $fieldSearchable;
    /**
     * The repository model search data structure.
     *
     * @var bool
     */
    protected bool $payloadCollect = false;

    /**
     * {@inheritdoc}
     */
    public function getConnection(): string
    {
        return $this->connection;
    }

    /**
     * {@inheritdoc}
     */
    public function setConnection($name): Repository|BaseRepositoryContract|static
    {
        $this->connection = $name;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getRepositoryId(): string
    {
        return $this->repositoryId ?: static::class;
    }

    /**
     * {@inheritdoc}
     */
    public function setRepositoryId($repositoryId): Repository|BaseRepositoryContract|static
    {
        $this->repositoryId = $repositoryId;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setPayloadCollect(): Repository|BaseRepositoryContract|static
    {
        $this->payloadCollect = true;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function store(int $id = null, array $attrs = [], bool $syncRelations = false)
    {
        return !$id ? $this->create($attrs, $syncRelations) : $this->update($id, $attrs, $syncRelations);
    }

    /**
     * {@inheritdoc}
     * @throws RepositoryException|BindingResolutionException
     */
    public function createModel()
    {
        if (is_string($model = $this->getModel())) {
            if (!class_exists($class = '\\'.ltrim($model, '\\'))) {
                throw new RepositoryException("Class {$model} does NOT exist!");
            }

            $model = $this->getContainer()->make($class);
        }

        // Set the connection used by the model
        if (!empty($this->connection)) {
            $model = $model->setConnection($this->connection);
        }

        if (!$model instanceof Model) {
            throw new RepositoryException("Class {$model} must be an instance of \\Illuminate\\Database\\Eloquent\\Model");
        }

        return $model;
    }

    /**
     * {@inheritdoc}
     */
    public function getModel(): string
    {
        $entity = $this->getContainer('config')->get('repository.models');

        return $this->model ?: str_replace(['Repositories', 'Repository'], [$entity, ''], static::class);
    }

    /**
     * {@inheritdoc}
     */
    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getContainer($service = null)
    {
        /** @noinspection OffsetOperationsInspection */
        return null === $service ? ($this->container ?: app()) : ($this->container[$service] ?: app($service));
    }

    /**
     * {@inheritdoc}
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    /**
     * {@inheritDoc}
     * @throws RepositoryException
     */
    public function getFillable(): ?array
    {
        $entity = $this->model();

        return $entity->getFillable() ?: null;
    }

    /**
     * {@inheritDoc}
     * @return Model
     * @throws RepositoryException
     */
    public function model($attributes = []): Model
    {
        $entity = $this->getModel();
        $entity = new $entity($attributes);

        if (!$entity instanceof Model) {
            throw new RepositoryException("Class {$entity} must be an instance of \\Illuminate\\Database\\Eloquent\\Model");
        }

        return $entity;
    }

    /**
     * @inheritDoc
     * @throws RepositoryException
     */
    public function getKeyName(): ?string
    {
        return $this->model()->getKeyName();
    }

    /**
     * @inheritDoc
     * @throws RepositoryException
     */
    public function getTable(): ?string
    {
        return $this->model()->getTable();
    }

    /**
     * @return string
     * @throws RepositoryException
     */
    public function getMap(): string
    {
        return $this->model()->getMap();
    }

    /**
     * Execute given callback and return the result.
     *
     * @param  string  $class
     * @param  string  $method
     * @param  array  $args
     * @param  Closure  $closure
     *
     * @return mixed
     * @throws JsonException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function executeCallback($class, $method, $args, Closure $closure): mixed
    {
        $skip_uri = $this->getContainer('config')->get('repository.cache.skip_uri');

        // Check if cache is enabled
        if ($this->getCacheLifetime() && !$this->getContainer('request')->has($skip_uri)) {
            return $this->cacheCallback($class, $method, $args, $closure);
        }

        // Cache disabled, just execute query & return result
        /** @noinspection VariableFunctionsUsageInspection */
        $result = call_user_func($closure);

        // We're done, let's clean up!
        $this->resetRepository();

        return $this->payloadCollect ? recToRec($result) : $result;
    }

    /**
     * @return $this
     */
    protected function resetRepository(): self
    {
        $this->whereJson = [];
        $this->whereExists = [];
        $this->whereJsonNotIn = [];
        $this->orWhereJson = [];
        $this->whereJsonCount = [];
        $this->when = [];
        $this->has = [];
        $this->orHas = [];
        $this->orWhereHas = [];
        $this->doesntHave = [];
        $this->orDoesntHave = [];
        $this->whereDoesntHave = [];
        $this->orWhereDoesntHave = [];
        $this->hasMorph = [];
        $this->whereHasMorph = [];
        $this->whereBetween = [];
        $this->orWhereBetween = [];
        $this->whereNotBetween = [];
        $this->whereDate = [];
        $this->whereMonth = [];
        $this->whereDay = [];
        $this->whereTime = [];
        $this->withCount = [];
        $this->withSum = [];
        $this->whereRaw = [];
        $this->orWhereRaw = [];
        $this->except = [];
        $this->relations = [];
        $this->where = [];
        $this->orWhere = [];
        $this->whereIn = [];
        $this->whereNotIn = [];
        $this->whereHas = [];
        $this->scopes = [];
        $this->orderBy = [];
        $this->groupBy = [];
        $this->having = [];
        $this->havingRaw = [];
        $this->join = [];
        $this->offset = null;
        $this->limit = null;
        $this->withTrashed = false;
        $this->withoutScope = false;

        if (method_exists($this, 'flushCriteria')) {
            $this->flushCriteria();
        }

        return $this;
    }
}
