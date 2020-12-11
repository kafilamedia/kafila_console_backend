<?php

namespace App\Models;

use App\Dto\FileInfo;

class Issue extends BaseModel
{
    //
    protected $fillable = [
        'date', 'content', 'place', 'email', 'issuer', 'issue_input', 'departement_id', 'attachment'
    ];

    protected int $id;
    protected $date;
    protected string $content;
    protected string $email;
    protected string $issuer;
    protected string $issue_input;
    protected string $place;
    protected string $attachment;
    protected int $departement_id;

    //not column
    
    protected Departement $departement;
    protected ?FollowedUpIssue $follow_up = null;
    protected ?bool $is_closed;
    protected $closed_date;
    protected ?FileInfo $attachment_info;

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
