<?php
namespace App\Helpers;

class ApiResponse
{
    public static function success($arData = [])
    {
        $arData['status'] = 'success';
        return response()->json($arData);
    }

    public static function error($sMessage = 'error', $nCode = 400)
    {
        return response()->json([
            'error_message' => $sMessage,
            'code' => $nCode,
            'status' => 'error'
        ], $nCode);
    }

    public static function errorNotFound($sMessage = 'Not found'){
        return self::error($sMessage, 404);
    }

    public static function errorBadRequest($sMessage = 'Bad Request'){
        return self::error($sMessage, 400);
    }
}
