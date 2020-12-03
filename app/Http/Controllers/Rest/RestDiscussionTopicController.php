<?php

namespace App\Http\Controllers\Rest;

use App\Services\DiscussionTopicService;
use Illuminate\Http\Request;

class RestDiscussionTopicController extends BaseRestController
{
    private DiscussionTopicService $discussionTopicService;

    public function __construct(DiscussionTopicService $discussionTopicService)
    {
        $this->discussionTopicService = $discussionTopicService;
    }
    public function list(Request $request)
    {
        try {
            $payload = parent::getWebRequest($request);
            $response = $this->discussionTopicService->list($payload, $request->user());
            return parent::jsonResponse($response);
        } catch (\Throwable $th) {
            return parent::errorResponse($th);
        }
    }
    public function store(Request $request)
    {
        try {
            $payload = parent::getWebRequest($request);
            $response = $this->discussionTopicService->storeDiscussionTopic($payload, $request->user());
            return parent::jsonResponse($response);
        } catch (\Throwable $th) {
            return parent::errorResponse($th);
        }
    }
    public function view(Request $request, $id)
    {
        try {
            $response = $this->discussionTopicService->view($id, $request->user());
            return parent::jsonResponse($response);
        } catch (\Throwable $th) {
            return parent::errorResponse($th);
        }
    }

    public function storeAction(Request $request)
    {
        try {
            $payload = parent::getWebRequest($request);
            $response = $this->discussionTopicService->createAction($payload, $request->user());
            return parent::jsonResponse($response);
        } catch (\Throwable $th) {
            return parent::errorResponse($th);
        }
    }

    public function resetAction(Request $request)
    {
        try {
            $payload = parent::getWebRequest($request);
            $response = $this->discussionTopicService->resetAction($payload, $request->user());
            return parent::jsonResponse($response);
        } catch (\Throwable $th) {
            return parent::errorResponse($th);
        }
    }
}
