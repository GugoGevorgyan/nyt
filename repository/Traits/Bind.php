<?php

declare(strict_types=1);

namespace Repository\Traits;

use Closure;

/**
 * Trait Bind
 * @package Repository\Traits
 */
trait Bind
{
    /**
     * The repository alias pattern.
     *
     * @var string
     */
    protected string $repositoryAliasPattern = '{{class}}Contract';

    /**
     * Register an IoC binding whether it's already been registered or not.
     * @param $abstract
     * @param  null  $concrete
     * @param  bool  $shared
     * @param  string|null  $alias
     * @param  bool  $force
     */
    protected function bindRepository($abstract, $concrete = null, bool $shared = true, string $alias = null, bool $force = false): void
    {
        if ($force || !$this->app->bound($abstract)) {
            $concrete = $concrete ?: $abstract;
            $this->app->bind($abstract, $concrete, $shared);
            $this->app->alias($abstract, $this->prepareRepositoryAlias($alias, $concrete));
        }
    }

    /**
     * Prepare the repository alias.
     *
     * @param  string|null  $alias
     * @param  mixed  $concrete
     *
     * @return string
     */
    protected function prepareRepositoryAlias(?string $alias, $concrete): string
    {
        if (!$alias && !$concrete instanceof Closure) {
            $concrete = str_replace('Repositories', 'Contracts', $concrete);
            $alias = str_replace('{{class}}', $concrete, $this->repositoryAliasPattern);
        }

        return $alias;
    }
}
