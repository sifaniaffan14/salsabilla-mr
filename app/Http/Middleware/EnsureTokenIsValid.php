<?php

namespace App\Http\Middleware;

use Closure;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
// use App\Services\Auth\PersonalAccessToken;
use App\Services\PersonalAccessToken;
// use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;

class EnsureTokenIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        # retrieve parameters
        $parameters = $request->all();
        $token = $request->bearerToken();

        # get token
        $filtration = [
            'token' => $token
        ];
        $personal_access_token = new PersonalAccessToken();
        $token_detail = $personal_access_token->getWithFiltration($filtration);

        # validate token
        if (!$token_detail) {
            return $this->returnResponse([
                'success' => false,
                'message' => 'Unauthorized'
            ],401);
        }
        else {
            # check token
            $date_time_now = new DateTime();
            $token = $token_detail->attributesToArray();
            // $token['abilities'] = json_decode($token['abilities'], true);
            $token_expires_at = new DateTime($token['expires_at']);
            if ($token_expires_at < $date_time_now) {
                # update token expired for 1 minute
                $update_parameters = [
                    'expires_at' => date("Y-m-d H:i:s", strtotime("+1 minute"))
                ];
                $personal_access_token = new PersonalAccessToken();
                $personal_access_token->updateById($update_parameters, $token['id']);

                # remove old token
//                $personal_access_token->deleteWithFiltration($filtration);

                # return response
                return $this->returnResponse([
                    'success' => false,
                    'message' => 'Token expired'
                ],403);
            }
        }

        return $next($request);
    }

    function returnResponse(mixed $data, int $code = 200): JsonResponse
    {
        return response()->json($data, $code);
    }
}
