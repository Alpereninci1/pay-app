<?php

namespace App\Requests\Payment;

class Payment3dRequest extends BasePaymentRequest
{
    /**
     * @var string
     */
    private string $returnUrl;

    /**
     * @var string
     */
    private string $cancelUrl;

    /**
     * @return string
     */
    public function getReturnUrl(): string
    {
        return $this->returnUrl;
    }

    /**
     * @param string $returnUrl
     */
    public function setReturnUrl(string $returnUrl): void
    {
        $this->returnUrl = $returnUrl;
    }

    /**
     * @return string
     */
    public function getCancelUrl(): string
    {
        return $this->cancelUrl;
    }

    /**
     * @param string $cancelUrl
     */
    public function setCancelUrl(string $cancelUrl): void
    {
        $this->cancelUrl = $cancelUrl;
    }


}
