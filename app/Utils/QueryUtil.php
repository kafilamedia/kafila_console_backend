<?php

namespace App\Utils;

use App\Dto\Filter;
use App\Models\BaseModel;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use ReflectionClass;
use Throwable;

class QueryUtil
{
    public static function rowMappedList(Collection $collection, $obj) : array
    {
        $mappedList = [];
    
        for ($i=0; $i < $collection->count(); $i++) {
            $item = $collection[$i];
            $obj_cloned = clone $obj;
            $mapped = QueryUtil::rowMapped($obj_cloned, $item);
            ($mappedList[$i] = $mapped);
        }
        
        return $mappedList;
    }
    public static function rowMapped(BaseModel $obj, object $rowDataRaw)
    {
        $reflectionClass = new ReflectionClass($obj);
        $rowData = get_object_vars($rowDataRaw);
        $leftJoins = [];
        foreach ($rowData as $key => $value) {
            if (Str::contains($key, 'MAPPED_LEFT_JOIN_') && Str::contains($key, '_LEFT_JOIN_KEY_END_')) {
                $arr1 = explode('MAPPED_LEFT_JOIN_', $key);
                $arr2 = explode('_LEFT_JOIN_KEY_END_', $arr1[1]);
                $table_name = $arr2[0];
                $table_key = $arr2[1];
                if (!Arr::has($leftJoins, $table_name) || is_null($leftJoins[$table_name])) {
                    $leftJoins[$table_name] = [];
                }
                $leftJoins[$table_name][$table_key] = $value;
            }
        }
        // dd($leftJoins);
        foreach ($rowData as $key => $value) {
            if ($reflectionClass->hasProperty($key)  && !is_null($rowData[$key])) {
                $obj->$key = $rowData[$key];
            }
        }
        //TODO: improve
        foreach ($leftJoins as $key => $value) {
            try {
                $obj->$key = $value;
            } catch (Throwable $th) {
                $obj->$key = null;
            }
        }
        // $obj->raw = $rowData;
        return $obj;
    }

    /**
     * generate select queries INLY, not query criteria
     * @return array select_fields
     */
    public static function setFillableSelect(BaseModel $joinObjectRaw, bool $as_join = false, string $join_alias = null) : array
    {
        $select_fields = [];
        $joinObject = clone $joinObjectRaw;
        $table_name = $joinObject->getTable();
        $fillable = $joinObject->getFillable();
        $filterable = $joinObject->getFilterable();

        $selects = array_merge($fillable, $filterable);

        foreach ($selects as $key) {
            if (false == ($as_join)) {
                $has_alias_for_filter_only = $joinObjectRaw->getAlias($key) != null;
                if (!$has_alias_for_filter_only) {
                    array_push($select_fields, $table_name.'.'.$key.' as '.$key);
                }
            } else {
                if (is_null($join_alias)) {
                    $join_alias = $table_name;
                }
                $join_key = 'MAPPED_LEFT_JOIN_'.$join_alias.'_LEFT_JOIN_KEY_END_';
                array_push($select_fields, $table_name.'.'.$key.' as '.$join_key.$key);
            }
        }
        return $select_fields;
    }

    public static function setFilterLimitOffsetOrder($query, Filter $filter = null)
    {
        QueryUtil::setFilter($query, $filter);
        QueryUtil::setLimitOffsetOrder($query, $filter);
    }

    public static function setLimitOffsetOrder($query, Filter $filter = null)
    {
        $has_filter = !is_null($filter);
        $limit = $has_filter ? $filter->limit : DEFAULT_LIMIT;
        $offset = $has_filter ? ($filter->page - 1) * $limit : 0;
        $order_by = $has_filter ? $filter->orderBy : null;
        $order_type = $has_filter ? ($filter->orderType == 'asc' ? 'asc' : 'desc') : 'asc';
        $query->skip($offset)->limit($limit);

        if (!is_null($order_by)) {
            $query->orderBy($order_by, $order_type);
        }
    }

    public static function setFilter($query, Filter $filter = null)
    {
        $has_filter = !is_null($filter);
        if ($has_filter == false) {
            return;
        }
        foreach ($filter->fieldsFilter as $key => $value) {
            if (!$filter->match) {
                $query->where($key, 'like', '%'.$value.'%');
            } else {
                $query->where($key, $value);
            }
        }
    }
}