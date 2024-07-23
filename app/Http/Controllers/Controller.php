<?php

namespace App\Http\Controllers;

use App\Exceptions\ExcelValidationException;
use BenSampo\Enum\Exceptions\InvalidEnumMemberException;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\RelationNotFoundException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * successResponse
     *
     * @param  mixed $data
     * @param  int $code
     * @return JsonResponse
     */
    public function successResponse($data, int $code = 200): JsonResponse
    {
        if ($data instanceof LengthAwarePaginator) {
            return response()->json([
                'success' => true,
                'message' => 'Request successfully processed',
                'data' => [
                    'items' => $data->items(),
                    'pagination' => [
                        'total' => $data->total(),
                        'per_page' => $data->perPage(),
                        'current_page' => $data->currentPage(),
                        'last_page' => $data->lastPage(),
                        'from' => $data->firstItem(),
                        'to' => $data->lastItem(),
                        'links' => [
                            'prev' => $data->previousPageUrl(),
                            'next' => $data->nextPageUrl(),
                        ],
                        'path' => $data->path(),
                    ],
                ],
            ])->setStatusCode($code);
        } else {
            return response()->json([
                'success' => true,
                'message' => 'Request successfully processed',
                'data' => $data,
            ])->setStatusCode($code);;
        }
    }

    public function errorResponse(string $message, Exception $data = null): JsonResponse
    {
        if(str_contains($message, 'Call to undefined relationship')) {
            $data = new RelationNotFoundException($message);
        }

        $response = [
            'success' => false,
            'message' => $message,
        ];

        $data = $data ?? new Exception($message);

        switch ($data) {
            case $data instanceof ModelNotFoundException:
                $code = Response::HTTP_NOT_FOUND;
                break;
            case $data instanceof ValidationException:
                $code = Response::HTTP_BAD_REQUEST;
                break;
            case $data instanceof UnauthorizedException:
                $code = Response::HTTP_UNAUTHORIZED;
                break;
            case $data instanceof BadRequestHttpException:
                $code = Response::HTTP_BAD_REQUEST;
                break;
            case $data instanceof InvalidEnumMemberException:
                $code = Response::HTTP_NOT_FOUND;
                break;
            case $data instanceof RelationNotFoundException:
                $code = Response::HTTP_BAD_REQUEST;
                break;
            default:
                $code = Response::HTTP_INTERNAL_SERVER_ERROR;
                break;
        }

        if ($data instanceof ValidationException) {
            $response['errors'] = $data->errors();
        }

        if (env('APP_DEBUG') && $data) {
            $response['data'] = [
                'message' => $data->getMessage(),
                'file' => $data->getFile(),
                'line' => $data->getLine(),
                'trace' => $data->getTrace(),
            ];
        }

        return response()->json($response)->setStatusCode($code);
    }
}
