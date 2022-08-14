<?php

namespace App\Helpers;

use Exception;

/**
 * Format response.
 */
class ResponseFormatter
{
    /**
     * API Response
     *
     * @var array
     */
    protected static $response = [
        'code' => 200,
        'error' => false,
        'message' => null,
        'data' => null,
    ];

    /**
     * Give success response.
     */
    public static function success($data = null, $message = null)
    {
        self::$response['message'] = $message;
        self::$response['data'] = $data;

        return response()->json(self::$response, self::$response['code']);
    }

    /**
     * Give error response.
     */
    public static function error($data = null, $message = null, $code = 400)
    {
        self::$response['error'] = true;
        self::$response['code'] = $code;
        self::$response['message'] = $message;
        self::$response['data'] = $data;

        return response()->json(self::$response, self::$response['code']);
    }

    public static function errorAuth(Exception $error){
        self::error([
            'message' => 'Something went wrong',
            'error' => $error,
        ],'Authentication Failed', 500);
    }
}
