<?php

namespace App\Http\Controllers\Rest;

use App\Dto\WebRequest;
use App\Dto\WebResponse;
use App\Http\Controllers\Controller;
use App\Services\AccountService;
use App\Utils\ObjectUtil;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class RestAccountController extends BaseRestController
{
    private AccountService $account_service;

    public function __construct(AccountService $account_service)
    {
        $this->account_service = $account_service;
    }
    //
    public function login(Request $request) : JsonResponse
    {
        $payload = parent::getWebRequest($request);
        try {
            $response = $this->account_service->loginAttemp($payload);
            $api_token = $response->user->api_token;
            $response->user = null;
            return parent::jsonResponse($response, ['api_token'=>$api_token]);
        } catch (Throwable $th) {
            return parent::errorResponse($th);
        }
    }
    public function register(Request $request)
    {
        try {
            $payload = parent::getWebRequest($request);
            $response = $this->account_service->register($payload);
           
            return parent::jsonResponse($response);
        } catch (Throwable $th) {
            
            return parent::errorResponse($th);
        }
    }
}
