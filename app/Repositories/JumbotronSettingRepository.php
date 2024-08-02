<?php

namespace App\Repositories;

use App\Criteria\CustomCriteria;
use App\Models\JumbotronSetting;
use Prettus\Repository\Eloquent\BaseRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;


/**
 * Class JumbotronSettingRepositoryRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class JumbotronSettingRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return JumbotronSetting::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(CustomCriteria::class));
    }
    
    public function fetchJumbotronSettingById(int $id): JumbotronSetting
    {
        $jumbotronSetting = $this->find($id);

        if (!$jumbotronSetting) {
            throw new ModelNotFoundException("Jumbtron with id {$id} not found", Response::HTTP_NOT_FOUND);
        }

        return $jumbotronSetting;
    }

    public function updateJumbotronSetting(int $id, array $data): JumbotronSetting
    {
        $jumbotronSetting = $this->fetchJumbotronSettingById($id);

        $jumbotronSetting->update($data);

        return $jumbotronSetting;
    }

    public function deleteJumbotronSetting($id)
    {
        $jumbotronSetting = $this->fetchJumbotronSettingById($id);

        // Delete data
        $jumbotronSetting->delete();
    }
}
