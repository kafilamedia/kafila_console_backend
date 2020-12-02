<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FollowedUpIssue extends Model
{
    //
    protected $fillable = [
        'date',
        'description',
        'issue_id',
    ];

    protected int $id;
    protected string $description;
    protected $date;
    protected int $issue_id;
    protected Issue $issue;
    
    public function issue()
    {
        return $this->hasOne(Issue::class, 'issue_id');
    }
}
