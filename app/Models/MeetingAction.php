<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MeetingAction extends Model
{
    //
    protected $fillable = [
        'date',
        'description',
        'note_id',
        'user_id'
    ];

    protected int $id;
    protected string $description;
    protected $date;
    protected int $note_id;
    protected int $user_id;
    protected MeetingNote $meeting_note;
    protected User $user;
    
    public function user()
    {
        return $this->hasOne(User::class, 'user_id');
    }
    public function meeting_note()
    {
        return $this->hasOne(MeetingNote::class, 'note_id');
    }
}
