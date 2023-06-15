<?php

namespace App\Requests\Payment;

class BasePaymentRequest
{
    /**
     * @var string
     */
    private string $ccHolderName;

    /**
     * @var string
     */
    private string $ccNo;

    /**
     * @var integer
     */
    private int $expiryMonth;

    /**
     * @var integer
     */
    private int $expiryYear;

    /**
     * @var string
     */
    private string $merchantKey;

    /**
     * @var string
     */
    private string $currencyCode;

    /**
     * @var string
     */
    private string $invoiceDescription;

    /**
     * @var integer
     */
    private int $total;

    /**
     * @var integer
     */
    private int $installmentsNumber;

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
    private string $hashKey;

    /**
     * @var string
     */
    private string $invoiceId;

    /**
     * @var ItemRequest[]
     */
    private array $items;


    /**
     * @return string
     */
    public function getCcHolderName(): string
    {
        return $this->ccHolderName;
    }

    /**
     * @param string $ccHolderName
     */
    public function setCcHolderName(string $ccHolderName): void
    {
        $this->ccHolderName = $ccHolderName;
    }

    /**
     * @return string
     */
    public function getCcNo(): string
    {
        return $this->ccNo;
    }

    /**
     * @param string $ccNo
     */
    public function setCcNo(string $ccNo): void
    {
        $this->ccNo = $ccNo;
    }

    /**
     * @return int
     */
    public function getExpiryMonth(): int
    {
        return $this->expiryMonth;
    }

    /**
     * @param int $expiryMonth
     */
    public function setExpiryMonth(int $expiryMonth): void
    {
        $this->expiryMonth = $expiryMonth;
    }

    /**
     * @return int
     */
    public function getExpiryYear(): int
    {
        return $this->expiryYear;
    }

    /**
     * @param int $expiryYear
     */
    public function setExpiryYear(int $expiryYear): void
    {
        $this->expiryYear = $expiryYear;
    }

    /**
     * @return string
     */
    public function getMerchantKey(): string
    {
        return $this->merchantKey;
    }

    /**
     * @param string $merchantKey
     */
    public function setMerchantKey(string $merchantKey): void
    {
        $this->merchantKey = $merchantKey;
    }

    /**
     * @return string
     */
    public function getCurrencyCode(): string
    {
        return $this->currencyCode;
    }

    /**
     * @param string $currencyCode
     */
    public function setCurrencyCode(string $currencyCode): void
    {
        $this->currencyCode = $currencyCode;
    }

    /**
     * @return string
     */
    public function getInvoiceDescription(): string
    {
        return $this->invoiceDescription;
    }

    /**
     * @param string $invoiceDescription
     */
    public function setInvoiceDescription(string $invoiceDescription): void
    {
        $this->invoiceDescription = $invoiceDescription;
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
        return $this->installmentsNumber;
    }

    /**
     * @param int $installmentsNumber
     */
    public function setInstallmentsNumber(int $installmentsNumber): void
    {
        $this->installmentsNumber = $installmentsNumber;
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
        return $this->hashKey;
    }

    /**
     * @param string $hashKey
     */
    public function setHashKey(string $hashKey): void
    {
        $this->hashKey = $hashKey;
    }

    /**
     * @return string
     */
    public function getInvoiceId(): string
    {
        return $this->invoiceId;
    }

    /**
     * @param string $invoiceId
     */
    public function setInvoiceId(string $invoiceId): void
    {
        $this->invoiceId = $invoiceId;
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



}
