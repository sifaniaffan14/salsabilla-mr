<?php

namespace App\Services;

use App\Enums\TerritoryTypeEnum;
use App\Helpers\StorageHelper;
use App\Repositories\AboutUsSettingRepository;
use App\Repositories\CountryRepository;
use App\Repositories\JumbotronSettingRepository;
use App\Repositories\ProductDetailRepository;
use App\Repositories\ProductRepository;
use App\Repositories\TerritoryRepository;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AboutUsService extends BaseService
{

    protected $aboutUsSettingRepository;

    public function __construct(
        AboutUsSettingRepository $aboutUsSettingRepository,
    ) {
        $this->aboutUsSettingRepository = $aboutUsSettingRepository;
    }

    public function storeAboutUs(array $data)
    {
        try {
            DB::beginTransaction();

            $imageVisi = $data['AboutUsVisiImage'] ?? null;
            $imageMisi = $data['AboutUsMisiImage'] ?? null;
            if ($imageVisi) {
                $imageName = 'visi_' . $data['AboutUsVisi'] . '_' . uniqid() . '.' . $imageVisi->getClientOriginalExtension();
                $imageVisi->move(public_path('storage/image/aboutUs'), $imageName);
                $data['AboutUsVisiImage'] = json_encode('/storage/image/aboutUs/' . $imageName);
            }
            if ($imageMisi) {
                $imageName = 'misi_' . $data['AboutUsMisi'] . '_' . uniqid() . '.' . $imageMisi->getClientOriginalExtension();
                $imageMisi->move(public_path('storage/image/aboutUs'), $imageName);
                $data['AboutUsMisiImage'] = json_encode('/storage/image/aboutUs/' . $imageName);
            }

            $aboutUsCreate = $this->aboutUsSettingRepository->create($data);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
        DB::commit();
        return $aboutUsCreate;
    }

    public function updateAboutUs(array $data, $aboutUsVisiImages, $aboutUsMisiImages, int $id)
    {
        DB::beginTransaction();
        try {
            $aboutUs = $this->aboutUsSettingRepository->fetchAboutUsById($id);
            if (!empty($aboutUsVisiImages)) {
                if (empty($data['AboutUsVisi'])) {
                    $aboutUsVisi = $aboutUs['AboutUsVisi'];
                    $imageName = 'visi_' . $aboutUsVisi . '_' . uniqid() . '.' . $data['AboutUsVisiImage']->getClientOriginalExtension();
                    $data['AboutUsVisiImage']->move(public_path('storage/image/aboutUs'), $imageName);
                    $data['AboutUsVisiImage'] = json_encode('/storage/image/aboutUs/' . $imageName);
                } else {
                    $imageName = 'visi_' . $data['AboutUsVisi'] . '_' . uniqid() . '.' . $data['AboutUsVisiImage']->getClientOriginalExtension();
                    $data['AboutUsVisiImage']->move(public_path('storage/image/aboutUs'), $imageName);
                    $data['AboutUsVisiImage'] = json_encode('/storage/image/aboutUs/' . $imageName);
                }
            }

            if (!empty($aboutUsMisiImages)) {
                if (empty($data['AboutUsMisi'])) {
                    $aboutUsMisi = $aboutUs['AboutUsMisi'];
                    $imageName = 'misi_' . $aboutUsMisi . '_' . uniqid() . '.' . $data['AboutUsMisiImage']->getClientOriginalExtension();
                    $data['AboutUsMisiImage']->move(public_path('storage/image/aboutUs'), $imageName);
                    $data['AboutUsMisiImage'] = json_encode('/storage/image/aboutUs/' . $imageName);
                } else {
                    $imageName = 'misi_' . $data['AboutUsMisi'] . '_' . uniqid() . '.' . $data['AboutUsMisiImage']->getClientOriginalExtension();
                    $data['AboutUsMisiImage']->move(public_path('storage/image/aboutUs'), $imageName);
                    $data['AboutUsMisiImage'] = json_encode('/storage/image/aboutUs/' . $imageName);
                }
            }
            $product = $this->aboutUsSettingRepository->update($data, $id);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
        DB::commit();
        return $product;
    }
}
