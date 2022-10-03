<?php

declare(strict_types=1);

namespace Repository\Criteries;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Repository\Contracts\BaseCriteriaContract;
use Repository\Contracts\BaseRepositoryContract;

use function count;
use function in_array;
use function is_array;
use function is_string;

/**
 * Class RequestCriteria
 * @package Repository\Criteries
 */
class RequestCriteria implements BaseCriteriaContract
{
    /**
     * @var Request
     */
    protected Request $request;

    /**
     * RequestCriteria constructor.
     * @param  Request  $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Apply criteria in query repository
     *
     * @param  Builder|Model  $model
     * @param  BaseRepositoryContract  $repository
     *
     * @return Builder|Model
     * @throws Exception
     */
    public function apply($model, BaseRepositoryContract $repository): Model|Builder
    {
        $fieldsSearchable = $repository->getFieldsSearchable();
        $search = $this->request->get(config('repository.criteria.params.search', 'search'), null);
        $searchFields = $this->request->get(config('repository.criteria.params.searchFields', 'searchFields'), null);
        $filter = $this->request->get(config('repository.criteria.params.filter', 'filter'), null);
        $orderBy = $this->request->get(config('repository.criteria.params.orderBy', 'orderBy'), null);
        $sortedBy = $this->request->get(config('repository.criteria.params.sortedBy', 'sortedBy'), 'asc');
        $with = $this->request->get(config('repository.criteria.params.with', 'with'), null);
        $withCount = $this->request->get(config('repository.criteria.params.withCount', 'withCount'), null);
        $searchJoin = $this->request->get(config('repository.criteria.params.searchJoin', 'searchJoin'), null);
        $sortedBy = !empty($sortedBy) ? $sortedBy : 'asc';

        if ($search && is_array($fieldsSearchable) && count($fieldsSearchable)) {
            $searchFields = is_array($searchFields) || null === $searchFields ? $searchFields : explode(';', $searchFields);
            $fields = $this->parserFieldsSearch($fieldsSearchable, $searchFields);
            $isFirstField = true;
            $searchData = $this->parserSearchData($search);
            $search = $this->parserSearchValue($search);
            $modelForceAndWhere = 'and' === strtolower($searchJoin);

            $model = $model->where(function ($query) use ($fields, $search, $searchData, $isFirstField, $modelForceAndWhere) {
                /** @var Builder $query */

                foreach ($fields as $field => $condition) {
                    if (is_numeric($field)) {
                        $field = $condition;
                        $condition = '=';
                    }

                    $value = null;

                    $condition = strtolower(trim($condition));

                    if (isset($searchData[$field])) {
                        $value = ('like' === $condition || 'ilike' === $condition) ? "%{$searchData[$field]}%" : $searchData[$field];
                    } elseif (null !== $search && !in_array($condition, ['in', 'between'])) {
                        $value = ('like' === $condition || 'ilike' === $condition) ? "%{$search}%" : $search;
                    }

                    $relation = null;
                    if (strpos($field, '.')) {
                        $explode = explode('.', $field);
                        $field = array_pop($explode);
                        $relation = implode('.', $explode);
                    }
                    if ('in' === $condition) {
                        $value = explode(',', $value);
                        if ('' === trim($value[0]) || $field == $value[0]) {
                            $value = null;
                        }
                    }
                    if ('between' === $condition) {
                        $value = explode(',', $value);
                        if (count($value) < 2) {
                            $value = null;
                        }
                    }
                    $modelTableName = $query->getModel()->getTable();
                    if ($isFirstField || $modelForceAndWhere) {
                        if (null !== $value) {
                            if (null === $relation) {
                                if ('in' === $condition) {
                                    $query->whereIn($modelTableName.'.'.$field, $value);
                                } elseif ('between' === $condition) {
                                    $query->whereBetween($modelTableName.'.'.$field, $value);
                                } else {
                                    $query->where($modelTableName.'.'.$field, $condition, $value);
                                }
                            } else {
                                $query->whereHas($relation, function ($query) use ($field, $condition, $value) {
                                    if ('in' === $condition) {
                                        $query->whereIn($field, $value);
                                    } elseif ('between' === $condition) {
                                        $query->whereBetween($field, $value);
                                    } else {
                                        $query->where($field, $condition, $value);
                                    }
                                });
                            }
                            $isFirstField = false;
                        }
                    } elseif (null !== $value) {
                        if (null === $relation) {
                            if ('in' === $condition) {
                                $query->orWhereIn($modelTableName.'.'.$field, $value);
                            } elseif ('between' === $condition) {
                                $query->whereBetween($modelTableName.'.'.$field, $value);
                            } else {
                                $query->orWhere($modelTableName.'.'.$field, $condition, $value);
                            }
                        } else {
                            $query->orWhereHas($relation, function ($query) use ($field, $condition, $value) {
                                if ('in' === $condition) {
                                    $query->whereIn($field, $value);
                                } elseif ('between' === $condition) {
                                    $query->whereBetween($field, $value);
                                } else {
                                    $query->where($field, $condition, $value);
                                }
                            });
                        }
                    }
                }
            });
        }

        if (isset($orderBy) && !empty($orderBy)) {
            $orderBySplit = explode(';', $orderBy);
            if (count($orderBySplit) > 1) {
                $sortedBySplit = explode(';', $sortedBy);
                foreach ($orderBySplit as $orderBySplitItemKey => $orderBySplitItem) {
                    $sortedBy = $sortedBySplit[$orderBySplitItemKey] ?? $sortedBySplit[0];
                    $model = $this->parserFieldsOrderBy($model, $orderBySplitItem, $sortedBy);
                }
            } else {
                $model = $this->parserFieldsOrderBy($model, $orderBySplit[0], $sortedBy);
            }
        }

        if (isset($filter) && !empty($filter)) {
            if (is_string($filter)) {
                $filter = explode(';', $filter);
            }

            $model = $model->select($filter);
        }

        if ($with) {
            $with = explode(';', $with);
            $model = $model->with($with);
        }

        if ($withCount) {
            $withCount = explode(';', $withCount);
            $model = $model->withCount($withCount);
        }

        return $model;
    }

    /**
     * @param  array  $fields
     * @param  array|null  $searchFields
     * @return array
     * @throws Exception
     */
    protected function parserFieldsSearch(array $fields = [], array $searchFields = null): array
    {
        if (null !== $searchFields && count($searchFields)) {
            $acceptedConditions = config('repository.criteria.acceptedConditions', [
                '=',
                'like'
            ]);
            $originalFields = $fields;
            $fields = [];

            foreach ($searchFields as $index => $field) {
                $field_parts = explode(':', $field);
                $temporaryIndex = array_search($field_parts[0], $originalFields);

                if ((2 == count($field_parts)) && in_array($field_parts[1], $acceptedConditions, true)) {
                    unset($originalFields[$temporaryIndex]);
                    $field = $field_parts[0];
                    $condition = $field_parts[1];
                    $originalFields[$field] = $condition;
                    $searchFields[$index] = $field;
                }
            }

            foreach ($originalFields as $field => $condition) {
                if (is_numeric($field)) {
                    $field = $condition;
                    $condition = '=';
                }
                if (in_array($field, $searchFields)) {
                    $fields[$field] = $condition;
                }
            }

            if (0 === count($fields)) {
                throw new Exception(trans('repository::criteria.fields_not_accepted', ['field' => implode(',', $searchFields)]));
            }
        }

        return $fields;
    }

    /**
     * @param $search
     *
     * @return array
     */
    protected function parserSearchData($search): array
    {
        $searchData = [];

        if (strpos($search, ':')) {
            $fields = explode(';', $search);

            foreach ($fields as $row) {
                try {
                    [$field, $value] = explode(':', $row);
                    $searchData[$field] = $value;
                } catch (Exception $e) {
                    //Surround offset error
                }
            }
        }

        return $searchData;
    }

    /**
     * @param $search
     * @return string|null
     */
    protected function parserSearchValue($search): ?string
    {
        if (strpos($search, ';') || strpos($search, ':')) {
            $values = explode(';', $search);
            foreach ($values as $value) {
                $s = explode(':', $value);
                if (1 === count($s)) {
                    return $s[0];
                }
            }

            return null;
        }

        return $search;
    }

    /**
     * @param $model
     * @param $orderBy
     * @param $sortedBy
     * @return mixed
     */
    protected function parserFieldsOrderBy($model, $orderBy, $sortedBy): mixed
    {
        $split = explode('|', $orderBy);
        if (count($split) > 1) {
            $table = $model->getModel()->getTable();
            $sortTable = $split[0];
            $sortColumn = $split[1];

            $split = explode(':', $sortTable);
            $localKey = '.id';
            if (count($split) > 1) {
                $sortTable = $split[0];

                $commaExp = explode(',', $split[1]);
                $keyName = $table.'.'.$split[1];
                if (count($commaExp) > 1) {
                    $keyName = $table.'.'.$commaExp[0];
                    $localKey = '.'.$commaExp[1];
                }
            } else {
                /*
                 * products -> product_id
                 */
                $prefix = Str::singular($sortTable);
                $keyName = $table.'.'.$prefix.'_id';
            }

            $model = $model
                ->leftJoin($sortTable, $keyName, '=', $sortTable.$localKey)
                ->orderBy($sortColumn, $sortedBy)
                ->addSelect($table.'.*');
        } else {
            $model = $model->orderBy($orderBy, $sortedBy);
        }

        return $model;
    }
}
