<?php

namespace App\Responses\Payment\GetPos;

class PosResponse
{
    /**
     * @var integer
     */
    private int $pos_id;

    /**
     * @var integer
     */
    private int $campaign_id;

    /**
     * @var int
     */
    private int $allocation_id;

    /**
     * @var int
     */
    private int $installments_number;

    /**
     * @var string
     */
    private string $card_type;

    /**
     * @var string
     */
    private string $card_program;

    /**
     * @var string
     */
    private string $card_scheme;

    /**
     * @var int
     */
    private int $payable_amount;

    /**
     * @var string
     */
    private string $hash_key;

    /**
     * @var string
     */
    private string $amount_to_be_paid;

    /**
     * @var string
     */
    private string $currency_code;

    /**
     * @var int
     */
    private int $currency_id;

    /**
     * @var string
     */
    private string $title;

    /**
     * @return int
     */
    public function getPosId(): int
    {
        return $this->pos_id;
    }

    /**
     * @param int $pos_id
     */
    public function setPosId(int $pos_id): void
    {
        $this->pos_id = $pos_id;
    }

    /**
     * @return int
     */
    public function getCampaignId(): int
    {
        return $this->campaign_id;
    }

    /**
     * @param int $campaign_id
     */
    public function setCampaignId(int $campaign_id): void
    {
        $this->campaign_id = $campaign_id;
    }

    /**
     * @return int
     */
    public function getAllocationId(): int
    {
        return $this->allocation_id;
    }

    /**
     * @param int $allocation_id
     */
    public function setAllocationId(int $allocation_id): void
    {
        $this->allocation_id = $allocation_id;
    }

    /**
     * @return int
     */
    public function getInstallmentsNumber(): int
    {
        return $this->installments_number;
    }

    /**
     * @param int $installments_number
     */
    public function setInstallmentsNumber(int $installments_number): void
    {
        $this->installments_number = $installments_number;
    }

    /**
     * @return string
     */
    public function getCardType(): string
    {
        return $this->card_type;
    }

    /**
     * @param string $card_type
     */
    public function setCardType(string $card_type): void
    {
        $this->card_type = $card_type;
    }

    /**
     * @return string
     */
    public function getCardProgram(): string
    {
        return $this->card_program;
    }

    /**
     * @param string $card_program
     */
    public function setCardProgram(string $card_program): void
    {
        $this->card_program = $card_program;
    }

    /**
     * @return string
     */
    public function getCardScheme(): string
    {
        return $this->card_scheme;
    }

    /**
     * @param string $card_scheme
     */
    public function setCardScheme(string $card_scheme): void
    {
        $this->card_scheme = $card_scheme;
    }

    /**
     * @return int
     */
    public function getPayableAmount(): int
    {
        return $this->payable_amount;
    }

    /**
     * @param int $payable_amount
     */
    public function setPayableAmount(int $payable_amount): void
    {
        $this->payable_amount = $payable_amount;
    }

    /**
     * @return string
     */
    public function getHashKey(): string
    {
        return $this->hash_key;
    }

    /**
     * @param string $hash_key
     */
    public function setHashKey(string $hash_key): void
    {
        $this->hash_key = $hash_key;
    }

    /**
     * @return string
     */
    public function getAmountToBePaid(): string
    {
        return $this->amount_to_be_paid;
    }

    /**
     * @param string $amount_to_be_paid
     */
    public function setAmountToBePaid(string $amount_to_be_paid): void
    {
        $this->amount_to_be_paid = $amount_to_be_paid;
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
     * @return int
     */
    public function getCurrencyId(): int
    {
        return $this->currency_id;
    }

    /**
     * @param int $currency_id
     */
    public function setCurrencyId(int $currency_id): void
    {
        $this->currency_id = $currency_id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
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
        return get_object_vars($this);
    }

}
