<?php

namespace App\Http\Controllers;

use App\Http\Requests\FooterContent\CreateFooterContentRequest;
use App\Http\Requests\FooterContent\UpdateFooterContentRequest;
use App\Repositories\FooterContentRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FooterContentController extends Controller
{
    protected $repository;

    public function __construct(
        FooterContentRepository $repository
    ) {
        $this->repository = $repository;
    }

    public function index(){
        $limit = request('limit', 10);
        try {
            $footerContents = $this->repository->paginate($limit);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e);  
        }
        return $this->successResponse($footerContents);
    }

    public function show(int $id)
    {
        try {
            $footerContents = $this->repository->fetchFooterContentById($id);
        } catch(Exception $e){
            return $this->errorResponse($e->getMessage(), $e);
        }

        return $this->successResponse($footerContents);
    }

    public function store(CreateFooterContentRequest $request)
    {
        try {
            $footerContents = $this->repository->create($request->all());
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->successResponse($footerContents, Response::HTTP_CREATED);
    }

    public function update(UpdateFooterContentRequest $request, int $id)
    {
        try {
            $footerContents = $this->repository->update($request->all(), $id);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->successResponse($footerContents);
    }

    public function destroy(int $id)
    {
        try {
            $this->repository->deleteFooterContent($id);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e);
        }
        return $this->successResponse("Footer Content with id {$id} deleted");
    }
}
