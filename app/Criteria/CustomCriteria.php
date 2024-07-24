<?php

namespace App\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Class CustomCriteriaCriteria.
 *
 * @package namespace App\Criteria;
 */
/*
                                                   .~))>>
                                                  .~)>>
                                                .~))))>>>
                                              .~))>>             ___
                                            .~))>>)))>>      .-~))>>
                                          .~)))))>>       .-~))>>)>
                                        .~)))>>))))>>  .-~)>>)>
                    )                 .~))>>))))>>  .-~)))))>>)>
                 ( )@@*)             //)>))))))  .-~))))>>)>
               ).@(@@               //))>>))) .-~))>>)))))>>)>
             (( @.@).              //))))) .-~)>>)))))>>)>
           ))  )@@*.@@ )          //)>))) //))))))>>))))>>)>
        ((  ((@@@.@@             |/))))) //)))))>>)))>>)>
       )) @@*. )@@ )   (\_(\-\b  |))>)) //)))>>)))))))>>)>
     (( @@@(.@(@ .    _/`-`  ~|b |>))) //)>>)))))))>>)>
      )* @@@ )@*     (@)  (@) /\b|))) //))))))>>))))>>
    (( @. )@( @ .   _/  /    /  \b)) //))>>)))))>>>_._
     )@@ (@@*)@@.  (6///6)- / ^  \b)//))))))>>)))>>   ~~-.
  ( @jgs@@. @@@.*@_ VvvvvV//  ^  \b/)>>))))>>      _.     `bb
   ((@@ @@@*.(@@ . - | o |' \ (  ^   \b)))>>        .'       b`,
    ((@@).*@@ )@ )   \^^^/  ((   ^  ~)_        \  /           b `,
      (@@. (@@ ).     `-'   (((   ^    `\ \ \ \ \|             b  `.
        (*.@*              / ((((        \| | |  \       .       b `.
                          / / (((((  \    \ /  _.-~\     Y,      b  ;
                         / / / (((((( \    \.-~   _.`" _.-~`,    b  ;
                        /   /   `(((((()    )    (((((~      `,  b  ;
                      _/  _/      `"""/   /'                  ; b   ;
                  _.-~_.-~           /  /'                _.'~bb _.'
                ((((~~              / /'              _.'~bb.--~
                                   ((((          __.-~bb.-~
                                               .'  b .~~
                                               :bb ,'
                                               ~~~~
 */
class CustomCriteria implements CriteriaInterface
{
    /**
     * Apply criteria in query repository
     *
     * @param string              $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */

    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply($model, RepositoryInterface $repository)
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
        $sortedBy = !empty($sortedBy) ? $sortedBy : 'desc';
        $conditions = $this->request->get(config('repository.criteria.params.conditions', 'conditions'), null);

        if ($search && is_array($fieldsSearchable) && count($fieldsSearchable)) {

            $searchFields = is_array($searchFields) || is_null($searchFields) ? $searchFields : explode(';', $searchFields);
            $isFirstField = true;
            $searchData = $this->parserSearchData($search);
            $fields = $this->parserFieldsSearch($fieldsSearchable, $searchFields, array_keys($searchData));
            $search = $this->parserSearchValue($search);
            $modelForceAndWhere = strtolower($searchJoin) === 'or';

            $model = $model->where(function ($query) use ($fields, $search, $searchData, $isFirstField, $modelForceAndWhere) {
                /** @var Builder $query */

                foreach ($fields as $field => $condition) {

                    if (is_numeric($field)) {
                        $field = $condition;
                        $condition = "=";
                    }

                    $value = null;

                    $condition = trim(strtolower($condition));

                    if (isset($searchData[$field])) {
                        $value = ($condition == "like" || $condition == "ilike") ? "%{$searchData[$field]}%" : $searchData[$field];
                    } else {
                        if (!is_null($search) && !in_array($condition, ['in', 'between'])) {
                            $value = ($condition == "like" || $condition == "ilike") ? "%{$search}%" : $search;
                        }
                    }

                    $relation = null;
                    if (stripos($field, '.')) {
                        $explode = explode('.', $field);
                        $field = array_pop($explode);
                        $relation = implode('.', $explode);
                    }
                    if ($condition === 'in') {
                        $value = explode(',', $value);
                        if (trim($value[0]) === "" || $field == $value[0]) {
                            $value = null;
                        }
                    }
                    if ($condition === 'between') {
                        $value = explode(',', $value);
                        if (count($value) < 2) {
                            $value = null;
                        }
                    }
                    $modelTableName = $query->getModel()->getTable();
                    if ($isFirstField || $modelForceAndWhere) {
                        if (!is_null($value)) {
                            if (!is_null($relation)) {
                                $query->whereHas($relation, function ($query) use ($field, $condition, $value) {
                                    if ($condition === 'in') {
                                        $query->whereIn($field, $value);
                                    } elseif ($condition === 'between') {
                                        $query->whereBetween($field, $value);
                                    } else {
                                        $query->where($field, $condition, $value);
                                    }
                                });
                            } else {
                                if ($condition === 'in') {
                                    $query->whereIn($modelTableName . '.' . $field, $value);
                                } elseif ($condition === 'between') {
                                    $query->whereBetween($modelTableName . '.' . $field, $value);
                                } else {
                                    $query->where($modelTableName . '.' . $field, $condition, $value);
                                }
                            }
                            $isFirstField = false;
                        }
                    } else {
                        if (!is_null($value)) {
                            if (!is_null($relation)) {
                                $query->orWhereHas($relation, function ($query) use ($field, $condition, $value) {
                                    if ($condition === 'in') {
                                        $query->whereIn($field, $value);
                                    } elseif ($condition === 'between') {
                                        $query->whereBetween($field, $value);
                                    } else {
                                        $query->where($field, $condition, $value);
                                    }
                                });
                            } else {
                                if ($condition === 'in') {
                                    $query->orWhereIn($modelTableName . '.' . $field, $value);
                                } elseif ($condition === 'between') {
                                    $query->whereBetween($modelTableName . '.' . $field, $value);
                                } else {
                                    $query->orWhere($modelTableName . '.' . $field, $condition, $value);
                                }
                            }
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
                    $sortedBy = isset($sortedBySplit[$orderBySplitItemKey]) ? $sortedBySplit[$orderBySplitItemKey] : $sortedBySplit[0];
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

            $conditionArr = [];

            if (isset($conditions) && !empty($conditions)) {
                $conditions = explode(';', $conditions);
            }

            if (isset($conditions) && !empty($conditions)) {
                foreach ($conditions as $condition) {
                    list($key, $value) = explode(':', $condition);
    
                    $conditionFields = explode('.', $key);
    
                    $key = end($conditionFields);
    
                    $conditionArr[$key] = $value;
                }
            }

            foreach ($filter as $filterItem) {
                list($filterField, $filterValue) = explode(':', $filterItem);

                $fields = explode('.', $filterField);

                $filterValue = $this->parserFilterValue($filterValue);

                if ($filterValue == 'null') {
                    $filterValue = null;
                }

                if ($filterValue == 'notnull') {
                    $filterValue = '!null';
                }

                $nestedRelation = '';
                foreach ($fields as $index => $field) {
                    if ($index === count($fields) - 1) {
                        if (!empty($nestedRelation)) {
                            $model = $this->applyFilterCondition($model, $nestedRelation, $field, $filterValue, $conditionArr[$field] ?? null);
                        } else {
                            $model = $this->applyFilterCondition($model, '', $field, $filterValue, $conditionArr[$field] ?? null);
                        }
                    } else {
                        $nestedRelation .= ($nestedRelation ? '.' : '') . $field;
                    }
                }
            }
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
     * @param $model
     * @param $orderBy
     * @param $sortedBy
     * @return mixed
     */
    protected function parserFieldsOrderBy($model, $orderBy, $sortedBy)
    {
        $split = explode('|', $orderBy);
        if (count($split) > 1) {
            /*
             * ex.
             * products|description -> join products on current_table.product_id = products.id order by description
             *
             * products:custom_id|products.description -> join products on current_table.custom_id = products.id order
             * by products.description (in case both tables have same column name)
             */
            $table = $model->getModel()->getTable();
            $sortTable = $split[0];
            $sortColumn = $split[1];

            $split = explode(':', $sortTable);
            $localKey = '.id';
            if (count($split) > 1) {
                $sortTable = $split[0];

                $commaExp = explode(',', $split[1]);
                $keyName = $table . '.' . $split[1];
                if (count($commaExp) > 1) {
                    $keyName = $table . '.' . $commaExp[0];
                    $localKey = '.' . $commaExp[1];
                }
            } else {
                /*
                 * If you do not define which column to use as a joining column on current table, it will
                 * use a singular of a join table appended with _id
                 *
                 * ex.
                 * products -> product_id
                 */
                $prefix = Str::singular($sortTable);
                $keyName = $table . '.' . $prefix . '_id';
            }

            $model = $model
                ->leftJoin($sortTable, $keyName, '=', $sortTable . $localKey)
                ->orderBy($sortColumn, $sortedBy)
                ->addSelect($table . '.*');
        } else {
            $model = $model->orderBy($orderBy, $sortedBy);
        }
        return $model;
    }

    /**
     * @param $search
     *
     * @return array
     */
    protected function parserSearchData($search)
    {
        $searchData = [];

        if (stripos($search, ':')) {
            $fields = explode(';', $search);

            foreach ($fields as $row) {
                try {
                    list($field, $value) = explode(':', $row);
                    $searchData[$field] = $value;
                } catch (\Exception $e) {
                    //Surround offset error
                }
            }
        }

        return $searchData;
    }

    /**
     * @param $search
     *
     * @return null
     */
    protected function parserSearchValue($search)
    {

        if (stripos($search, ';') || stripos($search, ':')) {
            $values = explode(';', $search);
            foreach ($values as $value) {
                $s = explode(':', $value);
                if (count($s) == 1) {
                    return $s[0];
                }
            }

            return null;
        }

        return $search;
    }

    /**
     * Parse the filter value.
     *
     * @param  mixed  $value
     * @return mixed
     */
    function parserFilterValue($filter)
    {
        if (stripos($filter, ';') || stripos($filter, ':')) {
            $values = explode(';', $filter);
            foreach ($values as $value) {
                $s = explode(':', $value);
                if (count($s) == 1) {
                    return $s[0];
                }
            }

            return null;
        }

        return $filter;
    }


    protected function parserFieldsSearch(array $fields = [], array $searchFields = null, array $dataKeys = null)
    {
        if (!is_null($searchFields) && count($searchFields)) {
            $acceptedConditions = config('repository.criteria.acceptedConditions', [
                '=',
                'like'
            ]);
            $originalFields = $fields;
            $fields = [];

            foreach ($searchFields as $index => $field) {
                $field_parts = explode(':', $field);
                $temporaryIndex = array_search($field_parts[0], $originalFields);

                if (count($field_parts) == 2) {
                    if (in_array($field_parts[1], $acceptedConditions)) {
                        unset($originalFields[$temporaryIndex]);
                        $field = $field_parts[0];
                        $condition = $field_parts[1];
                        $originalFields[$field] = $condition;
                        $searchFields[$index] = $field;
                    }
                }
            }

            if (!is_null($dataKeys) && count($dataKeys)) {
                $searchFields = array_unique(array_merge($dataKeys, $searchFields));
            }

            foreach ($originalFields as $field => $condition) {
                if (is_numeric($field)) {
                    $field = $condition;
                    $condition = "=";
                }
                if (in_array($field, $searchFields)) {
                    $fields[$field] = $condition;
                }
            }

            if (count($fields) == 0) {
                throw new \Exception(trans('repository::criteria.fields_not_accepted', ['field' => implode(',', $searchFields)]));
            }
        }

        return $fields;
    }

    // Add this function to apply filter condition
    protected function applyFilterCondition($model, $nestedRelation, $field, $filterValue, $condition = '=')
    {
        if (!$condition) {
            $condition = '=';
        }        

        if (!empty($nestedRelation)) {
            return $model->whereHas($nestedRelation, function ($query) use ($field, $condition, $filterValue) {
                return $this->applyFilterCondition($query, '', $field, $filterValue, $condition?? '=');
            });
        } else {
            switch ($condition) {
                case 'notin':
                    if (!is_array($filterValue)) {
                        $arrValue = explode(',', $filterValue);
                    }
                    return $model->whereNotIn($field, $arrValue);
                    break;
                case 'in':
                    if (!is_array($filterValue)) {
                        $arrValue = explode(',', $filterValue);
                    }
                    return $model->whereIn($field, $arrValue);
                    break;
                case 'between':
                    if (!is_array($filterValue)) {
                        $filterValue = explode(',', $filterValue);
                    }
                    return $model->whereBetween($field, $filterValue);
                    break;
                case 'before':
                    return $model->where($field, '<', $filterValue);
                    break;
                case 'after':
                    return $model->where($field, '>', $filterValue);
                    break;
                default:
                    if ($filterValue == '!null') {
                        return $model->whereNotNull($field);
                    }else{
                        return $model->where($field, $condition, $filterValue);
                    }
                    break;
            }
        }
    }
}
