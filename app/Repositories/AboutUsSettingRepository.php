<?php

namespace App\Repositories;

use App\Criteria\CustomCriteria;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Models\AboutUsSetting;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;

/**
 * Class AboutUsSettingRepositoryRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class AboutUsSettingRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return AboutUsSetting::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(CustomCriteria::class));
    }

    public function fetchAboutUsById(int $id): AboutUsSetting
    {
        $aboutUs = $this->find($id);

        if (!$aboutUs) {
            throw new ModelNotFoundException("About Us with id {$id} not found", Response::HTTP_NOT_FOUND);
        }

        return $aboutUs;
    }

    public function updateAboutUs(int $id, array $data): AboutUsSetting
    {
        $aboutUs = $this->fetchAboutUsById($id);

        $aboutUs->update($data);

        return $aboutUs;
    }

    public function deleteAboutUs($id)
    {
        $aboutUs = $this->fetchAboutUsById($id);

        // Delete data
        $aboutUs->delete();
    }
    
}
