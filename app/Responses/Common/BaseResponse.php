<?php

namespace App\Responses\Common;

class BaseResponse
{
    /**
     *  @var int
     */
    private int $status_code;

    /**
     * @var string
     */
    private string $status_description;

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->status_code;
    }

    /**
     * @param int $status_code
     */
    public function setStatusCode(int $status_code): void
    {
        $this->status_code = $status_code;
    }

    /**
     * @return string
     */
    public function getStatusDescription(): string
    {
        return $this->status_description;
    }

    /**
     * @param string $status_description
     */
    public function setStatusDescription(string $status_description): void
    {
        $this->status_description = $status_description;
    }

}
