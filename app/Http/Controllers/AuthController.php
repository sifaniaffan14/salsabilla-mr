<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;


class AuthController extends Controller
{
    protected $service;

    public function __construct(
        AuthService $service
    ) {
        $this->service = $service;
    }

    public function login(Request $request)
    {
        try {
            # do authentication
            $authentication = $this->service->login($request);

            # return response
            return $this->successResponse($authentication);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), null, ResponseAlias::HTTP_UNAUTHORIZED);
        }
    }

    public function generateToken(Request $request)
    {
        try {
            # generate token
            $token = $request->bearerToken();
            $token_detail = $this->service->updateToken($token);

            # return response
            return $this->successResponse($token_detail);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), null, ResponseAlias::HTTP_UNAUTHORIZED);
        }
    }

    public function logout(Request $request)
    {
        try {
            # do logout
            $authentication = $this->service->logout($request);

            # return response
            return $this->successResponse($authentication);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e);
        }
    }
}
