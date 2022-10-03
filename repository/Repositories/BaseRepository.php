<?php

declare(strict_types=1);

namespace Repository\Repositories;

use Closure;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use InvalidArgumentException;
use JsonException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Repository\Contracts\WhereClauseContract;
use Repository\Exceptions\EntityNotFoundException;
use Repository\Exceptions\RepositoryException;
use Repository\Traits\Clauses;
use Repository\Traits\Prepare;
use Repository\Traits\RelationsStore;

use function count;
use function func_get_args;
use function in_array;
use function is_array;

/**
 * Class BaseRepository
 * @method  distanceCord($latitude, $longitude, string $distance = 1)
 * @method  distance($latitude, $longitude)
 * @method  withoutGlobalScopes($scopes = null)
 */
class BaseRepository extends Repository
{
    use Clauses;
    use Prepare;
    use RelationsStore;

    /**
     * @inheritDoc
     * @param  null  $column
     * @param  array  $attributes
     * @return mixed
     * @throws ContainerExceptionInterface
     * @throws JsonException
     * @throws NotFoundExceptionInterface
     */
    public function firstLatest($column = null, array $attributes = ['*']): ?object
    {
        return $this->executeCallback(
            static::class,
            __FUNCTION__,
            func_get_args(),
            fn() => $this->prepareQuery($this->createModel())->latest($column)->first($attributes)
        );
    }

    /**
     * @inheritDoc
     * @param  null  $column
     * @return mixed
     * @throws ContainerExceptionInterface
     * @throws JsonException
     * @throws NotFoundExceptionInterface
     */
    public function firstOldest($column = null)
    {
        return $this->executeCallback(
            static::class,
            __FUNCTION__,
            func_get_args(),
            fn() => $this->prepareQuery($this->createModel())->oldest($column)->first());
    }

    /**
     * @inheritDoc
     * @param  null  $column
     * @return mixed
     * @throws ContainerExceptionInterface
     * @throws JsonException
     * @throws NotFoundExceptionInterface
     */
    public function latest($column = null)
    {
        return $this->executeCallback(
            static::class,
            __FUNCTION__,
            func_get_args(),
            fn() => $this->prepareQuery($this->createModel())->latest($column));
    }

    /**
     * @inheritDoc
     * @param  null  $column
     * @return mixed
     * @throws ContainerExceptionInterface
     * @throws JsonException
     * @throws NotFoundExceptionInterface
     */
    public function oldest($column = null)
    {
        return $this->executeCallback(
            static::class,
            __FUNCTION__,
            func_get_args(),
            fn() => $this->prepareQuery($this->createModel())->oldest($column));
    }

    /**
     * @inheritDoc
     */
    public function updateSet(array $attributes = [], bool $syncRelations = false): Collection|bool
    {
        $this->prepareQuery($this->model());

        $entities = $this->findAll();

        if ($entities->count() < 1) {
            return $entities;
        }

        $updated = [];

        foreach ($entities as $entity) {
            $this->getContainer('events')->dispatch($this->getRepositoryId().'.entity.updating', [$this, $entity]);

            // Extract relationships
            if ($syncRelations) {
                $relations = $this->extractRelations($entity, $attributes);
                Arr::forget($attributes, array_keys($relations));
            }

            // Fill instance with data
            $entity->fill($attributes);

            // Update the instance
            $updated[] = $entity->save();

            // Sync relationships
            if ($syncRelations && isset($relations)) {
                $this->syncRelations($entity, $relations);
            }

            if ($updated) {
                // Fire the updated event
                $this->getContainer('events')->dispatch($this->getRepositoryId().'.entity.updated', [$this, $entity]);
            }
        }

        return !in_array(false, $updated, true);
    }

    /**
     * {@inheritdoc}
     * @param  string[]  $attr
     * @return mixed
     * @throws ContainerExceptionInterface
     * @throws JsonException
     * @throws NotFoundExceptionInterface
     */
    public function findAll($attr = ['*']): Collection
    {
        return $this->executeCallback(
            static::class,
            __FUNCTION__,
            func_get_args(),
            fn() => $this->prepareQuery($this->createModel())->get($attr));
    }

    /**
     * {@inheritdoc}
     * @param  array  $where
     * @param  string[]  $attributes
     * @return mixed
     * @throws JsonException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function firstWhere(array $where, $attributes = ['*'])
    {
        return $this->executeCallback(
            static::class,
            __FUNCTION__,
            func_get_args(),
            function () use ($where, $attributes) {
                [$attribute, $operator, $value, $boolean] = array_pad($where, 4, null);

                $this->where($attribute, $operator, $value, $boolean);

                return $this->prepareQuery($this->createModel())->first($attributes);
            }
        );
    }

    /**
     * @return bool
     * @throws BindingResolutionException
     * @throws RepositoryException
     */
    public function deletes(): bool
    {
        $deleted = false;

        // Find the given instance
        $entity = $this->createModel();
        $entities = $this->prepareQuery($entity)->get($entity->getKeyName());

        if ($entities->count() > 0) {
            foreach ($entities as $entity) {
                // Fire the deleted event
                $this->getContainer('events')->dispatch($this->getRepositoryId().'.entity.deleting', [$this, $entity]);

                // Delete the instance
                $deleted = $entity->delete();

                // Fire the deleted event
                $this->getContainer('events')->dispatch($this->getRepositoryId().'.entity.deleted', [$this, $entity]);
            }
        }

        return $deleted;
    }

    /**
     * @inheritdoc
     */
    public function fullSearch($against, ...$matches): ?WhereClauseContract
    {
        return $this->whereRaw("MATCH ($matches) AGAINST (\\'$against\\' IN BOOLEAN MODE)");
    }

    /**
     * {@inheritdoc}
     * @param  string  $columns
     * @return int
     * @throws ContainerExceptionInterface
     * @throws JsonException
     * @throws NotFoundExceptionInterface
     */
    public function count($columns = '*'): int
    {
        return (int)$this->executeCallback(
            static::class,
            __FUNCTION__,
            func_get_args(),
            fn() => $this->prepareQuery($this->createModel())->count($columns));
    }

    /**
     * @inheritDoc
     * @throws JsonException
     */
    public function whereExistsExist($attribute, $operator = null, $value = null, $existsColumn = '', string $boolean = 'and'): bool
    {
        return $this->where($attribute, $operator, $value, $boolean)->exists($existsColumn);
    }

    /////////////////////////         RESET WHERE CLAUSES          /////////////////////////

    /**
     * @param  string  $column
     * @return bool
     * @throws ContainerExceptionInterface
     * @throws JsonException
     * @throws NotFoundExceptionInterface
     */
    public function exists(string $column = '*'): bool
    {
        return (bool)$this->executeCallback(
            static::class,
            __FUNCTION__,
            func_get_args(),
            fn() => $this->prepareQuery($this->createModel())->exists($column));
    }

    /**
     * {@inheritdoc}
     * @throws JsonException
     */
    public function findOrFail($id, $attributes = ['*'])
    {
        $result = $this->find($id, $attributes);

        if (is_array($id) && count($result) === count(array_unique($id))) {
            return $result;
        }

        if (null !== $result) {
            return $result;
        }

        throw new EntityNotFoundException($this->getModel(), $id);
    }

    /**
     * {@inheritdoc}
     * @param $id
     * @param  string[]  $attrs
     * @return object|null
     * @throws ContainerExceptionInterface
     * @throws JsonException
     * @throws NotFoundExceptionInterface
     */
    public function find($id, $attrs = ['*']): ?object
    {
        return $this->executeCallback(
            static::class,
            __FUNCTION__,
            func_get_args(),
            fn() => $this->prepareQuery($this->createModel())->find($id, $attrs)
        );
    }

    /**
     * @inheritDoc
     * @param $id
     * @param  string[]  $attributes
     * @return Model|mixed
     * @throws BindingResolutionException
     * @throws ContainerExceptionInterface
     * @throws JsonException
     * @throws NotFoundExceptionInterface
     * @throws RepositoryException
     */
    public function findOrNew($id, $attributes = ['*'])
    {
        if (null !== ($entity = $this->find($id, $attributes))) {
            return $entity;
        }

        return $this->createModel();
    }

    /**
     * {@inheritdoc}
     * @param $attribute
     * @param $value
     * @param  string[]  $attributes
     * @return mixed
     * @throws ContainerExceptionInterface
     * @throws JsonException
     * @throws NotFoundExceptionInterface
     */
    public function findBy($attribute, $value, $attributes = ['*'])
    {
        return $this->executeCallback(
            static::class,
            __FUNCTION__,
            func_get_args(),
            fn() => $this->prepareQuery($this->createModel())->where($attribute, '=', $value)->first($attributes));
    }

    /**
     * {@inheritdoc}
     * @param  string[]  $attr
     * @return object|null
     * @throws ContainerExceptionInterface
     * @throws JsonException
     * @throws NotFoundExceptionInterface
     */
    public function findFirst($attr = ['*']): object|null
    {
        return $this->executeCallback(
            static::class,
            __FUNCTION__,
            func_get_args(),
            fn() => $this->prepareQuery($this->createModel())->first($attr)
        );
    }

    /**
     * {@inheritdoc}
     * @param  null  $perPage
     * @param  string[]  $attributes
     * @param  string  $pageName
     * @param  null  $page
     * @return mixed
     * @throws ContainerExceptionInterface
     * @throws JsonException
     * @throws NotFoundExceptionInterface
     */
    public function paginate($perPage = null, $attributes = ['*'], $pageName = 'page', $page = null)
    {
        $page = $page ?: Paginator::resolveCurrentPage($pageName);

        return $this->executeCallback(
            static::class,
            __FUNCTION__,
            array_merge(func_get_args(), compact('page')),
            fn() => $this->prepareQuery($this->createModel())->paginate($perPage, $attributes, $pageName, $page)
        );
    }

    /**
     * {@inheritdoc}
     * @throws JsonException
     */
    public function simplePaginate($perPage = null, $attributes = ['*'], $pageName = 'page', $page = null)
    {
        $page = $page ?: Paginator::resolveCurrentPage($pageName);

        return $this->executeCallback(
            static::class,
            __FUNCTION__,
            array_merge(func_get_args(), compact('page')),
            fn() => $this->prepareQuery($this->createModel())->simplePaginate($perPage, $attributes, $pageName, $page)
        );
    }

    /**
     * {@inheritdoc}
     * @param  null  $perPage
     * @param  string[]  $columns
     * @param  string  $cursorName
     * @param  null  $cursor
     * @return mixed
     * @throws ContainerExceptionInterface
     * @throws JsonException
     * @throws NotFoundExceptionInterface
     */
    public function cursorPaginate($perPage = null, $columns = ['*'], $cursorName = 'cursor', $cursor = null)
    {
        $cursor = $cursor ?: Paginator::resolveCurrentPage($cursorName);

        return $this->executeCallback(
            static::class,
            __FUNCTION__,
            array_merge(func_get_args(), compact('cursor')),
            fn() => $this->prepareQuery($this->createModel())->cursorPaginate($perPage, $columns, $cursorName, $cursor)
        );
    }

    /**
     * {@inheritdoc}
     * @throws JsonException
     */
    public function findWhere(array $where, $attrs = ['*'])
    {
        return $this->executeCallback(
            static::class,
            __FUNCTION__,
            func_get_args(),
            function () use ($where, $attrs) {
                [$attribute, $operator, $value, $boolean] = array_pad($where, 4, null);

                $this->where($attribute, $operator, $value, $boolean);

                return $this->prepareQuery($this->createModel())->get($attrs);
            }
        );
    }

    /**
     * {@inheritdoc}
     * @throws JsonException
     */
    public function findWhereIn(array $where, $attributes = ['*'])
    {
        return $this->executeCallback(
            static::class,
            __FUNCTION__,
            func_get_args(),
            function () use ($where, $attributes) {
                [$attribute, $values, $boolean, $not] = array_pad($where, 4, null);

                $this->whereIn($attribute, $values, $boolean, $not);

                return $this->prepareQuery($this->createModel())->get($attributes);
            }
        );
    }

    /**
     * {@inheritdoc}
     * @throws JsonException
     */
    public function findWhereNotIn(array $where, $attributes = ['*'])
    {
        return $this->executeCallback(
            static::class,
            __FUNCTION__,
            func_get_args(),
            function () use ($where, $attributes) {
                [$attribute, $values, $boolean] = array_pad($where, 3, null);

                $this->whereNotIn($attribute, $values, $boolean);

                return $this->prepareQuery($this->createModel())->get($attributes);
            }
        );
    }

    /**
     * {@inheritdoc}
     * @throws JsonException
     */
    public function findWhereHas(array $where, $attributes = ['*'])
    {
        return $this->executeCallback(
            static::class,
            __FUNCTION__,
            func_get_args(),
            function () use ($where, $attributes) {
                [$relation, $callback, $operator, $count] = array_pad($where, 4, null);

                $this->whereHas($relation, $callback, $operator, $count);

                return $this->prepareQuery($this->createModel())->get($attributes);
            }
        );
    }

    /**
     * @inheritDoc
     * @param  array  $where
     * @param  array  $attrs
     * @param  bool  $syncRelations
     * @param  bool  $merge
     * @return mixed|object|null
     * @throws BindingResolutionException
     * @throws ContainerExceptionInterface
     * @throws JsonException
     * @throws NotFoundExceptionInterface
     * @throws RepositoryException
     */
    public function updateOrCreate(array $where, array $attrs, bool $syncRelations = false, bool $merge = false)
    {
        $queries = array_chunk($where, 3);

        if (count($queries) > 1) {
            foreach ($queries as $query) {
                $this->where($query[0], $query[1], $query[2]);
            }
        } else {
            $this->where($queries[0][0], $queries[0][1], $queries[0][2]);
        }

        $entities = $this->findAll();

        if ($entities->count()) {
            foreach ($entities as $entity) {
                $this->update($entity->{$entity->getKeyName()}, $attrs, $syncRelations);
            }
        } else {
            $query_attribute[$queries[0][0]] = $queries[0][2];
            $attribute = array_merge($query_attribute, $attrs);
            $entities = $this->create($attribute, $syncRelations);
        }

        return $entities;
    }

    /**
     * {@inheritdoc}
     */
    public function update($id, array $attrs = [], bool $syncRelations = false): bool|object
    {
        $updated = false;

        // Find the given instance
        $entity = $id instanceof Model ? $id : $this->find($id);

        if ($entity) {
            // Fire the updated event
            $this->getContainer('events')->dispatch($this->getRepositoryId().'.entity.updating', [$this, $entity]);

            // Extract relationships
            if ($syncRelations) {
                $relations = $this->extractRelations($entity, $attrs);
                Arr::forget($attrs, array_keys($relations));
            }

            // Fill instance with data
            $entity->fill($attrs);

            //Check if we are updating attributes values
            $dirty = $syncRelations ? [1] : $entity->getDirty();

            // Update the instance
            $updated = $entity->save();

            // Sync relationships
            if ($syncRelations && isset($relations)) {
                $this->syncRelations($entity, $relations);
            }

            if (count($dirty) > 0) {
                // Fire the updated event
                $this->getContainer('events')->dispatch($this->getRepositoryId().'.entity.updated', [$this, $entity]);
            }
        }

        return $updated ? $entity : $updated;
    }

    /**
     * {@inheritdoc}
     * @throws RepositoryException
     * @throws BindingResolutionException
     */
    public function create(array $attrs = [], bool $syncRelations = false): ?object
    {
        // Create a new instance
        $entity = $this->createModel();

        // Fire the created event
        $this->getContainer('events')->dispatch($this->getRepositoryId().'.entity.creating', [$this, $entity]);

        // Extract relationships
        if ($syncRelations) {
            $relations = $this->extractRelations($entity, $attrs);
            Arr::forget($attrs, array_keys($relations));
        }

        // Fill instance with data
        $entity->fill($attrs);

        // Save the instance
        $created = $entity->save();

        // Sync relationships
        if ($syncRelations && isset($relations)) {
            $this->syncRelations($entity, $relations);
        }

        // Fire the created event
        $this->getContainer('events')->dispatch($this->getRepositoryId().'.entity.created', [$this, $entity]);

        // Return instance
        return $created ? $entity : null;
    }

    /**
     * @inheritdoc
     * @throws BindingResolutionException
     * @throws JsonException
     * @throws RepositoryException
     */
    public function updateOrInsert($attributes, $values = [], $syncRelations = false)
    {
        $query = array_chunk($attributes, 3);

        if (count($query) > 1) {
            foreach ($query as $query_result) {
                $this->where($query_result[0], $query_result[1], $query_result[2]);
            }
        } else {
            $this->where($query[0][0], $query[0][1], $query[0][2]);
        }

        $entities = $this->findAll();

        if ($entities->count()) {
            foreach ($entities as $entity) {
                $this->update($entity->{$entity->getKeyName()}, $attributes, $syncRelations);
            }
        } else {
            $query_attribute[$query[0][0]] = $query[0][2];
            $attribute = array_merge($attributes, $query_attribute);
            $entities = $this->insert($attribute);
        }

        return $entities;
    }

    /**
     * @inheritDoc
     * @throws BindingResolutionException
     * @throws JsonException
     * @throws RepositoryException
     */
    public function insert($values)
    {
        // Create a new instance
        $entity = $this->createModel();

        // Fire the created event
        $this->getContainer('events')->dispatch($this->getRepositoryId().'.entity.creating', [$this, $entity]);

        $inserted = $this->executeCallback(
            static::class,
            __FUNCTION__,
            func_get_args(),
            fn() => $this->prepareQuery($this->createModel())->insert($values));

        // Fire the created event
        $this->getContainer('events')->dispatch($this->getRepositoryId().'.entity.created', [$this, $entity]);

        return $inserted;
    }

    /**
     * {@inheritdoc}
     */
    public function delete($id): false|object
    {
        $deleted = false;

        // Find the given instance
        $entity = $id instanceof Model ? $id : $this->find($id);

        if ($entity) {
            // Fire the deleted event
            $this->getContainer('events')->dispatch($this->getRepositoryId().'.entity.deleting', [$this, $entity]);

            // Delete the instance
            $deleted = $entity->delete();

            // Fire the deleted event
            $this->getContainer('events')->dispatch($this->getRepositoryId().'.entity.deleted', [$this, $entity]);
        }

        return $deleted ? $entity : $deleted;
    }

    /**
     * {@inheritdoc}
     */
    public function restore($id)
    {
        $restored = false;

        // Find the given instance
        $entity = $id instanceof Model ? $id : static::withTrashed()->find($id);

        if ($entity) {
            // Fire the restoring event
            $this->getContainer('events')->dispatch($this->getRepositoryId().'.entity.restoring', [$this, $entity]);

            // Restore the instance
            $restored = $entity->restore();

            // Fire the restored event
            $this->getContainer('events')->dispatch($this->getRepositoryId().'.entity.restored', [$this, $entity]);
        }

        return $restored ? $entity : $restored;
    }

    /**
     * {@inheritdoc}
     */
    public function beginTransaction(Closure $closure = null, Closure $closure_before = null, int $tries = 1)
    {
        if ($closure_before) {
            $closure_before();
        }

        if ($closure) {
            return $this->getContainer('db')->transaction($closure, $tries);
        }

        $this->getContainer('db')->beginTransaction();
    }

    /**
     * {@inheritdoc}
     */
    public function commit(): void
    {
        $this->getContainer('db')->commit();
    }

    /**
     * {@inheritdoc}
     */
    public function rollBack(): void
    {
        $this->getContainer('db')->rollBack();
    }

    /**
     * {@inheritdoc}
     *
     * @param $column
     * @return mixed
     * @throws ContainerExceptionInterface
     * @throws JsonException
     * @throws NotFoundExceptionInterface
     */
    public function min($column)
    {
        return $this->executeCallback(
            static::class,
            __FUNCTION__,
            func_get_args(),
            fn() => $this->prepareQuery($this->createModel())->min($column));
    }

    /**
     * {@inheritdoc}
     *
     * @param $column
     * @return mixed
     * @throws ContainerExceptionInterface
     * @throws JsonException
     * @throws NotFoundExceptionInterface
     */
    public function max($column)
    {
        return $this->executeCallback(
            static::class,
            __FUNCTION__,
            func_get_args(),
            fn() => $this->prepareQuery($this->createModel())->max($column));
    }

    /**
     * {@inheritdoc}
     * @throws JsonException
     */
    public function avg($column)
    {
        return $this->executeCallback(
            static::class,
            __FUNCTION__,
            func_get_args(),
            function () use ($column) {
                return $this->prepareQuery($this->createModel())->avg($column);
            }
        );
    }

    /**
     * {@inheritdoc}
     *
     * @throws JsonException
     */
    public function sum($column)
    {
        return $this->executeCallback(
            static::class,
            __FUNCTION__,
            func_get_args(),
            fn() => $this->prepareQuery($this->createModel())->sum($column));
    }

    /**
     * @inheritDoc
     * @throws JsonException
     */
    public function deletesBy($where, array $values = [])
    {
        return $this->executeCallback(
            static::class,
            __FUNCTION__,
            func_get_args(),
            fn() => $this->prepareQuery($this->createModel())->whereIn($where, $values)->delete()
        );
    }

    /**
     * @param $value
     * @param $operator
     * @param  bool  $useDefault
     * @return array
     */
    protected function prepareValueAndOperator($value, $operator, $useDefault = false): array
    {
        if ($useDefault) {
            return [$operator, '='];
        }

        if ($this->isInvalidOperatorAndValue($operator, $value)) {
            throw new InvalidArgumentException('Illegal operator and value combination.');
        }

        return [$value, $operator];
    }

    /**
     * @param $operator
     * @param $value
     * @return bool
     */
    protected function isInvalidOperatorAndValue($operator, $value): bool
    {
        return null === $value && in_array($operator, $this->operators, true) && !in_array($operator, ['=', '<>', '!=', '<', '>']);
    }
}
