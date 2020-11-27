<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MeetingNote extends Model
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

    protected User $user;
    protected Departement $departement;

    public function user()
    {
        return $this->hasOne(User::class, 'user_id');
    }
    public function department()
    {
        return $this->hasOne(Departement::class, 'departement_id');
    }
}
