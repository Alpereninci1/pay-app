<?php

namespace App\Responses\Payment\Payment2d;

class Payment2dResponse
{
    /**
     * @var string
     */
    private string $invoice_id;

    /**
     * @var string
     */
    private string $order_no;

    /**
     * @var string
     */
    private string $order_id;

    /**
     * @var string
     */
    private string $credit_card_no;

    /**
     * @var string
     */
    private string $transaction_type;

    /**
     * @var integer
     */
    private int $payment_status;

    /**
     * @var integer
     */
    private int $payment_method;

    /**
     * @var integer
     */
    private int $error_code;

    /**
     * @var string
     */
    private string $error;

    /**
     * @var integer
     */
    private int $auth_code;

    /**
     * @var float
     */
    private float $merchant_commission;

    /**
     * @var float
     */
    private float $user_commission;

    /**
     * @var integer
     */
    private int $merchant_commission_percentage;

    /**
     * @var integer
     */
    private int $merchant_commission_fixed;

    /**
     * @var string
     */
    private string $hash_key;

    /**
     * @var string
     */
    private string $original_bank_error_code;

    /**
     * @var string
     */
    private string $original_bank_error_description;

    /**
     * @return string
     */
    public function getInvoiceId(): string
    {
        return $this->invoice_id;
    }

    /**
     * @param string $invoice_id
     */
    public function setInvoiceId(string $invoice_id): void
    {
        $this->invoice_id = $invoice_id;
    }

    /**
     * @return string
     */
    public function getOrderNo(): string
    {
        return $this->order_no;
    }

    /**
     * @param string $order_no
     */
    public function setOrderNo(string $order_no): void
    {
        $this->order_no = $order_no;
    }

    /**
     * @return string
     */
    public function getOrderId(): string
    {
        return $this->order_id;
    }

    /**
     * @param string $order_id
     */
    public function setOrderId(string $order_id): void
    {
        $this->order_id = $order_id;
    }

    /**
     * @return string
     */
    public function getCreditCardNo(): string
    {
        return $this->credit_card_no;
    }

    /**
     * @param string $credit_card_no
     */
    public function setCreditCardNo(string $credit_card_no): void
    {
        $this->credit_card_no = $credit_card_no;
    }

    /**
     * @return string
     */
    public function getTransactionType(): string
    {
        return $this->transaction_type;
    }

    /**
     * @param string $transaction_type
     */
    public function setTransactionType(string $transaction_type): void
    {
        $this->transaction_type = $transaction_type;
    }

    /**
     * @return int
     */
    public function getPaymentStatus(): int
    {
        return $this->payment_status;
    }

    /**
     * @param int $payment_status
     */
    public function setPaymentStatus(int $payment_status): void
    {
        $this->payment_status = $payment_status;
    }

    /**
     * @return int
     */
    public function getPaymentMethod(): int
    {
        return $this->payment_method;
    }

    /**
     * @param int $payment_method
     */
    public function setPaymentMethod(int $payment_method): void
    {
        $this->payment_method = $payment_method;
    }

    /**
     * @return int
     */
    public function getErrorCode(): int
    {
        return $this->error_code;
    }

    /**
     * @param int $error_code
     */
    public function setErrorCode(int $error_code): void
    {
        $this->error_code = $error_code;
    }

    /**
     * @return string
     */
    public function getError(): string
    {
        return $this->error;
    }

    /**
     * @param string $error
     */
    public function setError(string $error): void
    {
        $this->error = $error;
    }

    /**
     * @return int
     */
    public function getAuthCode(): int
    {
        return $this->auth_code;
    }

    /**
     * @param int $auth_code
     */
    public function setAuthCode(int $auth_code): void
    {
        $this->auth_code = $auth_code;
    }

    /**
     * @return float
     */
    public function getMerchantCommission(): float
    {
        return $this->merchant_commission;
    }

    /**
     * @param float $merchant_commission
     */
    public function setMerchantCommission(float $merchant_commission): void
    {
        $this->merchant_commission = $merchant_commission;
    }

    /**
     * @return float
     */
    public function getUserCommission(): float
    {
        return $this->user_commission;
    }

    /**
     * @param float $user_commission
     */
    public function setUserCommission(float $user_commission): void
    {
        $this->user_commission = $user_commission;
    }

    /**
     * @return int
     */
    public function getMerchantCommissionPercentage(): int
    {
        return $this->merchant_commission_percentage;
    }

    /**
     * @param int $merchant_commission_percentage
     */
    public function setMerchantCommissionPercentage(int $merchant_commission_percentage): void
    {
        $this->merchant_commission_percentage = $merchant_commission_percentage;
    }

    /**
     * @return int
     */
    public function getMerchantCommissionFixed(): int
    {
        return $this->merchant_commission_fixed;
    }

    /**
     * @param int $merchant_commission_fixed
     */
    public function setMerchantCommissionFixed(int $merchant_commission_fixed): void
    {
        $this->merchant_commission_fixed = $merchant_commission_fixed;
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
    public function getOriginalBankErrorCode(): string
    {
        return $this->original_bank_error_code;
    }

    /**
     * @param string $original_bank_error_code
     */
    public function setOriginalBankErrorCode(string $original_bank_error_code): void
    {
        $this->original_bank_error_code = $original_bank_error_code;
    }

    /**
     * @return string
     */
    public function getOriginalBankErrorDescription(): string
    {
        return $this->original_bank_error_description;
    }

    /**
     * @param string $original_bank_error_description
     */
    public function setOriginalBankErrorDescription(string $original_bank_error_description): void
    {
        $this->original_bank_error_description = $original_bank_error_description;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return get_object_vars($this);
    }

}
