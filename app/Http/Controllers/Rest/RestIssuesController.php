<?php

namespace App\Http\Controllers\Rest;

use App\Dto\WebResponse;
use App\Http\Controllers\Controller;
use App\Models\Departement;
use App\Services\IssuesService;
use Exception;
use Illuminate\Http\Request;
use Throwable;

class RestIssuesController extends BaseRestController
{
    private IssuesService $issueService;

    public function __construct(IssuesService $issueService)
    {
        $this->issueService = $issueService;
    }
    public function list(Request $request)
    {
        try {
            $payload = parent::getWebRequest($request);
            $response = $this->issueService->list($payload, $request->user());
            return parent::jsonResponse($response);
        } catch (\Throwable $th) {
            return parent::errorResponse($th);
        }
    }
    public function store(Request $request)
    {
        try {
            $payload = parent::getWebRequest($request);
            $response = $this->issueService->storeIssue($payload, $request->user());
            return parent::jsonResponse($response);
        } catch (\Throwable $th) {
            return parent::errorResponse($th);
        }
    }
    public function view(Request $request, $id)
    {
        try {
            $response = $this->issueService->view($id, $request->user());
            return parent::jsonResponse($response);
        } catch (\Throwable $th) {
            return parent::errorResponse($th);
        }
    }
    public function delete(Request $request, int $id)
    {
        try {
            if ($request->user()->isAdmin() == false) {
                throw new Exception("Unauthorized access");
            }
            // $payload = parent::getWebRequest($request);
            $this->issueService->delete($id);
            return parent::jsonResponse(new WebResponse());
        } catch (Throwable $th) {
            return parent::errorResponse($th);
        }
    }

    public function storeAction(Request $request)
    {
        try {
            $payload = parent::getWebRequest($request);
            $response = $this->issueService->createAction($payload, $request->user());
            return parent::jsonResponse($response);
        } catch (\Throwable $th) {
            return parent::errorResponse($th);
        }
    }

    public function resetAction(Request $request)
    {
        try {
            $payload = parent::getWebRequest($request);
            $response = $this->issueService->resetAction($payload, $request->user());
            return parent::jsonResponse($response);
        } catch (\Throwable $th) {
            return parent::errorResponse($th);
        }
    }



    // =================== PUBLIC ================

    public function departementList(Request $request)
    {
        try {
            $response = new WebResponse();
            $response->result_list = Departement::all()->toArray();
            return parent::jsonResponse($response);
        } catch (\Throwable $th) {
            return parent::errorResponse($th);
        }
    }
}
