<?php

namespace App\Services;

use App\Enums\TerritoryTypeEnum;
use App\Helpers\StorageHelper;
use App\Repositories\CountryRepository;
use App\Repositories\JumbotronSettingRepository;
use App\Repositories\ProductDetailRepository;
use App\Repositories\ProductRepository;
use App\Repositories\TerritoryRepository;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class JumbotronSettingService extends BaseService
{

    protected $jumbotronRepository;

    public function __construct(
        JumbotronSettingRepository $jumbotronRepository,
    ) {
        $this->jumbotronRepository = $jumbotronRepository;
    }

    public function storeJumbotron(array $data)
    {
        try {
            DB::beginTransaction();

            $image = $data['JumbotronImage'] ?? null;
            if ($image) {
                $imageName = 'jumbotron_' . $data['JumbotronTittle'] . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('storage/image/jumbotron'), $imageName);
                $data['JumbotronImage'] = json_encode('/storage/image/jumbotron/' . $imageName);
            }
            // print_r($data['ProductImage']);exit;
            $jumbotronCreate = $this->jumbotronRepository->create($data);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
        DB::commit();
        return $jumbotronCreate;
    }
}
