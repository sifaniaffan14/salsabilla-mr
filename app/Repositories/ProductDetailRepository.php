<?php

namespace App\Repositories;

use App\Models\ProductDetail;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class ProductDetailRepositoryRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class ProductDetailRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return ProductDetail::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
    public function fetchProductDetailById(int $id): ProductDetail
    {
        $productDetail = $this->find($id);

        if (!$productDetail) {
            throw new ModelNotFoundException("Product Detail with id {$id} not found", Response::HTTP_NOT_FOUND);
        }

        return $productDetail;
    }

    public function updateProduct(int $id, array $data): ProductDetail
    {
        $productDetail = $this->fetchProductDetailById($id);

        $productDetail->update($data);

        return $productDetail;
    }

    public function deleteProductDetail($id)
    {
        $productDetail = $this->fetchProductDetailById($id);

        // Delete data
        $productDetail->delete();
    }

    public function bulkInsert(array $data)
    {
        $data = $this->model->insert($data);

        return $data;
    } 
}
