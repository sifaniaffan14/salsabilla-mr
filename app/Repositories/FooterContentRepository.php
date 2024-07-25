<?php

namespace App\Repositories;

use App\Criteria\CustomCriteria;
use App\Models\FooterContent;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Prettus\Repository\Eloquent\BaseRepository;
use Illuminate\Http\Response;

/**
 * Class FooterContentRepositoryRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class FooterContentRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return FooterContent::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(CustomCriteria::class));
    }

    public function fetchFooterContentById(int $id): FooterContent
    {
        $footerContent = $this->find($id);

        if (!$footerContent) {
            throw new ModelNotFoundException("Footer Content with id {$id} not found", Response::HTTP_NOT_FOUND);
        }

        return $footerContent;
    }

    public function updateFooterContent(int $id, array $data): FooterContent
    {
        $footerContent = $this->fetchFooterContentById($id);

        $footerContent->update($data);

        return $footerContent;
    }

    public function deleteFooterContent($id)
    {
        $footerContent = $this->fetchFooterContentById($id);

        // Delete data
        $footerContent->delete();
    }
    
}
