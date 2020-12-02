<?php

namespace App\Models;

class Issue extends BaseModel
{
    //
    protected $fillable = [
        'date', 'content', 'place', 'email', 'issuer', 'issue_input', 'departement_id'
    ];

    protected int $id;
    protected $date;
    protected string $content;
    protected string $email;
    protected string $issuer;
    protected string $issue_input;
    protected string $place;
    protected int $departement_id;
    protected Departement $departement;

    //not column
    protected ?FollowedUpIssue $follow_up = null;
    protected ?bool $is_closed;

    public function __construct()
    {
        parent::addFilterable('id', 'departement');
        parent::addFilterableAlias('departement', 'departements.name');
        //
    }

    public function department()
    {
        return $this->hasOne(Departement::class, 'departement_id');
    }
}
