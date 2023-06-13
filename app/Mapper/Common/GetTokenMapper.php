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

        $getTokenResponse->setStatusCode($response->status_code);
        $getTokenResponse->setStatusDescription($response->status_description);

        $tokenResponse = new TokenResponse();
        $tokenResponse->setToken($response->data->token);
        $tokenResponse->setExpiredAt($response->data->expired_at);
        $tokenResponse->setIs3d($response->data->is_3d);

        $getTokenResponse->setData($tokenResponse);

        return $getTokenResponse;
    }
}
