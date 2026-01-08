<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{

    /**
     * @param array $result
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendResponse($result = [], $message = '')
    {
        $response = [
            'success' => true,
            'data'    => $result,
        ];
        if ($message) $response['message'] = $message;

        return response()->json($response, 200);
    }


    /**
     * @param $error
     * @param array $errorMessages
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendError($error, $code = 500 , $errorMessages = [])
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }
}
