<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departement extends Model
{
    //
    protected $fillable = [
        'name', 'description',
    ];

    protected int $id;
    protected $name;
    protected $description;
}
