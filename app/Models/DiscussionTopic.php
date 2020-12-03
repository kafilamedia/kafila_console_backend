<?php

namespace App\Models;

class DiscussionTopic extends BaseModel
{
    //
    protected $fillable = [
        'date','content','decision',
        'deadline_date',
        'person_in_charge',
        'user_id','departement_id',
    ];

    protected int $id;
    protected $date;
    protected string $content;
    protected string $decision;
    protected $deadline_date;
    protected int $user_id;
    protected int $departement_id;
    protected string $person_in_charge;

    protected User $user;
    protected Departement $departement;

    //not column
    protected ?DiscussionAction $action = null;
    protected ?bool $is_closed;

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
}