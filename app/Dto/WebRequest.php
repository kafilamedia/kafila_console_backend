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

class WebRequest
{
    public User $user;

    //crud stuff
    public ?User $userModel;

    public MeetingAction $action;
    public MeetingAction $meeting_action;
    public MeetingNote $meeting_note;

    public Departement $departement;
    
    public Issue $issue;
    public FollowedUpIssue $followed_up_issue;

    public DiscussionTopic $discussion_topic;
    public DiscussionAction $discussion_action;
    
    public ?string $code = null;
    public ?Filter $filter = null;
    
}