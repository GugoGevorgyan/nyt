<?php

declare(strict_types=1);


namespace Src\Core\Traits;


/**
 * Trait HasModules
 * @package Src\Traits
 */
trait Excludable
{
    /**
     * Exclude an array of elements from the result.
     * @param $query
     * @param $columns
     * @return mixed
     */
    public function scopeExclude($query, $columns): mixed
    {
        return $query->select(array_diff($this->getTableColumns(), (array)$columns));
    }

    /**
     * Get the array of columns
     * @return mixed
     */
    public function getTableColumns(): mixed
    {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }

}
