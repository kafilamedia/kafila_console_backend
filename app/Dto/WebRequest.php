<?php
namespace App\Dto;

use App\Models\Action;
use App\Models\Departement;
use App\Models\MeetingNote;
use App\Models\User;

class WebRequest
{
    public User $user;

    //crud stuff
    public ?User $userModel;
    public Action $action;
    public MeetingNote $meeting_note;
    public Departement $departement;

    public ?string $code = null;
    public ?Filter $filter = null;
    
}