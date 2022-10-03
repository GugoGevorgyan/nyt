<?php

declare(strict_types=1);

namespace Repository\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;
use Repository\Exceptions\RepositoryException;

use function get_class;

trait RelationsStore
{

    /**
     * Extract relationships.
     *
     * @param  mixed  $entity
     * @param  array  $attributes
     *
     * @return array
     */
    protected function extractRelations($entity, array $attributes): array
    {
        $relations = [];
        $potential = array_diff(array_keys($attributes), $entity->getFillable());

        array_walk(
            $potential,
            static function ($relation) use ($entity, $attributes, &$relations) {
                if (method_exists($entity, $relation)) {
                    $relations[$relation] = [
                        'values' => $attributes[$relation],
                        'class' => get_class($entity->{$relation}()),
                    ];
                }
            }
        );

        return $relations;
    }

    /**
     * Sync relationships.
     *
     * @param  mixed  $entity
     * @param  array  $relations
     * @param  bool  $detaching
     * @param  bool  $create
     * @return void
     * @throws RepositoryException
     */
    protected function syncRelations($entity, array $relations, bool $detaching = true, bool $create = true): void
    {
        foreach ($relations as $method => $relation) {
            switch ($relation['class']) {
                case BelongsToMany::class:
                    $entity->{$method}()->sync((array)$relation['values'], $detaching);
                    break;
                case HasMany::class:
                    $rel_repository = $this->getRelationRepositoryId($entity, $method);
                    $this->getContainer('events')->dispatch($rel_repository->getRepositoryId().'.entity.creating', [$this, $relation['values']]);
                    $entity->{$method}()->createMany($relation['values'], $detaching);
                    $this->getContainer('events')->dispatch($rel_repository->getRepositoryId().'.entity.created', [$this, $relation['values']]);
                    break;
                case HasOne::class:
                    $rel_repository = $this->getRelationRepositoryId($entity, $method);
                    $this->getContainer('events')->dispatch($rel_repository->getRepositoryId().'.entity.creating', [$this, $relation['values']]);
                    $entity->{$method}()->create($relation['values'], $detaching);
                    $this->getContainer('events')->dispatch($rel_repository->getRepositoryId().'.entity.created', [$this, $relation['values']]);
                    break;
                default:
                    throw new RepositoryException('Error relation type '.$relation['class'], 500);
            }
        }
    }

    /**
     * @param $entity
     * @param $method
     * @return mixed
     */
    protected function getRelationRepositoryId($entity, $method): mixed
    {
        $namespace = 'Src\Repositories\\';
        $xt_name = Str::afterLast(get_class($entity->{$method}()->getRelated()), '\\');
        return app($namespace.$xt_name.'\\'.$xt_name.'Contract');
    }
}
