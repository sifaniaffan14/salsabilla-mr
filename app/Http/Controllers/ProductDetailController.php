<?php

namespace App\Http\Controllers;

use App\Repositories\ProductDetailRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductDetailController extends Controller
{
    protected $repository;

    public function __construct(
        ProductDetailRepository $repository,
    ) {
        $this->repository = $repository;
    }

    public function index(){
        $limit = request('limit', 10);
        try {
            $productDetails = $this->repository->paginate($limit);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e);  
        }
        return $this->successResponse($productDetails);
    }

    public function show(int $id)
    {
        try {
            $productDetails = $this->repository->fetchProductDetailById($id);
        } catch(Exception $e){
            return $this->errorResponse($e->getMessage(), $e);
        }

        return $this->successResponse($productDetails);
    }

    public function store(Request $request)
    {
        try {
            $productDetails = $this->repository->create($request->all());
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->successResponse($productDetails, Response::HTTP_CREATED);
    }

    public function update(Request $request, int $id)
    {
        try {
            $productDetails = $this->repository->update($request->all(), $id);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->successResponse($productDetails);
    }

    public function destroy(int $id)
    {
        try {
            $this->repository->deleteProductDetail($id);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e);
        }
        return $this->successResponse("Product with id {$id} deleted");
    }
}
