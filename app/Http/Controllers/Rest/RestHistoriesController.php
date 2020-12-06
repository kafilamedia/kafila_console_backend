<?php

namespace App\Http\Controllers\Rest;

use App\Dto\WebRequest;
use App\Dto\WebResponse;
use App\Services\AccountService;
use App\Services\MasterDataService;
use App\Services\RecordHistoriesService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Throwable;

class RestHistoriesController extends BaseRestController
{
    private RecordHistoriesService $recordHistoriesService;

    public function __construct(RecordHistoriesService $recordHistoriesService)
    {
        $this->recordHistoriesService = $recordHistoriesService;
    }
    public function statistic(Request $request) : JsonResponse
    {
        try {
            $response = $this->recordHistoriesService->getDashboardStatisticData($request->user());
            return parent::jsonResponse($response);
        } catch (Throwable $th) {
            return parent::errorResponse($th);
        }
    }
 
}
