<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiscussionAction extends Model
{
    //
    protected $fillable = [
        'date',
        'description',
        'topic_id',
        'user_id'
    ];

    protected int $id;
    protected string $description;
    protected $date;
    protected int $note_id;
    protected int $user_id;
    protected DiscussionTopic $discussion_topic;
    
    protected User $user;
    
    public function user()
    {
        return $this->hasOne(User::class, 'user_id');
    }
    public function discussion_topic()
    {
        return $this->hasOne(DiscussionTopic::class, 'topic_id');
    }
}
