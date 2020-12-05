<?php
namespace App\Services;

use App\Dto\Filter;
use App\Dto\WebRequest;
use App\Dto\WebResponse;
use App\Models\DiscussionAction;
use App\Models\DiscussionTopic;
use App\Models\MeetingAction;
use App\Models\MeetingNote;
use App\Models\User;
use App\Utils\ObjectUtil;
use Exception;
use Illuminate\Support\Collection;

class MeetingNoteService
{

    private MasterDataService $stakeHolderManagementService;
    private DiscussionTopicService $discussionTopicService;

    public function __construct(MasterDataService $stakeHolderManagementService, DiscussionTopicService $discussionTopicService)
    {
        $this->stakeHolderManagementService = $stakeHolderManagementService;
        $this->discussionTopicService = $discussionTopicService;
    }

    public function storeMeetingNote(WebRequest $webRequest, User $user) : WebResponse
    {
        if (is_null($webRequest->meeting_note->discussion_topics) || sizeof($webRequest->meeting_note->discussion_topics) == 0){
            throw new Exception("Tema Pembahasan Harus ada!");
            # code...
        }
        $result = $this->stakeHolderManagementService->storeMeetingNote($webRequest->meeting_note, $user);
        $discussion_topics = $webRequest->meeting_note->discussion_topics;
        $saved_topics = [];
        foreach ($discussion_topics as $discussion_topic_array) {
            $discussion_topic = ObjectUtil::arraytoobj(new DiscussionTopic(), $discussion_topic_array);
            $discussion_topic->note_id = $result->id;
            $saved_topic = $this->stakeHolderManagementService->storeDiscussionTopic($discussion_topic, $user);
            array_push($saved_topics, $saved_topic);
        }

        $result->discussion_topics = $saved_topics;
        $response = new WebResponse();
        $response->meeting_note = $result;
        return $response;
    }

    public static function pluckIdAsArray(array $array_containing_id) : array
    {
        $collection = new Collection($array_containing_id);
        return $collection->pluck('id')->flatten()->toArray();
    }

    public function list(WebRequest $webRequest, User $user) : WebResponse
    {
        $filter = is_null($webRequest->filter)? new Filter(): $webRequest->filter;
        $result = $this->stakeHolderManagementService->getMeetingNoteList($filter, $user);
        $records = $result['list'];

        $note_ids = $this->pluckIdAsArray($records);
        
        $discussion_topics = DiscussionTopic::whereIn('note_id', $note_ids)->get();

        $topic_ids = $this->pluckIdAsArray($discussion_topics->toArray());
        $discussion_actions = DiscussionAction::whereIn('topic_id', $topic_ids)->get();
        foreach ($discussion_topics as $discussion_topic) {
            $action = $discussion_actions->where('topic_id', $discussion_topic->id)->first();
            $discussion_topic->action = $action;
            $discussion_topic->is_closed = is_null($action) == false;
        }
        
        //check if closed
        foreach ($records as $record) {
            try {
                $discussion_topics_filtered = $discussion_topics->where('note_id', $record->id);
                $record->discussion_topics = ObjectUtil::collectionToPlainArray($discussion_topics_filtered);
            } catch (\Throwable $th) {
                $record->discussion_topics = [];
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
        $result = $this->stakeHolderManagementService->getMeetingNoteList($filter, $user, $id);
        $response = new WebResponse();
        $meeting_note = $result['list'][0];
        
        $discussion_topics = DiscussionTopic::where('note_id', $meeting_note->id)->get();
        $topic_ids = $this->pluckIdAsArray($discussion_topics->toArray());
        $discussion_actions = DiscussionAction::whereIn('topic_id', $topic_ids)->get();
        foreach ($discussion_topics as $discussion_topic) {
            $action = $discussion_actions->where('topic_id', $discussion_topic->id)->first();
            $discussion_topic->action = $action;
            $discussion_topic->is_closed = is_null($action) == false;
        }
        $meeting_note->discussion_topics = $discussion_topics->toArray();
        $response->meeting_note = $meeting_note;
        return $response;
    }

    public function createAction(WebRequest $request, User $user) : WebResponse
    {
        $response = new WebResponse();
        $actionModel = $request->action;
        $meeting_note_id = $actionModel->note_id;
        $meeting_note = MeetingNote::find($meeting_note_id);
        if (is_null($meeting_note)) {
            throw new Exception("Corresponding meeting note invalid");
        }
        if (!$user->isAdmin()) {
            if ($meeting_note->departement_id != $user->departement_id) {
                throw new Exception("action not allowed");
            }
        }
        $newRecord = new MeetingAction();
        //override existing
        $existing = MeetingAction::where('note_id', $meeting_note_id)->first();
        if (!is_null($existing)) {
            $newRecord = $existing;
        }
        $newRecord->note_id = $meeting_note_id;
        $newRecord->description = $actionModel->description;
        $newRecord->date = $actionModel->date;
        $newRecord->user_id = $user->id;
        $newRecord->save();

        $response->action = $newRecord;
        return $response;
    }

    public function resetAction(WebRequest $request, User $user) : WebResponse
    {
        $response = new WebResponse();
        $actionModel = $request->action;
        $meeting_note_id = $actionModel->note_id;
        $meeting_note = MeetingNote::find($meeting_note_id);
        if (is_null($meeting_note)) {
            throw new Exception("Corresponding meeting note invalid");
        }
        if (!$user->isAdmin()) {
            if ($meeting_note->departement_id != $user->departement_id) {
                throw new Exception("action not allowed");
            }
        }
        //override existing
        $existing = MeetingAction::where('note_id', $meeting_note_id)->first();
        if (!is_null($existing)) {
            $existing->delete();
        }
        return $response;
    }
}