<?php

namespace App\Http\Controllers\Rest;

use App\Services\MasterDataService;
use Illuminate\Http\Request;
use Throwable;

class RestStakeHolderManagementController extends BaseRestController
{
    private MasterDataService $masterDataService;

    public function __construct(MasterDataService $masterDataService)
    {
        $this->masterDataService = $masterDataService;
    }

    public function list(Request $request)
    {
        try {
            $payload = parent::getWebRequest($request);
            $response = $this->masterDataService->list($payload);

            return parent::jsonResponse($response);
        } catch (Throwable $th) {
            return parent::errorResponse($th);
        }
    }
    public function store(Request $request)
    {
        try {
            $payload = parent::getWebRequest($request);
            $response = $this->masterDataService->store($payload, $request);

            return parent::jsonResponse($response);
        } catch (Throwable $th) {
            return parent::errorResponse($th);
        }
    }
    public function view(Request $request, int $id)
    {
        try {
            $payload = parent::getWebRequest($request);
            $response = $this->masterDataService->view($payload, $id);

            return parent::jsonResponse($response);
        } catch (Throwable $th) {
            return parent::errorResponse($th);
        }
    }
    public function delete(Request $request, int $id)
    {
        try {
            $payload = parent::getWebRequest($request);
            $response = $this->masterDataService->delete($payload, $id);

            return parent::jsonResponse($response);
        } catch (Throwable $th) {
            return parent::errorResponse($th);
        }
    }
}
