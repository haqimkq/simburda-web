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
        'error' => false,
        'message' => null,
    ];

    /**
     * Give success response.
     */
    public static function success($dataKey = null, $data = null, $message = null)
    {
        self::$response['message'] = $message;
        if($dataKey!=NULL){
            self::$response[$dataKey] = $data;
        }
        return response()->json(self::$response);
    }

    /**
     * Give error response.
     */
    public static function error($message = null)
    {
        self::$response['error'] = true;
        self::$response['message'] = $message;
        
        return response()->json(self::$response);
    }
}
