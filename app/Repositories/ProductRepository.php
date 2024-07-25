<?php

namespace App\Repositories;

use App\Criteria\CustomCriteria;
use App\Models\Product;
use Prettus\Repository\Eloquent\BaseRepository;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class ProductRepositoryRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class ProductRepository extends BaseRepository 
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Product::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(CustomCriteria::class));
    }

    public function fetchProductById(int $id): Product
    {
        $product = $this->find($id);

        if (!$product) {
            throw new ModelNotFoundException("Product with id {$id} not found", Response::HTTP_NOT_FOUND);
        }

        return $product;
    }

    public function updateProduct(int $id, array $data): Product
    {
        $product = $this->fetchProductById($id);

        $product->update($data);

        return $product;
    }

    public function deleteProduct($id)
    {
        $product = $this->fetchProductById($id);

        // Delete data
        $product->delete();
    }

    
}
