<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
abstract class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    
    public function sendSuccess($message, $result, $statusCode = 200)
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data' => $result,
        ];
        return response()->json($response, $statusCode);
    }

     /**
     * Summary of sendError
     * @param mixed $message - error message
     * @param mixed $statusCode - error status code, default is 400
     * @param mixed $errors - error data array
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function sendError($message, $statusCode = 400, $errors = [])
    {
        $response = [
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ];

        return response()->json($response, $statusCode);
    }
    
    /**
     * Summary of getUserId
     * return user id from request header coming from TokenVerificationMiddleware
     */
    public function getUserId($request)
    {
        return $request->headers->get('id');
    }

    /**
     * Summary of getUserEmail
     * return user email from request header coming from TokenVerificationMiddleware
     */
    public function getUserEmail($request)
    {
        return $request->headers->get('id');
    }

}
