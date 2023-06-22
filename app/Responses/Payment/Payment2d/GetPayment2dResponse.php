<?php

namespace App\Responses\Payment\Payment2d;

use App\Responses\IResponse;
use App\Responses\Payment\BaseResponse;

class GetPayment2dResponse extends BaseResponse implements IResponse
{
    /**
     * @var Payment2dResponse
     */
    private Payment2dResponse $data;

    /**
     * @return Payment2dResponse
     */
    public function getData(): Payment2dResponse
    {
        return $this->data;
    }

    /**
     * @param null|Payment2dResponse $data
     */
    public function setData(Payment2dResponse $data): void
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
