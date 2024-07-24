<?php

namespace App\Services;

use App\Enums\TerritoryTypeEnum;
use App\Helpers\StorageHelper;
use App\Repositories\CountryRepository;
use App\Repositories\ProductDetailRepository;
use App\Repositories\ProductRepository;
use App\Repositories\TerritoryRepository;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductService extends BaseService
{

    protected $productRepository;
    protected $productDetailRepository;

    public function __construct(
        ProductRepository $productRepository,
        ProductDetailRepository $productDetailRepository
    ) {
        $this->productRepository = $productRepository;
        $this->productDetailRepository = $productDetailRepository;
    }

    public function imageProcess($image)
    {
        $resource = 'product';
        $returnImages = [];

        if ($image !== null) {
            $imagePath = StorageHelper::storeFileImage($image, $resource);
            $decodeImage = json_decode($imagePath);
            $returnImages[] = $decodeImage;
        }

        return $returnImages;
    }
    public function storeProduct(array $data)
    {
        try {
            DB::beginTransaction();

            // if (isset($data["ProductImage"])) {
            //     $imagesPath = $this->imageProcess($data["ProductImage"]);
            //     $data["ProductImage"] = $imagesPath;
            // }
            // print_r($data["ProductImage"]);exit;

            $productDetails = $data['ProductDetails'] ?? []; // Store the value before unsetting
            unset($data['ProductDetails']);

            $productCreate = $this->productRepository->create($data);

            $defaultProductDetails = [
                'ProductDetailProductId' => $productCreate->ProductId,
            ];

            $productDetailsData = [];
            foreach ($productDetails as $productDetailData) {
                $productDetailsData[] = array_merge($defaultProductDetails, $productDetailData);
            }
            $this->productDetailRepository->bulkInsert($productDetailsData);

        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
        DB::commit();
        return $productCreate;
    }
}
