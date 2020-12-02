<?php

namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Controller;
use App\Services\IssuesService;
use Illuminate\Http\Request;

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

    public function storeAction(Request $request)
    {
        // try {
        //     $payload = parent::getWebRequest($request);
        //     $response = $this->issueService->createAction($payload, $request->user());
        //     return parent::jsonResponse($response);
        // } catch (\Throwable $th) {
        //     return parent::errorResponse($th);
        // }
    }

    public function resetAction(Request $request)
    {
        // try {
        //     $payload = parent::getWebRequest($request);
        //     $response = $this->issueService->resetAction($payload, $request->user());
        //     return parent::jsonResponse($response);
        // } catch (\Throwable $th) {
        //     return parent::errorResponse($th);
        // }
    }
}
