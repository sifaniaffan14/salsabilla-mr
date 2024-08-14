<?php

namespace App\Services;

use App\Models\PersonalAccessTokens;
use App\Repositories\AboutUsSettingRepository;
use App\Repositories\ConfigUserRepository;
use App\Services\BaseService;
use App\Services\PersonalAccessToken;
use DateTime;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService extends BaseService
{

    protected $userRepository;

    public function __construct(
        ConfigUserRepository $userRepository,
    ) {
        $this->userRepository = $userRepository;
    }

    function login(Request $post_parameters) : array
    {
        # some validations
        $credentials = $post_parameters->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string']
        ]);
        # check authentication
        if (!Auth::attempt($credentials))
            throw new ModelNotFoundException('Invalid password or Username');

        # generate token
        return $this->generateToken($post_parameters, true);
    }

    function updateToken(string $token)
    {
        # get token detail
        $filtration = [
            'token' => $token
        ];
        $personal_access_token = new PersonalAccessToken();
        $token_detail = $personal_access_token->getWithFiltration($filtration);
        if (!$token_detail)
            throw new ModelNotFoundException('Invalid token');

        # get user detail
        $userId = $token_detail->attributesToArray()['tokenable_id'];
        $filtration = [
            'id' => $userId
        ];
        $userDetail = $this->userRepository->getUserWithFiltration($filtration);
        if (!$userDetail)
            throw new ModelNotFoundException('User not found');

        # generate token
        $userDetail = new Request($userDetail->toArray());
        return $this->generateToken($userDetail);
    }

    function generateToken(Request $post_parameters, bool $user_need_login = false) : array
    {
        # define default variables
        $token = null;
        $userName = $post_parameters->username;
        $personal_access_token_repository = new PersonalAccessToken();

        # some validations
        $post_parameters->validate([
            'username' => ['required', 'string'],
        ]);

        # get user detail
        $userDetail = $this->userRepository->getUserWithUserName($userName);
        if (!$userDetail)
            throw new ModelNotFoundException('Invalid UserName');

        # check is token exist
        $filtration = [
            'tokenable_id' => $userDetail->attributesToArray()['id']
        ];

        $token_detail = $personal_access_token_repository->getWithFiltration($filtration);
        if (empty($token_detail) && !$user_need_login) {
            throw new ModelNotFoundException("User has not logged in cause user token's not found");
        }
        else {
            if (!empty($token_detail)) {
                # check token
                $date_time_now = new DateTime();
                $token = $token_detail->attributesToArray();
                // $token['abilities'] = json_decode($token['abilities'], true);
                $token_expires_at = new DateTime($token['expires_at']);
                if ($token_expires_at < $date_time_now || abs(strtotime($token['expires_at']) - time()) <= 300) {
                    # remove old token
                    $personal_access_token_repository->deleteWithFiltration($filtration);

                    # generate token
                    $userDetail->createToken($userDetail->username, [], now()->addDay());

                    # get token
                    $token_detail = $personal_access_token_repository->getWithFiltration($filtration);
                    $token = $token_detail->attributesToArray();
                    $token['abilities'] = json_decode($token['abilities'], true);
                }
            }
            else {
                # generate token
                $check = $userDetail->createToken($userDetail->username, [], now()->addDay());

                # get token
                $token_detail = $personal_access_token_repository->getWithFiltration($filtration);
                $token = $token_detail->attributesToArray();
                $token['abilities'] = json_decode($token['abilities'], true);
            }
        }


        # return response
        return [
            'token_detail' => $token,
            'user_detail' => $userDetail->toArray()
        ];
    }

    function logout(Request $post_parameters)
    {
        # get token
        $token = $post_parameters->bearerToken();
        $filtration = [
            'token' => $token
        ];
        $personal_access_token = new PersonalAccessToken();
        $token_detail = $personal_access_token->getWithFiltration($filtration);
        if (!$token_detail)
            throw new ModelNotFoundException('Invalid token');

        # get user detail
        $filtration = [
            'id' => $token_detail->attributesToArray()['tokenable_id']
        ];
        $userDetail = $this->userRepository->getUserWithFiltration($filtration);
        if (!$userDetail)
            throw new ModelNotFoundException('User not found');

        # revoke token
        $revoke = $userDetail->tokens()->where('token', $token)->delete();
        if (!$revoke)
            throw new ModelNotFoundException('Logout failed');

        # return response
        return [
            "success" => true,
	        "message" => "Logout successfully"
        ];
    }
}
