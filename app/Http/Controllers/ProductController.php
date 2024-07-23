<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\CreateProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Repositories\ProductRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    protected $repository;

    public function __construct(
        ProductRepository $repository
    ) {
        $this->repository = $repository;
    }

    public function index(){
        $limit = request('limit', 10);
        try {
            $products = $this->repository->paginate($limit);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e);  
        }
        return $this->successResponse($products);
    }

    public function show(int $id)
    {
        try {
            $products = $this->repository->fetchProductById($id);
        } catch(Exception $e){
            return $this->errorResponse($e->getMessage(), $e);
        }

        return $this->successResponse($products);
    }

    public function store(CreateProductRequest $request)
    {
        try {
            $products = $this->repository->create($request->all());
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->successResponse($products, Response::HTTP_CREATED);
    }

    public function update(UpdateProductRequest $request, int $id)
    {
        try {
            $products = $this->repository->update($request->all(), $id);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->successResponse($products);
    }

    public function destroy(int $id)
    {
        try {
            $this->repository->deleteProduct($id);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e);
        }
        return $this->successResponse("Product with id {$id} deleted");
    }
}
