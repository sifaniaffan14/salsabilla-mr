<?php

namespace App\Repositories;

use App\Criteria\CustomCriteria;
use App\Models\SocialMedia;
use Prettus\Repository\Eloquent\BaseRepository;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;


/**
 * Class SocialMediaRepositoryRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class SocialMediaRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return SocialMedia::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(CustomCriteria::class));
    }

    public function fetchSocialMediaById(int $id): SocialMedia
    {
        $socialMedia = $this->find($id);

        if (!$socialMedia) {
            throw new ModelNotFoundException("Social Media with id {$id} not found", Response::HTTP_NOT_FOUND);
        }

        return $socialMedia;
    }

    public function updateSocialMedia(int $id, array $data): SocialMedia
    {
        $socialMedia = $this->fetchSocialMediaById($id);

        $socialMedia->update($data);

        return $socialMedia;
    }

    public function deleteSocialMedia($id)
    {
        $socialMedia = $this->fetchSocialMediaById($id);

        // Delete data
        $socialMedia->delete();
    }
    
}
