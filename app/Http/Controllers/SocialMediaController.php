<?php

namespace App\Http\Controllers;

use App\Http\Requests\SocialMedia\CreateSocialMediaRequest;
use App\Http\Requests\SocialMedia\UpdateSocialMediaRequest;
use App\Repositories\SocialMediaRepository;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Http\Response;


class SocialMediaController extends Controller
{
    protected $repository;

    public function __construct(
        SocialMediaRepository $repository
    ) {
        $this->repository = $repository;
    }

    public function index(){
        $limit = request('limit', 10);
        try {
            $socialMedia = $this->repository->paginate($limit);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e);  
        }
        return $this->successResponse($socialMedia);
    }

    public function show(int $id)
    {
        try {
            $socialMedia = $this->repository->fetchSocialMediaById($id);
        } catch(Exception $e){
            return $this->errorResponse($e->getMessage(), $e);
        }

        return $this->successResponse($socialMedia);
    }

    public function store(CreateSocialMediaRequest $request)
    {
        try {
            $socialMedia = $this->repository->create($request->all());
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->successResponse($socialMedia, Response::HTTP_CREATED);
    }

    public function update(UpdateSocialMediaRequest $request, int $id)
    {
        try {
            $socialMedia = $this->repository->update($request->all(), $id);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->successResponse($socialMedia);
    }

    public function destroy(int $id)
    {
        try {
            $this->repository->deleteSocialMedia($id);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e);
        }
        return $this->successResponse("Social Media with id {$id} deleted");
    }
}
