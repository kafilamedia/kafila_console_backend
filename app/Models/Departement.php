<?php

namespace App\Models;

class Departement extends BaseModel
{
    //
    protected $fillable = [
        'name', 'description',
    ];

    protected int $id;
    protected $name;
    protected $description;
    
    
}
