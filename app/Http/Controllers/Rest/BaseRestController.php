<?php


namespace App\Http\Controllers\Rest;

use App\Dto\WebRequest;
use App\Dto\WebResponse; 
use App\Http\Controllers\Controller;
use App\Utils\ObjectUtil;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class BaseRestController extends Controller {

    public function __construct()
    {
        // $this->middleware('api');
    }
 

    protected function getWebRequest(Request $request) : WebRequest
    {
        return   ObjectUtil::arraytoobj(new WebRequest(), $request->json());
    }

    protected function webResponse($code = null, $message = null)
    {
        $response = new WebResponse();

        if (null != $code) {
            $response->code = $code;
        }
        if (null!=$message) {
            $response->message = $message;
        }
        $statusCode = 200;
        if ($code!="00") {
            $statusCode = 500;
        }
        return response(json_encode(($response)), $statusCode);
    }
 
    
    protected function jsonResponse(WebResponse $response, array $header = null)
    {
        if (null == $header) {
            return response()->json(($response));
        }
        
        return response()->json(($response), 200, $header);
    }

    protected function errorResponse(Throwable $th)
    {
        // throw $th;
        $response = new WebResponse();
        $response->message = $th->getMessage();
        $response->code = "-1";
        return response()->json(($response), 500);
    }
}
