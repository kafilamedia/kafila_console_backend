<?php
namespace App\Services;

use App\Dto\Filter;
use App\Dto\WebRequest;
use App\Dto\WebResponse;
use App\Models\User;

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
}