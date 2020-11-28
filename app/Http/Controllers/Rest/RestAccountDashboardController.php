<?php

namespace App\Http\Controllers\Rest;

use App\Dto\WebRequest;
use App\Dto\WebResponse;
use App\Services\AccountService;
use App\Services\StakeHolderManagementService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Throwable;

class RestAccountDashboardController extends BaseRestController
{
    private AccountService $account_service;
    private StakeHolderManagementService $stakeHolderManagementService;

    public function __construct(AccountService $account_service, StakeHolderManagementService $stakeHolderManagementService)
    {
        $this->account_service = $account_service;
        $this->stakeHolderManagementService = $stakeHolderManagementService;
    }
     
    public function getUser(Request $request) : JsonResponse
    {
        try {
            $response = new WebResponse();
            $userList = $this->stakeHolderManagementService->getUserList(new WebRequest(), $request->user()->id);
            $response->user = ($userList[0]);
            $token = $request->header('Authorization');
            $token = explode(" ", $token)[1];
            return parent::jsonResponse($response, ['api_token'=>$token]);
        } catch (Throwable $th) {
            return parent::errorResponse($th);
        }
    }
 
}
