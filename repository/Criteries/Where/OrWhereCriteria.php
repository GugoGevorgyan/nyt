<?php

declare(strict_types=1);


namespace Repository\Criteries\Where;


use Repository\Contracts\BaseCriteriaContract;
use Repository\Contracts\BaseRepositoryContract;

/**
 * Class OrWhereCriteria
 * @package Src\Criteries\Where
 */
class OrWhereCriteria implements BaseCriteriaContract
{
    /**
     * @var string
     */
    protected $attribute;

    /**
     * @var string
     */
    protected $value;

    /**
     * @var string
     */
    protected $sign;

    /**
     * @param $attribute
     * @param $value
     * @param  string  $sign
     */
    public function __construct($attribute, $value, $sign = '=')
    {
        $this->attribute = $attribute;
        $this->value = $value;
        $this->sign = $sign;
    }

    /**
     * @param  mixed  $query
     * @param  BaseRepositoryContract  $repository
     * @return mixed
     */
    public function apply($query, BaseRepositoryContract $repository)
    {
        return $query->orWhere($this->attribute, $this->sign, $this->value);
    }
}
