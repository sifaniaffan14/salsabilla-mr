<?php

namespace App\Services;

use App\Repositories\ConfigUserRepository;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class UserService extends BaseService
{

    protected $userRepository;

    public function __construct(
        ConfigUserRepository $userRepository,
    ) {
        $this->userRepository = $userRepository;
    }

    public function storeUser(array $data)
    {
        try {
            DB::beginTransaction();

            $image = $data['UserImages'] ?? null;
            if ($image) {
                $imageName = 'user_' . $data['UserName'] . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('storage/image/user'), $imageName);
                $data['UserImages'] = json_encode('/storage/image/user/' . $imageName);
            }

            if (!empty($data['UserPassword'])) {
                $data['UserPassword'] = bcrypt($data['UserPassword']);
            }
            $userCreate = $this->userRepository->create($data);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
        DB::commit();
        return $userCreate;
    }

    public function updateUser(array $data, $userImages, int $id)
    {
        DB::beginTransaction();
        try {
            $user = $this->userRepository->fetchUserById($id);
            if (!empty($userImages)) {
                if (empty($data['UserName'])) {
                    $userName = $user['UserName'];
                    $imageName = 'user_' . $userName . '_' . uniqid() . '.' . $data['UserImages']->getClientOriginalExtension();
                    $data['UserImages']->move(public_path('storage/image/user'), $imageName);
                    $data['UserImages'] = json_encode('/storage/image/user/' . $imageName);
                } else {
                    $imageName = 'user_' . $data['UserName'] . '_' . uniqid() . '.' . $data['UserImages']->getClientOriginalExtension();
                    $data['UserImages']->move(public_path('storage/image/user'), $imageName);
                    $data['UserImages'] = json_encode('/storage/image/user/' . $imageName);
                }
            }
            $user = $this->userRepository->update($data, $id);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
        DB::commit();
        return $user;
    }
}
