<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    protected array $filterable = [];
    protected array $filterable_aliases = [];

    protected function addFilterable(...$keys)
    {
        foreach ($keys as $key) {
            array_push($this->filterable, $key);
        }
        
    }
    protected function addFilterableAlias($filterable_key)
    {
        $this->filterable_aliases[$filterable_key] = parent::getTable().'.'.$filterable_key;
        
    }

    /**
     * Get the value of filterable
     */ 
    public function getFilterable()
    {
        return $this->filterable;
    }
}