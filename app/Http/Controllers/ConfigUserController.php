<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConfigUser\CreateUserRequest;
use App\Http\Requests\ConfigUser\UpdateUserRequest;
use App\Repositories\ConfigUserRepository;
use App\Services\UserService;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class ConfigUserController extends Controller
{
    protected $repository;
    protected $service;

    public function __construct(
        ConfigUserRepository $repository,
        UserService $service
    ) {
        $this->repository = $repository;
        $this->service = $service;
    }

    public function index(){
        try {
            $users = $this->repository->fetchAllUser();
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e);  
        }
        return $this->successResponse($users);
    }

    public function show(int $id)
    {
        try {
            $users = $this->repository->fetchUserById($id);
        } catch(Exception $e){
            return $this->errorResponse($e->getMessage(), $e);
        }

        return $this->successResponse($users);
    }

    public function store(CreateUserRequest $request)
    {
        try {
            $users = $this->service->storeUser($request->all());
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->successResponse($users, Response::HTTP_CREATED);
    }

    public function update(UpdateUserRequest $request, int $id)
    {
        try {
            $data = $request->all();
            $users = $this->service->updateUser($data, empty($data['UserImages']) ? null : $data['UserImages'],$id);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->successResponse($users);
    }

    public function destroy(int $id)
    {
        try {
            $this->repository->deleteUser($id);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e);
        }
        return $this->successResponse("User with id {$id} deleted");
    }
}
