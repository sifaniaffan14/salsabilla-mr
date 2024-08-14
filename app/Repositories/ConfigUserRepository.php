<?php

namespace App\Repositories;

use App\Criteria\CustomCriteria;
use App\Models\ConfigUser;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class ConfigUserRepositoryRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class ConfigUserRepository extends BaseRepository
{
    
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return ConfigUser::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(CustomCriteria::class));
    }

    public function fetchAllUser()
    {
        $limit = request('limit', 10);
        $users = $this->paginate($limit);

        // Decode kolom ProductImage dari JSON untuk setiap produk
        foreach ($users as $user) {
            if ($user->images !== null) {
                $user->images = json_decode($user->images, true);
            }
        }

        return $users;
    }
    
    public function fetchUserById(int $id): ConfigUser
    {
        $user = $this->find($id);

        if (!$user) {
            throw new ModelNotFoundException("User with id {$id} not found", Response::HTTP_NOT_FOUND);
        }

        $user->images = json_decode($user->images, true);

        return $user;
    }

    public function updateUser(int $id, array $data): ConfigUser
    {
        $user = $this->fetchUserById($id);

        $user->update($data);

        return $user;
    }

    public function deleteUser($id)
    {
        $user = $this->fetchUserById($id);

        // Delete data
        $user->delete();
    }

    public function getUserWithFiltration(array $filtration)
    {
        return $this->where($filtration)->first();
    }

    public function getUserWithUserName(string $username)
    {
        return $this->where('username', $username)->first();
    }
}
