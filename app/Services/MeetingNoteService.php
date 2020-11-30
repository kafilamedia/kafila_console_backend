<?php
namespace App\Services;

use App\Dto\Filter;
use App\Dto\WebRequest;
use App\Dto\WebResponse;
use App\Models\Action;
use App\Models\MeetingNote;
use App\Models\User;
use Exception;

class MeetingNoteService
{

    private StakeHolderManagementService $stakeHolderManagementService;

    public function __construct(StakeHolderManagementService $stakeHolderManagementService)
    {
        $this->stakeHolderManagementService = $stakeHolderManagementService;
    }

    public function storeMeetingNote(WebRequest $webRequest, User $user) : WebResponse
    {
        $result = $this->stakeHolderManagementService->storeMeetingNote($webRequest->meeting_note, $user);
        $response = new WebResponse();
        $response->meeting_note = $result;
        return $response;
    }

    public function list(WebRequest $webRequest, User $user) : WebResponse
    {
        $filter = is_null($webRequest->filter)? new Filter(): $webRequest->filter;
        $result = $this->stakeHolderManagementService->getMeetingNoteList($filter, $user);
        $response = new WebResponse();
        $response->result_list = $result['list'];
        $response->count = $result['count'];
        $response->filter = StakeHolderManagementService::adjustFilterKey($filter);
        return $response;
    }
    public function view($id, User $user) : WebResponse
    {
        $filter = new Filter();
        $result = $this->stakeHolderManagementService->getMeetingNoteList($filter, $user, $id);
        $response = new WebResponse();
        $response->meeting_note = $result['list'][0];
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
        $newRecord = new Action();
        //override existing
        $existing = Action::where('note_id', $meeting_note_id)->first();
        if (!is_null($existing)) {
            $newRecord = $existing;
        }
        $newRecord->note_id = $meeting_note_id;
        $newRecord->description = $actionModel->description;
        $newRecord->date = $actionModel->date;
        $newRecord->save();
        return $response;
    }
}