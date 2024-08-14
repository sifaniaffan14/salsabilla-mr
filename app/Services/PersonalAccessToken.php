<?php

namespace App\Services;

use App\Models\PersonalAccessTokens;
use App\Repositories\PersonalAccessTokenRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class PersonalAccessToken
{

    protected PersonalAccessTokenRepository $personalAccessTokenRepository;

    public function __construct()
    {
        $this->personalAccessTokenRepository = new PersonalAccessTokenRepository();
    }

    function getWithFiltration(array $filtration)
    {
        # get token detail
        return $this->personalAccessTokenRepository->getWithFiltration($filtration);
    }

    public function updateById(array $parameters, string $id): bool
    {
        # update token detail
        return $this->personalAccessTokenRepository->updateById($parameters, $id);
    }

    function deleteWithFiltration(array $filtration)
    {
        # delete token
        return $this->personalAccessTokenRepository->deleteWithFiltration($filtration);
    }
}
