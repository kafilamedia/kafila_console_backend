<?php

namespace App\Models;

class MeetingNote extends BaseModel
{
    //
    protected $fillable = [
        'date','place',
        // 'person_in_charge',
        'user_id','departement_id',
    ];

    protected int $id;
    protected $date;
    protected string $place;
    protected int $user_id;
    protected int $departement_id;

    protected User $user;
    protected Departement $departement;
    protected $discussion_topics = [];

    //not column
    protected ?MeetingAction $action = null;
    protected ?bool $is_closed;

    public function __construct()
    {
        parent::addFilterable('id', 'departement', 'user');
        parent::addFilterableAlias('departement', 'departements.name');
        parent::addFilterableAlias('user', 'users.name');
    }

    // public function user()
    // {
    //     return $this->hasOne(User::class, 'user_id');
    // }
    // public function department()
    // {
    //     return $this->hasOne(Departement::class, 'departement_id');
    // }
}
