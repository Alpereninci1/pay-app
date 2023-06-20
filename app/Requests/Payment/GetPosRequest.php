<?php

namespace App\Requests\Payment;

class GetPosRequest
{

    /**
     * @var string
     */
    private string $credit_card;

    /**
     * @var string
     */
    private string $amount;

    /**
     * @var string
     */
    private string $currency_code;

    /**
     * @var string
     */
    private string $is_2d;

    /**
     * @var string
     */
    private string $merchant_key;

    /**
     * @return string
     */
    public function getMerchantKey(): string
    {
        return $this->merchant_key;
    }

    /**
     * @param string $merchant_key
     */
    public function setMerchantKey(string $merchant_key): void
    {
        $this->merchant_key = $merchant_key;
    }


    /**
     * @return string
     */
    public function getCreditCard(): string
    {
        return $this->credit_card;
    }

    /**
     * @param string $credit_card
     */
    public function setCreditCard(string $credit_card): void
    {
        $this->credit_card = $credit_card;
    }

    /**
     * @return string
     */
    public function getAmount(): string
    {
        return $this->amount;
    }

    /**
     * @param string $amount
     */
    public function setAmount(string $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * @return string
     */
    public function getCurrencyCode(): string
    {
        return $this->currency_code;
    }

    /**
     * @param string $currency_code
     */
    public function setCurrencyCode(string $currency_code): void
    {
        $this->currency_code = $currency_code;
    }

    /**
     * @return string
     */
    public function getIs2d(): string
    {
        return $this->is_2d;
    }

    /**
     * @param string $is_2d
     */
    public function setIs2d(string $is_2d): void
    {
        $this->is_2d = $is_2d;
    }

    public function getData()
    {
        $data = [
            'credit_card' => $this->getCreditCard(),
            'amount' => $this->getAmount(),
            'currency_code' => $this->getCurrencyCode(),
            'merchant_key' => $this->getMerchantKey(),
            'is_2d' => $this->getIs2d(),
        ];

        return json_encode($data);
    }
}
