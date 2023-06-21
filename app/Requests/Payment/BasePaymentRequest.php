<?php

namespace App\Requests\Payment;

class BasePaymentRequest
{
    /**
     * @var string
     */
    private string $cc_holder_name;

    /**
     * @var string
     */
    private string $cc_no;

    /**
     * @var integer
     */
    private int $expiry_month;

    /**
     * @var integer
     */
    private int $expiry_year;

    /**
     * @var string
     */
    private string $merchant_key;

    /**
     * @var string
     */
    private string $currency_code;

    /**
     * @var string
     */
    private string $invoice_description;

    /**
     * @var integer
     */
    private int $total;

    /**
     * @var integer
     */
    private int $installments_number;

    /**
     * @var string
     */
    private string $name;

    /**
     * @var string
     */
    private string $surname;

    /**
     * @var string
     */
    private string $hash_key;

    /**
     * @var string
     */
    private string $invoice_id;

    /**
     * @var ItemRequest[]
     */
    private array $items;


    /**
     * @return string
     */
    public function getCcHolderName(): string
    {
        return $this->cc_holder_name;
    }

    /**
     * @param string $ccHolderName
     */
    public function setCcHolderName(string $ccHolderName): void
    {
        $this->cc_holder_name = $ccHolderName;
    }

    /**
     * @return string
     */
    public function getCcNo(): string
    {
        return $this->cc_no;
    }

    /**
     * @param string $ccNo
     */
    public function setCcNo(string $ccNo): void
    {
        $this->cc_no = $ccNo;
    }

    /**
     * @return int
     */
    public function getExpiryMonth(): int
    {
        return $this->expiry_month;
    }

    /**
     * @param int $expiryMonth
     */
    public function setExpiryMonth(int $expiryMonth): void
    {
        $this->expiry_month = $expiryMonth;
    }

    /**
     * @return int
     */
    public function getExpiryYear(): int
    {
        return $this->expiry_year;
    }

    /**
     * @param int $expiryYear
     */
    public function setExpiryYear(int $expiryYear): void
    {
        $this->expiry_year = $expiryYear;
    }

    /**
     * @return string
     */
    public function getMerchantKey(): string
    {
        return $this->merchant_key;
    }

    /**
     * @param string $merchantKey
     */
    public function setMerchantKey(string $merchantKey): void
    {
        $this->merchant_key = $merchantKey;
    }

    /**
     * @return string
     */
    public function getCurrencyCode(): string
    {
        return $this->currency_code;
    }

    /**
     * @param string $currencyCode
     */
    public function setCurrencyCode(string $currencyCode): void
    {
        $this->currency_code = $currencyCode;
    }

    /**
     * @return string
     */
    public function getInvoiceDescription(): string
    {
        return $this->invoice_description;
    }

    /**
     * @param string $invoiceDescription
     */
    public function setInvoiceDescription(string $invoiceDescription): void
    {
        $this->invoice_description = $invoiceDescription;
    }

    /**
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }

    /**
     * @param int $total
     */
    public function setTotal(int $total): void
    {
        $this->total = $total;
    }

    /**
     * @return int
     */
    public function getInstallmentsNumber(): int
    {
        return $this->installments_number;
    }

    /**
     * @param int $installmentsNumber
     */
    public function setInstallmentsNumber(int $installmentsNumber): void
    {
        $this->installments_number = $installmentsNumber;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getSurname(): string
    {
        return $this->surname;
    }

    /**
     * @param string $surname
     */
    public function setSurname(string $surname): void
    {
        $this->surname = $surname;
    }

    /**
     * @return string
     */
    public function getHashKey(): string
    {
        return $this->hash_key;
    }

    /**
     * @param string $hashKey
     */
    public function setHashKey(string $hashKey): void
    {
        $this->hash_key = $hashKey;
    }

    /**
     * @return string
     */
    public function getInvoiceId(): string
    {
        return $this->invoice_id;
    }

    /**
     * @param string $invoiceId
     */
    public function setInvoiceId(string $invoiceId): void
    {
        $this->invoice_id = $invoiceId;
    }

    /**
     * @return ItemRequest[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param array $items
     */
    public function setItems(array $items): void
    {
        $this->items = $items;
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
