<?php
declare(strict_types=1);


namespace Src\Support\Filters;


/**
 * Class BaseFilter
 * @package Src\Filters
 */
abstract class BaseFilter
{
    /**
     * @var
     */
    protected $app;

    /**
     * BaseFilter constructor.
     * @param $app
     */
    public function __construct($app)
    {
        $this->app = $app;
        $this->filter();
    }

    /**
     * @return mixed
     */
    abstract public function filter();
}
