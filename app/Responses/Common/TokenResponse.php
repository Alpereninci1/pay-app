<?php

namespace App\Responses\Common;

class TokenResponse
{
    /**
     * @var string
     */
    private string $token;


    /**
     * @var boolean
     */
    private bool $is_3d;

    /**
     * @var string
     */
    private string $expired_at;

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $token
     */
    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    /**
     * @return bool
     */
    public function isIs3d(): bool
    {
        return $this->is_3d;
    }

    /**
     * @param bool $is_3d
     */
    public function setIs3d(bool $is_3d): void
    {
        $this->is_3d = $is_3d;
    }

    /**
     * @return string
     */
    public function getExpiredAt(): string
    {
        return $this->expired_at;
    }

    /**
     * @param string $expired_at
     */
    public function setExpiredAt(string $expired_at): void
    {
        $this->expired_at = $expired_at;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
