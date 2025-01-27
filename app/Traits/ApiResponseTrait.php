<?php

namespace App\Traits;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use stdClass;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

trait ApiResponseTrait
{
    /**
     * @param array|object $data
     * @param string $message
     * @return JsonResponse
     */
    public function successResponse(array|object $data = [], string $message = ""): JsonResponse
    {
        $response = !empty($data) ? $data : new stdClass();
        return response()->json($response, ResponseAlias::HTTP_OK);
    }

    /**
     * Response returned in case of error or exception
     */
    public function errorResponse(string $message = '', int $code = ResponseAlias::HTTP_UNPROCESSABLE_ENTITY): JsonResponse
    {
        $response = ["message" => $message];
        return response()->json($response, $code);
    }

    /**
     * Response returned in case of validation errors
     * @throws HttpResponseException
     */
    public function validationResponse($validator): void
    {
        $response = ["message" => $validator->errors()->first()];
        throw new HttpResponseException(response($response, ResponseAlias::HTTP_UNPROCESSABLE_ENTITY));
    }

    /**
     * Response returned in case of unauthorized or invalid token
     */
    public function unAuthorizedResponse(): JsonResponse
    {
        return $this->errorResponse('Unauthorized', ResponseAlias::HTTP_UNAUTHORIZED);
    }

    public function invalidToken(): JsonResponse
    {
        return $this->errorResponse('Invalid Token', ResponseAlias::HTTP_UNAUTHORIZED);
    }
}
