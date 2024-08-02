<?php

namespace App\Http\Controllers;

use App\Http\Requests\JumbotronSetting\CreateJumbotronSettingRequest;
use App\Http\Requests\JumbotronSetting\UpdateJumbotronSettingRequest;
use App\Repositories\JumbotronSettingRepository;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class JumbotronSettingController extends Controller
{
    protected $repository;

    public function __construct(
        JumbotronSettingRepository $repository
    ) {
        $this->repository = $repository;
    }

    public function index(){
        $limit = request('limit', 10);
        try {
            $jumbotronSetting = $this->repository->paginate($limit);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e);  
        }
        return $this->successResponse($jumbotronSetting);
    }

    public function show(int $id)
    {
        try {
            $jumbotronSetting = $this->repository->fetchJumbotronSettingById($id);
        } catch(Exception $e){
            return $this->errorResponse($e->getMessage(), $e);
        }

        return $this->successResponse($jumbotronSetting);
    }

    public function store(CreateJumbotronSettingRequest $request)
    {
        try {
            $jumbotronSetting = $this->repository->create($request->all());
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->successResponse($jumbotronSetting, Response::HTTP_CREATED);
    }

    public function update(UpdateJumbotronSettingRequest $request, int $id)
    {
        try {
            $jumbotronSetting = $this->repository->update($request->all(), $id);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->successResponse($jumbotronSetting);
    }

    public function destroy(int $id)
    {
        try {
            $this->repository->deleteJumbotronSetting($id);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e);
        }
        return $this->successResponse("Jumbotron with id {$id} deleted");
    }
}
