<?php

namespace App\Models;

class MeetingNote extends BaseModel
{
    //
    protected $fillable = [
        'date','content','decision',
        'deadline_date','place',
        'person_in_charge',
        'user_id','departement_id',
    ];

    protected int $id;
    protected $date;
    protected string $content;
    protected string $decision;
    protected string $place;
    protected $deadline_date;
    protected int $user_id;
    protected int $departement_id;
    protected string $person_in_charge;

    protected User $user;
    protected Departement $departement;

    public function __construct()
    {
        parent::addFilterable('id');
        parent::addFilterableAlias('id');
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
