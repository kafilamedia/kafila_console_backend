<?php
namespace App\Services;

use App\Dto\Filter;
use App\Dto\WebRequest;
use App\Dto\WebResponse;
use App\Models\DiscussionAction;
use App\Models\MeetingAction;
use App\Models\DiscussionTopic;
use App\Models\User;
use Exception;

class DiscussionTopicService
{

    private MasterDataService $masterDataService;

    public function __construct(MasterDataService $masterDataService)
    {
        $this->masterDataService = $masterDataService;
    }

    public function storeDiscussionTopic(WebRequest $webRequest, User $user) : WebResponse
    {
        $result = $this->masterDataService->storeDiscussionTopic($webRequest->discussion_topic, $user);
        $response = new WebResponse();
        $response->discussion_topic = $result;
        return $response;
    }

    public function list(WebRequest $webRequest, User $user) : WebResponse
    {
        $filter = is_null($webRequest->filter)? new Filter(): $webRequest->filter;
        $result = $this->masterDataService->getDiscussionTopicList($filter, $user);
        $records = $result['list'];

        $topic_ids = MeetingNoteService::pluckIdAsArray($records);
        $discussion_actions = DiscussionAction::whereIn('topic_id', $topic_ids)->get();
        
        //check if closed
        foreach ($records as $record) {
            try {
                $action = $discussion_actions->where('topic_id', $record->id)->first();
                if (!is_null($action)) {
                    $record->is_closed = true;
                    $record->action = $action;
                } else {
                    $record->is_closed = false;
                }
            } catch (\Throwable $th) {
                // throw $th;
                $record->is_closed = false;
            }
        }

        $response = new WebResponse();
        $response->result_list = $records;
        $response->count = $result['count'];
        $response->filter = MasterDataService::adjustFilterKey($filter);
        return $response;
    }
    public function view($id, User $user) : WebResponse
    {
        $filter = new Filter();
        $result = $this->masterDataService->getDiscussionTopicList($filter, $user, $id);
        $response = new WebResponse();
        $discussion_topic = $result['list'][0];
        try {
            $action = DiscussionAction::where('topic_id', $discussion_topic->id)->first();
            if (!is_null($action)) {
                $discussion_topic->is_closed = true;
                $discussion_topic->action = $action;
            } else {
                $discussion_topic->is_closed = false;
            }
        } catch (\Throwable $th) {
            // throw $th;
            $discussion_topic->is_closed = false;
        }
        $response->discussion_topic = $discussion_topic;
        return $response;
    }

    public function createAction(WebRequest $request, User $user) : WebResponse
    {
        $response = new WebResponse();
        $actionModel = $request->discussion_action;
        $discussion_topic_id = $actionModel->topic_id;
        $discussion_topic = DiscussionTopic::find($discussion_topic_id);
        if (is_null($discussion_topic)) {
            throw new Exception("Corresponding discussion topics invalid: ".$discussion_topic_id);
        }
        if (!$user->isAdmin()) {
            if ($discussion_topic->departement_id != $user->departement_id) {
                throw new Exception("action not allowed");
            }
        }
        $newRecord = new DiscussionAction();
        //override existing
        $existing = DiscussionAction::where('topic_id', $discussion_topic_id)->first();
        if (!is_null($existing)) {
            $newRecord = $existing;
        }
        $newRecord->topic_id = $discussion_topic_id;
        $newRecord->description = $actionModel->description;
        $newRecord->date = $actionModel->date;
        $newRecord->user_id = $user->id;
        $newRecord->save();

        $response->discussion_action = $newRecord;
        return $response;
    }

    public function resetAction(WebRequest $request, User $user) : WebResponse
    {
        $response = new WebResponse();
        $actionModel = $request->discussion_action;
        $discussion_topic_id = $actionModel->topic_id;
        $discussion_topic = DiscussionTopic::find($discussion_topic_id);
        if (is_null($discussion_topic)) {
            throw new Exception("Corresponding meeting note invalid");
        }
        if (!$user->isAdmin()) {
            if ($discussion_topic->departement_id != $user->departement_id) {
                throw new Exception("action not allowed");
            }
        }
        //override existing
        $existing = DiscussionAction::where('topic_id', $discussion_topic_id)->first();
        if (!is_null($existing)) {
            $existing->delete();
        }
        return $response;
    }
}