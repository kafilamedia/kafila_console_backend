<?php

namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Controller;
use App\Services\StakeHolderManagementService;
use Illuminate\Http\Request;
use Throwable;

class RestStakeHolderManagementController extends BaseRestController
{
    private StakeHolderManagementService $stakeHolderManagementService;

    public function __construct(StakeHolderManagementService $stakeHolderManagementService)
    {
        $this->stakeHolderManagementService = $stakeHolderManagementService;
    }

    public function list(Request $request)
    {
        try {
            $payload = parent::getWebRequest($request);
            $response = $this->stakeHolderManagementService->list($payload);

            return parent::jsonResponse($response);
        } catch (Throwable $th) {
            return parent::errorResponse($th);
        }
    }
    public function store(Request $request)
    {
        try {
            $payload = parent::getWebRequest($request);
            $response = $this->stakeHolderManagementService->store($payload, $request);

            return parent::jsonResponse($response);
        } catch (Throwable $th) {
            return parent::errorResponse($th);
        }
    }
    public function view(Request $request, int $id)
    {
        try {
            $payload = parent::getWebRequest($request);
            $response = $this->stakeHolderManagementService->view($payload, $id);

            return parent::jsonResponse($response);
        } catch (Throwable $th) {
            return parent::errorResponse($th);
        }
    }
}
