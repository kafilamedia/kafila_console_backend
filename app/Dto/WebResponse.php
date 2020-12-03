<?php
namespace App\Dto;

use App\Models\MeetingAction;
use App\Models\Departement;
use App\Models\DiscussionAction;
use App\Models\DiscussionTopic;
use App\Models\FollowedUpIssue;
use App\Models\Issue;
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

    public Departement $departement;
    
    public MeetingAction $action;
    public MeetingNote $meeting_note;

    public FollowedUpIssue $followed_up_issue;
    public Issue $issue;

    public DiscussionTopic $discussion_topic;
    public DiscussionAction $discussion_action;
    
    public array $result_list;
    public int $count = 0;

    public ?Filter $filter = null;

    public function __construct()
    {
        $this->date = now();
    }
}