<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    protected array $filterable = [];
    //only for filter or order, not for select components
    protected array $filterable_aliases = [];

    protected function addFilterable(...$keys)
    {
        foreach ($keys as $key) {
            array_push($this->filterable, $key);
        }
        
    }
    protected function addFilterableAlias($filterable_key, $alias)
    {
        $this->filterable_aliases[$filterable_key] = $alias;
    }

    /**
     * Get the value of filterable
     */ 
    public function getFilterable()
    {
        return $this->filterable;
    }

    /**
     * Get the value of filterable_aliases
     */ 
    public function getFilterable_aliases()
    {
        return $this->filterable_aliases;
    }
    public function getAlias($key)
    {
        if (isset($this->filterable_aliases[$key])) {
            return $this->filterable_aliases[$key];
        }
        return null;
    }
}