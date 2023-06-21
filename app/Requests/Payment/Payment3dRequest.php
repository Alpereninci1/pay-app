<?php

namespace App\Requests\Payment;

class Payment3dRequest extends BasePaymentRequest
{
    /**
     * @var string
     */
    private string $return_url;

    /**
     * @var string
     */
    private string $cancel_url;

    /**
     * @return string
     */
    public function getReturnUrl(): string
    {
        return $this->return_url;
    }

    /**
     * @param string $returnUrl
     */
    public function setReturnUrl(string $returnUrl): void
    {
        $this->return_url = $returnUrl;
    }

    /**
     * @return string
     */
    public function getCancelUrl(): string
    {
        return $this->cancel_url;
    }

    /**
     * @param string $cancelUrl
     */
    public function setCancelUrl(string $cancelUrl): void
    {
        $this->cancel_url = $cancelUrl;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $parent = parent::toArray();

        $child = get_object_vars($this);

        $data = array_merge($parent, $child);
        return $data;

    }


}
