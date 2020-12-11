<?php

namespace App\Models;

use App\Dto\FileInfo;

class DiscussionTopic extends BaseModel
{
    //
    protected $fillable = [
        'date','content','decision',
        'deadline_date',
        'person_in_charge',
        'user_id','departement_id',
        'note_id','attachment'
    ];

    protected int $id;
    protected $date;
    protected string $content;
    protected string $decision;
    protected $deadline_date;
    protected int $user_id;
    protected int $note_id;
    protected int $departement_id;
    protected string $person_in_charge;
    protected string $attachment;

  

    //not column
    protected User $user;
    protected Departement $departement;
    protected MeetingNote $meeting_note;
    protected ?FileInfo $attachment_info;
    protected ?DiscussionAction $action = null;
    protected ?bool $is_closed;
    protected $closed_date;

    public function __construct()
    {
        parent::addFilterable('id', 'departement', 'user');
        parent::addFilterableAlias('departement', 'departements.name');
        parent::addFilterableAlias('user', 'users.name');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'user_id');
    }
    public function department()
    {
        return $this->hasOne(Departement::class, 'departement_id');
    }
    public function meeting_note()
    {
        return $this->hasOne(MeetingNote::class, 'note_id');
    }
}
