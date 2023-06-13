<?php

namespace App\Mapper;

use App\Responses\IResponse;
use Psr\Http\Message\ResponseInterface;

interface IMapper
{
    /**
     * @param ResponseInterface $response
     * @return IResponse
     */
    public static function map(ResponseInterface $response): IResponse;
}
