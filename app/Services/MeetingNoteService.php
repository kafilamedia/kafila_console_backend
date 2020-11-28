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

        if (!$user->hasRole('admin')) {
            $filter->fieldsFilter['departements.id'] = $user->departement_id;
        }
       
        $result = $this->stakeHolderManagementService->getMeetingNoteList($filter);
        $response = new WebResponse();
        $response->meeting_note = $result;
        return $response;
    }
    public function view($id, User $user) : WebResponse
    {
        $filter = new Filter();
        if (!$user->hasRole('admin')) {
            $filter->fieldsFilter['departements.id'] = $user->departement_id;
        }
       
        $result = $this->stakeHolderManagementService->getMeetingNoteList($filter, $id);
        $response = new WebResponse();
        $response->meeting_note = $result;
        return $response;
    }
}