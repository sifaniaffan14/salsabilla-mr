<?php

namespace App\Repositories;

use App\Criteria\CustomCriteria;
use App\Models\PersonalAccessTokens;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Prettus\Repository\Eloquent\BaseRepository;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

/**
 * Class PersonalAccessTokenRepositoryRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class PersonalAccessTokenRepository
{
    protected $model;
    protected $table = 'personal_access_tokens';

    public function __construct()
    {
        $this->model = new PersonalAccessTokens();
    }

    public function getWithFiltration(array $filtration)
    {
        return $this->model->where($filtration)->first();
    }

    public function updateById(array $data, int $id): bool
    {
        return DB::table($this->table)
        ->where('id', $id)
        ->update($data);
    }

    public function deleteWithFiltration(array $filtration)
    {
        return $this->model->where($filtration)->delete();
    }
    
}
