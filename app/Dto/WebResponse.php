<?php
namespace App\Dto;

use App\Models\Action;
use App\Models\Departement;
use App\Models\MeetingNote;
use App\Models\User;
use DateTimeZone;
use Illuminate\Support\Facades\Date;

class WebResponse
{
    public ?string $code = "00";
    public ?string $message = "success";
    public $date;
    public ?User $user;
    public Action $action;
    public MeetingNote $meeting_note;
    public Departement $departement;
    public array $result_list;

    public function __construct()
    {
        $this->date = now();
    }
}