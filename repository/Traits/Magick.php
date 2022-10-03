<?php

declare(strict_types=1);

namespace Repository\Traits;

use Illuminate\Contracts\Container\BindingResolutionException;
use Repository\Exceptions\RepositoryException;

trait Magick
{

    /**
     * {@inheritdoc}
     * @throws RepositoryException|BindingResolutionException
     */
    public function __call(string $method, array $parameters)
    {
        if (method_exists($model = $this->createModel(), $scope = 'scope'.ucfirst($method))) {
            $this->scope($method, $parameters);

            return $this;
        }

//        if (!method_exists(Builder::class, ucfirst($method))) {
//            $this->model()->{$method}(...$parameters);
//
//            return $this;
//        }

        return call_user_func_array([$this->createModel(), $method], $parameters);
    }

    /**
     * {@inheritdoc}
     */
    public static function __callStatic($method, $parameters)
    {
        return call_user_func_array([new static(), $method], $parameters);
    }
}
