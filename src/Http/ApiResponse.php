<?php

namespace App\Http;

use Symfony\Component\HttpFoundation\JsonResponse;

class ApiResponse extends JsonResponse {

    public const STATUS_SUCCESS = 'success';

    public const STATUS_ERROR = 'error';

    /**
     * @param array $data
     * @param int $http_status
     * @param array $headers
     * @return JsonResponse
     */
    public static function success( $data = [], int $http_status = 200, array $headers = [] ) {
        return self::createJsonResponse( self::STATUS_SUCCESS, $data, $http_status, $headers );
    }

    /**
     * @param $data
     * @param int $http_status
     * @param array $headers
     * @return JsonResponse
     */
    public static function error( $data, int $http_status = 400, array $headers = [] ) {
        return self::createJsonResponse( self::STATUS_ERROR, $data, $http_status, $headers );
    }

    /**
     * @param string $response_status
     * @param mixed|null $data
     * @param int $http_status
     * @param array $headers
     * @return JsonResponse
     */
    private static function createJsonResponse( string $response_status, $data = NULL, int $http_status = 200, array $headers = [] ) {
        $result = [
            'status' => $response_status,
        ];
        if ( $response_status === self::STATUS_ERROR ) {
            $result['error'] = $data;
        } else {
            $result['data'] = $data;
        }
        return JsonResponse::create( $result, $http_status, $headers );
    }
}