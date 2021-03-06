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
        'user_id'
    ];

    protected int $id;
    protected string $description;
    protected $date;
    protected int $issue_id;
    protected int $user_id;
    protected Issue $issue;
    protected User $user;
    
    public function user()
    {
        return $this->hasOne(User::class, 'user_id');
    }
    public function issue()
    {
        return $this->hasOne(Issue::class, 'issue_id');
    }
}
