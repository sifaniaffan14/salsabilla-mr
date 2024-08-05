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

    public function getProductandDetail()
    {
        $limit = request('limit', 10);
        $products = $this->productRepository->paginate($limit);
        $productDetailsData = $products->productDetails;
        $productDetails = $this->productDetailRepository->all();
        return [
            'products' => $products,
            'productDetails' => $productDetails
        ];
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

            $productDetails = $data['ProductDetails'] ?? []; // Store the value before unsetting
            unset($data['ProductDetails']);

            $image = $data['ProductImage'] ?? null;
            if ($image) {
                $imageName = 'product_' . $data['ProductName'] . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('storage/image/product'), $imageName);
                $data['ProductImage'] = '/storage/image/product/' . $imageName;
                $data['ProductImage'] = json_encode($data['ProductImage']);
            }
            // print_r($data['ProductImage']);exit;
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
