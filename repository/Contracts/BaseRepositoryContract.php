<?php

declare(strict_types=1);

namespace Repository\Contracts;

use Closure;
use Exception;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use Repository\Exceptions\RepositoryException;
use Repository\Repositories\Repository;
use RuntimeException;

/**
 * Interface BaseRepositoryContract
 * @method forgetCache()
 * @method isCacheClearEnabled()
 * @method getCacheLifetime()
 * @method distanceCord($latitude, $longitude, string $distance = 1): self
 * @method distance($latitude, $longitude): self
 * @method withoutGlobalScopes($scopes = null)
 */
interface BaseRepositoryContract extends WhereClauseContract
{
    /**
     * Dynamically pass missing static methods to the model.
     *
     * @param $method
     * @param $parameters
     *
     * @return mixed
     */
    public static function __callStatic($method, $parameters);

    /**
     * @param  array  $attributes
     * @return Model
     */
    public function model($attributes = []): Model;

    /**
     * Get Model fillable array
     *
     * @return array|null
     */
    public function getFillable(): ?array;

    /**
     * Get Searchable Fields
     *
     * @return array
     */
    public function getFieldsSearchable(): array;

    /**
     * Get Model primary key name
     *
     * @return Model Primary Key Name
     */
    public function getKeyName(): ?string;

    /**
     * Get Model primary key name
     *
     * @return Model Primary Key Name
     */
    public function getTable(): ?string;

    /**
     * Retrieve the "count" result of the query.
     *
     * @param  string  $column
     * @return bool
     */
    public function exists(string $column = '*'): bool;

    /**
     * @param $where
     * @param  array  $values
     * @return mixed
     */
    public function deletesBy($where, array $values = []);

    /**
     * @param  null  $column
     * @param  array  $attributes
     * @return object|null
     */
    public function firstLatest($column = null, array $attributes = ['*']): ?object;

    /**
     * @param $column
     * @return mixed
     */
    public function firstOldest($column = null);

    /**
     * @param $column
     * @return Builder
     */
    public function latest($column = null);

    /**
     * @param $column
     * @return Builder
     */
    public function oldest($column = null);

    /**
     * Update an entity with the given attributes.
     *
     * @param  array  $attributes
     * @param  bool  $syncRelations
     *
     * @return bool|Collection
     */
    public function updateSet(array $attributes = [], bool $syncRelations = false): \Illuminate\Support\Collection|bool;

    /**
     * Update an entity with the given attributes.
     *
     * @return bool
     */
    public function deletes(): bool;

    /**
     * @param $values
     * @return mixed
     */
    public function insert($values);

    /**
     * @param $against
     * @param  mixed  ...$matches
     * @return null|WhereClauseContract
     */
    public function fullSearch($against, ...$matches): null|WhereClauseContract;

    /**
     * Find all entities matching where conditions.
     *
     * @param  array  $where
     * @param  array  $attributes
     *
     * @return mixed
     */
    public function firstWhere(array $where, $attributes = ['*']);

    /**
     * Set the IoC container instance.
     *
     * @param  Container  $container
     *
     * @return static
     */
    public function setContainer(Container $container);

    /**
     * Get the IoC container instance or any of its services.
     *
     * @param  string|null  $service
     *
     * @return mixed
     */
    public function getContainer($service = null);

    /**
     * Set the connection associated with the repository.
     *
     * @param  string  $name
     *
     * @return static
     */
    public function setConnection($name);

    /**
     * Get the current connection for the repository.
     *
     * @return string
     */
    public function getConnection(): string;

    /**
     * Set the repository identifier.
     *
     * @param  string  $repositoryId
     *
     * @return static
     */
    public function setRepositoryId($repositoryId): Repository|BaseRepositoryContract|static;

    /**
     * Set the repository return data to collection.
     *
     * @return Repository|BaseRepositoryContract
     */
    public function setPayloadCollect(): Repository|BaseRepositoryContract|static;

    /**
     * Get the repository identifier.
     *
     * @return string
     */
    public function getRepositoryId(): string;

    /**
     * Set the repository model.
     *
     * @param  string  $model
     *
     * @return static
     */
    public function setModel($model);

    /**
     * Get the repository model.
     *
     * @return string
     */
    public function getModel(): string;

    /**
     * Get the repository model.
     *
     * @return string
     */
    public function getMap(): string;

    /**
     * Create a new repository model instance.
     *
     * @return mixed
     * @throws RepositoryException
     *
     */
    public function createModel();

    /**
     * Set the relationships that should be eager loaded.
     *
     * @param  array|string  $rels
     *
     * @return static
     */
    public function with($rels): WhereClauseContract;

    /**
     * Find an entity by its primary key.
     *
     * @param  int  $id
     * @param  string[]  $attrs
     * @return object|null
     */
    public function find($id, $attrs = ['*']): ?object;

    /**
     * Find an entity by its primary key or throw an exception.
     *
     * @param  mixed  $id
     * @param  array  $attributes
     *
     * @return mixed
     * @throws RuntimeException
     *
     */
    public function findOrFail($id, $attributes = ['*']);

    /**
     * Find an entity by its primary key or return fresh entity instance.
     *
     * @param  mixed  $id
     * @param  array  $attributes
     *
     * @return mixed
     */
    public function findOrNew($id, $attributes = ['*']);

    /**
     * Find an entity by one of its attributes.
     *
     * @param  string  $attribute
     * @param  string  $value
     * @param  array  $attributes
     *
     * @return mixed
     */
    public function findBy($attribute, $value, $attributes = ['*']);

    /**
     * Find the first entity.
     *
     * @param  array  $attr
     *
     * @return object|null
     */
    public function findFirst($attr = ['*']): object|null;

    /**
     * Find all entities.
     *
     * @param  array  $attr
     *
     * @return \Illuminate\Support\Collection
     */
    public function findAll($attr = ['*']): \Illuminate\Support\Collection;

    /**
     * Paginate all entities.
     *
     * @param  int|null  $perPage
     * @param  array  $attributes
     * @param  string  $pageName
     * @param  int|null  $page
     *
     * @return LengthAwarePaginator
     * @throws InvalidArgumentException
     *
     */
    public function paginate($perPage = null, $attributes = ['*'], $pageName = 'page', $page = null);

    /**
     * Paginate all entities into a simple paginator.
     *
     * @param  int|null  $perPage
     * @param  array  $attributes
     * @param  string  $pageName
     *
     * @return Paginator
     */
    public function simplePaginate($perPage = null, $attributes = ['*'], $pageName = 'page');

    /**
     * @param  null  $perPage
     * @param  string[]  $columns
     * @param  string  $cursorName
     * @param  null  $cursor
     * @return mixed
     */
    public function cursorPaginate($perPage = null, $columns = ['*'], $cursorName = 'cursor', $cursor = null);

    /**
     * Find all entities matching where conditions.
     *
     * @param  array  $where
     * @param  array  $attrs
     *
     * @return \Illuminate\Support\Collection
     */
    public function findWhere(array $where, $attrs = ['*']);

    /**
     * Find all entities matching whereIn conditions.
     *
     * @param  array  $where
     * @param  array  $attributes
     *
     * @return \Illuminate\Support\Collection
     */
    public function findWhereIn(array $where, $attributes = ['*']);

    /**
     * Find all entities matching whereNotIn conditions.
     *
     * @param  array  $where
     * @param  array  $attributes
     *
     * @return \Illuminate\Support\Collection
     */
    public function findWhereNotIn(array $where, $attributes = ['*']);

    /**
     * Find all entities matching whereHas conditions.
     *
     * @param  array  $where
     * @param  array  $attributes
     *
     * @return \Illuminate\Support\Collection
     */
    public function findWhereHas(array $where, $attributes = ['*']);

    /**
     * @param  array  $where
     * @param  array  $attrs
     * @param  bool  $syncRelations
     * @param  bool  $merge
     * @return mixed
     */
    public function updateOrCreate(array $where, array $attrs, bool $syncRelations = false, bool $merge = false);

    /**
     * @param $attributes
     * @param  array  $values
     * @return mixed
     */
    public function updateOrInsert($attributes, $values = [], $syncRelations = false);

    /**
     * Create a new entity with the given attributes.
     *
     * @param  array  $attrs
     * @param  bool  $syncRelations
     *
     * @return null|object
     */
    public function create(array $attrs = [], bool $syncRelations = false): ?object;

    /**
     * Update an entity with the given attributes.
     *
     * @param  mixed  $id
     * @param  array  $attrs
     * @param  bool  $syncRelations
     *
     * @return bool|object
     */
    public function update($id, array $attrs = [], bool $syncRelations = false): bool|object;

    /**
     * Store the entity with the given attributes.
     *
     * @param  mixed  $id
     * @param  array  $attrs
     * @param  bool  $syncRelations
     *
     * @return mixed
     */
    public function store(int $id = null, array $attrs = [], bool $syncRelations = false);

    /**
     * Delete an entity with the given id.
     *
     * @param  mixed  $id
     *
     * @return bool|object
     */
    public function delete($id): false|object;

    /**
     * Restore an entity with the given id.
     *
     * @param  mixed  $id
     *
     * @return mixed
     */
    public function restore($id);

    /**
     * Start a new database transaction.
     *
     * @param  Closure|null  $closure
     * @param  Closure|null  $closure_before
     * @param  int  $tries
     * @return void|mixed
     * @throws Exception
     */
    public function beginTransaction(Closure $closure = null, Closure $closure_before = null, int $tries = 1);

    /**
     * Commit the active database transaction.
     *
     * @return void
     */
    public function commit(): void;

    /**
     * Rollback the active database transaction.
     *
     * @return void
     */
    public function rollBack(): void;

    /**
     * Retrieve the "count" result of the query.
     *
     * @param  string  $columns
     *
     * @return int
     */
    public function count($columns = '*'): int;

    /**
     * Retrieve the minimum value of a given column.
     *
     * @param  string  $column
     *
     * @return mixed
     */
    public function min($column);

    /**
     * Retrieve the maximum value of a given column.
     *
     * @param  string  $column
     *
     * @return mixed
     */
    public function max($column);

    /**
     * Retrieve the average value of a given column.
     *
     * @param  string  $column
     *
     * @return mixed
     */
    public function avg($column);

    /**
     * Retrieve the sum of the values of a given column.
     *
     * @param  string  $column
     *
     * @return mixed
     */
    public function sum($column);

    /**
     * Dynamically pass missing methods to the model.
     *
     * @param  string  $method
     * @param  array  $parameters
     *
     * @return mixed
     */
    public function __call(string $method, array $parameters);
}
