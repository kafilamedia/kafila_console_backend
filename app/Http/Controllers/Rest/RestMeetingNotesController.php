<?php

namespace App\Http\Controllers\Rest;

use App\Services\MeetingNoteService;
use Illuminate\Http\Request;

class RestMeetingNotesController extends BaseRestController
{
    private MeetingNoteService $meetingNoteService;

    public function __construct(MeetingNoteService $meetingNoteService)
    {
        $this->meetingNoteService = $meetingNoteService;
    }
    public function list(Request $request)
    {
        try {
            $payload = parent::getWebRequest($request);
            $response = $this->meetingNoteService->list($payload, $request->user());
            return parent::jsonResponse($response);
        } catch (\Throwable $th) {
            return parent::errorResponse($th);
        }
    }
    public function store(Request $request)
    {
        try {
            $payload = parent::getWebRequest($request);
            $response = $this->meetingNoteService->storeMeetingNote($payload, $request->user());
            return parent::jsonResponse($response);
        } catch (\Throwable $th) {
            return parent::errorResponse($th);
        }
    }
    public function view(Request $request, $id)
    {
        try {
            $response = $this->meetingNoteService->view($id, $request->user());
            return parent::jsonResponse($response);
        } catch (\Throwable $th) {
            return parent::errorResponse($th);
        }
    }

    public function storeAction(Request $request)
    {
        try {
            $payload = parent::getWebRequest($request);
            $response = $this->meetingNoteService->createAction($payload, $request->user());
            return parent::jsonResponse($response);
        } catch (\Throwable $th) {
            return parent::errorResponse($th);
        }
    }

    public function resetAction(Request $request)
    {
        try {
            $payload = parent::getWebRequest($request);
            $response = $this->meetingNoteService->resetAction($payload, $request->user());
            return parent::jsonResponse($response);
        } catch (\Throwable $th) {
            return parent::errorResponse($th);
        }
    }
}
