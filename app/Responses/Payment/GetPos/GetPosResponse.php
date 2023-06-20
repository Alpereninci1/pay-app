<?php

namespace App\Responses\Payment\GetPos;

use App\Responses\IResponse;
use App\Responses\Payment\BaseResponse;

class GetPosResponse extends BaseResponse implements IResponse
{
    /**
     * @var PosResponse[]
     */
    private array $data;

    /**
     * @return PosResponse[]
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param PosResponse[] $data
     */
    public function setData(array $data): void
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
        $params = [];

        if (!empty($this->getData())) {

            $params['status_code'] = $this->getStatusCode();
            $params['status_description'] = $this->getStatusDescription();
            foreach ($this->getData() as $data) {
                $params['data'][] = $data->toArray();
            }

        }
        //dd($params);
        return $params;
    }
}
