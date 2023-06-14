<?php

namespace App\Mapper\Common;

use App\Mapper\IMapper;
use App\Responses\Common\GetTokenResponse;
use App\Responses\Common\TokenResponse;
use App\Responses\IResponse;
use Psr\Http\Message\ResponseInterface;

class GetTokenMapper implements IMapper
{

    public static function map(ResponseInterface $response): IResponse
    {
        $getTokenResponse = new GetTokenResponse();

        $responseData = json_decode($response->getBody(), true);

        $getTokenResponse->setStatusCode($responseData['status_code']);
        $getTokenResponse->setStatusDescription($responseData['status_description']);

        $tokenResponse = new TokenResponse();
        $tokenResponse->setToken($responseData['data']['token']);
        $tokenResponse->setExpiredAt($responseData['data']['expires_at']);
        $tokenResponse->setIs3d($responseData['data']['is_3d']);

        $getTokenResponse->setData($tokenResponse);

        return $getTokenResponse;
    }
}
