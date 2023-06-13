<?php

namespace App\Responses\Common;

use App\Responses\IResponse;

class GetTokenResponse extends BaseResponse implements IResponse
{
    /**
     * @var TokenResponse
     */
    private TokenResponse $data;

    /**
     * @return TokenResponse
     */
    public function getData(): TokenResponse
    {
        return $this->data;
    }

    /**
     * @param TokenResponse $data
     */
    public function setData(TokenResponse $data): void
    {
        $this->data = $data;
    }

    /**
     * @return false|string
     */
    public function toJson(): bool|string
    {
        return json_encode($this->toArray());
    }


    /**
     * @return array
     */
    public function toArray(): array
    {
        $params = get_object_vars($this);
        if (!empty($this->getData())) {

            $params['data'] = $this->getData()->toArray();
        }
        return $params;
    }

}
