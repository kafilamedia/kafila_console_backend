<?php

namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Controller;
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
            return $this->meetingNoteService->list($payload, $request->user());
        } catch (\Throwable $th) {
            return parent::errorResponse($th);
        }
    }
    public function store(Request $request)
    {
        try {
            $payload = parent::getWebRequest($request);
            return $this->meetingNoteService->storeMeetingNote($payload, $request->user());
        } catch (\Throwable $th) {
            return parent::errorResponse($th);
        }
    }
    public function view(Request $request, int $id)
    {
        try {
            return $this->meetingNoteService->view($id, $request->user());
        } catch (\Throwable $th) {
            return parent::errorResponse($th);
        }
    }

    public function createAction(Request $request)
    {

    }
}
