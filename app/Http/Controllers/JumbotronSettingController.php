<?php

namespace App\Http\Controllers;

use App\Http\Requests\JumbotronSetting\CreateJumbotronSettingRequest;
use App\Http\Requests\JumbotronSetting\UpdateJumbotronSettingRequest;
use App\Repositories\JumbotronSettingRepository;
use App\Services\JumbotronSettingService;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class JumbotronSettingController extends Controller
{
    protected $repository;
    protected $service;

    public function __construct(
        JumbotronSettingRepository $repository,
        JumbotronSettingService $service
    ) {
        $this->repository = $repository;
        $this->service = $service;
    }

    public function index(){
        try {
            $jumbotronSetting = $this->repository->fetchAllJumbotron();
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
            $jumbotronSetting = $this->service->storeJumbotron($request->all());
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->successResponse($jumbotronSetting, Response::HTTP_CREATED);
    }

    public function update(UpdateJumbotronSettingRequest $request, int $id)
    {
        try {
            $data = $request->all();
            $jumbotronSetting = $this->service->updateJumbotron($data, empty($data['JumbotronImage']) ? null : $data['JumbotronImage'],$id);
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
