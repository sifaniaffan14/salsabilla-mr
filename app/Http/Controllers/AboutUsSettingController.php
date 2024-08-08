<?php

namespace App\Http\Controllers;

use App\Http\Requests\AboutUsSetting\CreateAboutUsSettingRequest;
use App\Http\Requests\AboutUsSetting\UpdateAboutUsSettingRequest;
use App\Repositories\AboutUsSettingRepository;
use App\Services\AboutUsService;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Http\Response;


class AboutUsSettingController extends Controller
{
    protected $repository;
    protected $service;

    public function __construct(
        AboutUsSettingRepository $repository,
        AboutUsService $service
    ) {
        $this->repository = $repository;
        $this->service = $service;
    }

    public function index(){
        $limit = request('limit', 10);
        try {
            $aboutUs = $this->repository->paginate($limit);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e);  
        }
        return $this->successResponse($aboutUs);
    }

    public function show(int $id)
    {
        try {
            $aboutUs = $this->repository->fetchAboutUsById($id);
        } catch(Exception $e){
            return $this->errorResponse($e->getMessage(), $e);
        }

        return $this->successResponse($aboutUs);
    }

    public function store(CreateAboutUsSettingRequest $request)
    {
        try {
            $aboutUs = $this->service->storeAboutUs($request->all());
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->successResponse($aboutUs, Response::HTTP_CREATED);
    }

    public function update(UpdateAboutUsSettingRequest $request, int $id)
    {
        try {
            $data = $request->all();
            $aboutUs = $this->service->updateAboutUs($data, empty($data['AboutUsVisiImage']) ? null : $data['AboutUsVisiImage'], empty($data['AboutUsMisiImage']) ? null : $data['AboutUsMisiImage'], $id);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->successResponse($aboutUs);
    }

    public function destroy(int $id)
    {
        try {
            $this->repository->deleteAboutUs($id);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e);
        }
        return $this->successResponse("Abous Us with id {$id} deleted");
    }
}
