<?php

namespace App\Http\Controllers\Rest;

use App\Services\MasterDataService;
use Illuminate\Http\Request;
use Throwable;

class RestStakeHolderManagementController extends BaseRestController
{
    private MasterDataService $stakeHolderManagementService;

    public function __construct(MasterDataService $stakeHolderManagementService)
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
    public function delete(Request $request, int $id)
    {
        try {
            $payload = parent::getWebRequest($request);
            $response = $this->stakeHolderManagementService->delete($payload, $id);

            return parent::jsonResponse($response);
        } catch (Throwable $th) {
            return parent::errorResponse($th);
        }
    }
}
