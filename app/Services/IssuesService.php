<?php
namespace App\Services;

use App\Dto\Filter;
use App\Dto\WebRequest;
use App\Dto\WebResponse;
use App\Models\FollowedUpIssue;
use App\Models\Issue;
use App\Models\MeetingAction;
use App\Models\MeetingNote;
use App\Models\User;
use Exception;

class IssuesService
{

    private MasterDataService $stakeHolderManagementService;

    public function __construct(MasterDataService $stakeHolderManagementService)
    {
        $this->stakeHolderManagementService = $stakeHolderManagementService;
    }

    public function storeIssue(WebRequest $webRequest, User $user) : WebResponse
    {
        $result = $this->stakeHolderManagementService->storeIssue($webRequest->issue, $user);
        $response = new WebResponse();
        $response->issue = $result;
        return $response;
    }

    public function list(WebRequest $webRequest, User $user) : WebResponse
    {
        $filter = is_null($webRequest->filter)? new Filter(): $webRequest->filter;
        $result = $this->stakeHolderManagementService->getIssueList($filter, $user);
        $records = $result['list'];
        $response = new WebResponse();
        $response->result_list = $records;
        $response->count = $result['count'];
        $response->filter = MasterDataService::adjustFilterKey($filter);
        return $response;
    }
    public function view($id, User $user) : WebResponse
    {
        $filter = new Filter();
        $result = $this->stakeHolderManagementService->getIssueList($filter, $user, $id);
        $response = new WebResponse();
        $issue = $result['list'][0];
        try {
            $action = FollowedUpIssue::where('issue_id', $issue->id)->first();
            if (!is_null($action)) {
                $issue->is_closed = true;
                $issue->follow_up = $action;
            } else {
                $issue->is_closed = false;
            }
        } catch (\Throwable $th) {
            // throw $th;
            $issue->is_closed = false;
        }
        $response->issue = $issue;
        return $response;
    }

    public function delete($id)
    {
        $model = Issue::find($id);
        $model->delete();
    }

    public function createAction(WebRequest $request, User $user) : WebResponse
    {
        $response = new WebResponse();
        $actionModel = $request->followed_up_issue;
        $issue_id = $actionModel->issue_id;
        $issue = Issue::find($issue_id);
        if (is_null($issue)) {
            throw new Exception("Corresponding issue invalid");
        }
        if (!$user->isAdmin()) {
            if ($issue->departement_id != $user->departement_id) {
                throw new Exception("action not allowed");
            }
        }
        $newRecord = new FollowedUpIssue();
        //override existing
        $existing = FollowedUpIssue::where('issue_id', $issue_id)->first();
        if (!is_null($existing)) {
            $newRecord = $existing;
        }
        $newRecord->issue_id = $issue_id;
        $newRecord->description = $actionModel->description;
        $newRecord->date = $actionModel->date;
        $newRecord->user_id = $user->id;
        $newRecord->save();

        $response->followed_up_issue = $newRecord;
        return $response;
    }

    public function resetAction(WebRequest $request, User $user) : WebResponse
    {
        $response = new WebResponse();
        $actionModel = $request->followed_up_issue;
        $issue_id = $actionModel->issue_id;
        $issue = Issue::find($issue_id);
        if (is_null($issue)) {
            throw new Exception("Corresponding issue invalid");
        }
        if (!$user->isAdmin()) {
            if ($issue->departement_id != $user->departement_id) {
                throw new Exception("action not allowed");
            }
        }
        //override existing
        $existing = FollowedUpIssue::where('issue_id', $issue_id)->first();
        if (!is_null($existing)) {
            $existing->delete();
        }
        return $response;
    }
}